<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', 86400);
    session_set_cookie_params(86400);
    session_start();
}

$enabled_modules = get_option('engagifii_enabled_modules', array());
$setupCompleted = get_option('engagifii_setup_completed');
if ($setupCompleted && !in_array('organization_directory', $enabled_modules)) {
    echo '<div class="alert alert-warning text-center" style="margin:40px 0;font-size:1.2em;">This module is deactivated. Please contact the admin.</div>';
    return;
}

if (!function_exists('engagifii_get_organization_id_from_request')) {
    function engagifii_get_organization_id_from_request() {
        $sources = array();
        if (!empty($_GET) && is_array($_GET)) {
            $sources[] = $_GET;
        }
        if (!empty($_REQUEST) && is_array($_REQUEST)) {
            $sources[] = $_REQUEST;
        }
        foreach ($sources as $params) {
            foreach ($params as $key => $value) {
                $normalizedKey = strtolower((string) $key);
                if (in_array($normalizedKey, array('organizationid', 'orgid'), true) && $value !== '') {
                    return sanitize_text_field(wp_unslash($value));
                }
            }
        }
        return '';
    }
}

if (!function_exists('engagifii_org_response_has_data')) {
    function engagifii_org_response_has_data($response) {
        if (empty($response) || !is_object($response)) {
            return false;
        }
        $id = $response->id ?? $response->Id ?? null;
        $name = $response->name ?? $response->Name ?? null;
        return !empty($id) || !empty($name);
    }
}

$orgId = engagifii_get_organization_id_from_request();
if (empty($orgId)) {
    echo '<h5 class="text-center pt-5">Organization ID not available</h5>';
    return;
}

$api = new Engagifii_API();
$response = $api->getOrganizationBasicDetails($orgId);

if (!engagifii_org_response_has_data($response)) {
    echo '<h5 class="text-center pt-5">Organization details not found.</h5>';
    return;
}

$options = get_option('ebt_api_settings');
$theme_color = !empty($options['engagifii_theme_color']) ? $options['engagifii_theme_color'] : '#008896';
$org_detail_section_color = !empty($options['organization_detail_section_color'])
    ? $options['organization_detail_section_color']
    : $theme_color;
$org_default_img = ENGAGIFII_ASSETS_URL . '/images/org-list-grey.png';
$is_logged_in = is_user_logged_in();
$guest_hidden_fields = defined('ORGANIZATION_GUEST_HIDDEN_FIELDS') ? ORGANIZATION_GUEST_HIDDEN_FIELDS : ['phoneNumbers', 'primaryEmail'];

if (!function_exists('engagifii_org_get_custom_field')) {
    function engagifii_org_get_custom_field($customFields, $fieldName) {
        if (empty($customFields) || !is_array($customFields)) {
            return '';
        }
        foreach ($customFields as $field) {
            if (isset($field->fieldName) && strcasecmp($field->fieldName, $fieldName) === 0) {
                return $field->fieldValue ?? '';
            }
        }
        return '';
    }
}

if (!function_exists('engagifii_org_is_valid_image')) {
    function engagifii_org_is_valid_image($url) {
        if (empty($url)) {
            return false;
        }
        if (strpos($url, 'non-member') !== false || strpos($url, '/assets/images/') === 0) {
            return false;
        }
        return (bool) filter_var($url, FILTER_VALIDATE_URL);
    }
}

if (!function_exists('engagifii_org_group_custom_fields')) {
    function engagifii_org_group_custom_fields($customFields, $excludeFieldNames = array(), $fieldVisibilityCallback = null) {
        $groups = array();
        if (empty($customFields) || !is_array($customFields)) {
            return $groups;
        }

        $excludeNormalized = array();
        foreach ($excludeFieldNames as $excludeName) {
            $excludeNormalized[] = strtolower(trim($excludeName));
        }

        foreach ($customFields as $field) {
            $fieldName = trim($field->fieldName ?? '');
            $fieldValue = trim($field->fieldValue ?? '');
            $groupName = trim($field->groupName ?? '');
            $fieldId = trim($field->fieldId ?? '');

            if ($fieldName === '' || $fieldValue === '' || $groupName === '') {
                continue;
            }
            if (in_array(strtolower($fieldName), $excludeNormalized, true)) {
                continue;
            }
            if (is_callable($fieldVisibilityCallback) && !$fieldVisibilityCallback($fieldName, $fieldId)) {
                continue;
            }

            if (!isset($groups[$groupName])) {
                $groups[$groupName] = array();
            }
            $groups[$groupName][] = array(
                'fieldName' => $fieldName,
                'fieldValue' => $fieldValue,
                'fieldId' => $fieldId,
            );
        }

        return $groups;
    }
}

if (!function_exists('engagifii_org_build_detail_visibility_map')) {
    function engagifii_org_build_detail_visibility_map($visible_fields) {
        $map = array(
            'has_settings' => false,
            'col_names' => array(),
            'field_ids' => array(),
        );

        if (empty($visible_fields) || !is_array($visible_fields)) {
            return $map;
        }

        $map['has_settings'] = true;
        foreach ($visible_fields as $field_json) {
            $field = json_decode(stripslashes($field_json), true);
            if (!$field || empty($field['colName'])) {
                continue;
            }
            $map['col_names'][strtolower(trim($field['colName']))] = true;
            if (!empty($field['fieldId'])) {
                $map['field_ids'][strtolower(trim($field['fieldId']))] = true;
            }
        }

        return $map;
    }
}

if (!function_exists('engagifii_org_detail_field_visible')) {
    function engagifii_org_detail_field_visible($colName, $fieldId, $visibility_map) {
        if (empty($visibility_map['has_settings'])) {
            return true;
        }

        $colKey = strtolower(trim((string) $colName));
        if ($colKey !== '' && !empty($visibility_map['col_names'][$colKey])) {
            return true;
        }

        $fieldKey = strtolower(trim((string) $fieldId));
        if ($fieldKey !== '' && !empty($visibility_map['field_ids'][$fieldKey])) {
            return true;
        }

        return false;
    }
}

if (!function_exists('engagifii_org_render_custom_field_value')) {
    function engagifii_org_render_custom_field_value($value) {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }
        if ($value !== wp_strip_all_tags($value)) {
            return wp_kses_post($value);
        }
        return esc_html($value);
    }
}

if (!function_exists('engagifii_org_normalize_external_url')) {
    function engagifii_org_normalize_external_url($url) {
        $url = trim((string) $url);
        if ($url === '') {
            return '';
        }
        if (preg_match('/^https?:\/\//i', $url)) {
            return $url;
        }
        if (strpos($url, '//') === 0) {
            return 'https:' . $url;
        }
        return 'https://' . ltrim($url, '/');
    }
}

if (!function_exists('engagifii_org_get_social_icon_class')) {
    function engagifii_org_get_social_icon_class($platform) {
        $platform = strtolower(trim((string) $platform));
        if (strpos($platform, 'linkedin') !== false) {
            return 'fab fa-linkedin-in';
        }
        if (strpos($platform, 'facebook') !== false) {
            return 'fab fa-facebook-f';
        }
        if (strpos($platform, 'instagram') !== false) {
            return 'fab fa-instagram';
        }
        if (strpos($platform, 'twitter') !== false || $platform === 'x') {
            return 'fab fa-twitter';
        }
        if (strpos($platform, 'youtube') !== false) {
            return 'fab fa-youtube';
        }
        return 'fas fa-share-alt';
    }
}

if (!function_exists('engagifii_org_get_social_platform_key')) {
    function engagifii_org_get_social_platform_key($platform) {
        $platform = strtolower(trim((string) $platform));
        if (strpos($platform, 'linkedin') !== false) {
            return 'linkedin';
        }
        if (strpos($platform, 'facebook') !== false) {
            return 'facebook';
        }
        if (strpos($platform, 'instagram') !== false) {
            return 'instagram';
        }
        if (strpos($platform, 'twitter') !== false || $platform === 'x') {
            return 'twitter';
        }
        if (strpos($platform, 'youtube') !== false) {
            return 'youtube';
        }
        return preg_replace('/[^a-z0-9]+/', '-', $platform);
    }
}

if (!function_exists('engagifii_org_get_social_pages')) {
    function engagifii_org_get_social_pages($socialPages) {
        $pages = array();
        if (empty($socialPages) || !is_array($socialPages)) {
            return $pages;
        }

        $seen = array();
        foreach ($socialPages as $page) {
            $platform = trim($page->platform ?? '');
            $url = engagifii_org_normalize_external_url($page->url ?? '');
            if ($platform === '' || $url === '') {
                continue;
            }
            $key = engagifii_org_get_social_platform_key($platform);
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $pages[] = array(
                'platform' => $platform,
                'url' => $url,
                'icon' => engagifii_org_get_social_icon_class($platform),
            );
        }

        return $pages;
    }
}

$org_name = esc_html($response->name ?? $response->Name ?? '');
$org_logo = engagifii_org_is_valid_image($response->imageThumbUrl ?? '') ? esc_url($response->imageThumbUrl) : esc_url($org_default_img);

$website_raw = trim($response->website ?? '');
if ($website_raw === '') {
    $website_raw = trim(strip_tags(engagifii_org_get_custom_field($response->customFields ?? [], 'Website')));
}
$website_url = '';
if ($website_raw !== '') {
    $website_url = preg_match('/^https?:\/\//i', $website_raw) ? $website_raw : 'https://' . $website_raw;
}

$phone_number = trim($response->phoneNumber ?? '');
if ($phone_number === '' && !empty($response->contactDetails) && is_array($response->contactDetails)) {
    foreach ($response->contactDetails as $contactDetail) {
        if (!empty($contactDetail->phoneNumber)) {
            $phone_number = $contactDetail->phoneNumber;
            break;
        }
    }
}

$email_address = trim($response->email ?? '');
$website_contacts = !empty($response->websiteContacts) && is_array($response->websiteContacts)
    ? $response->websiteContacts
    : [];
$social_pages = engagifii_org_get_social_pages($response->socialPages ?? array());

$org_overview_html = trim(engagifii_org_get_custom_field($response->customFields ?? [], 'Organization Bio'));
if ($org_overview_html === '') {
    $org_overview_html = trim(engagifii_org_get_custom_field($response->customFields ?? [], 'Organization Overview'));
}
if ($org_overview_html === '') {
    $org_overview_html = trim(engagifii_org_get_custom_field($response->customFields ?? [], 'Overview'));
}
if ($org_overview_html === '') {
    $org_overview_html = trim(engagifii_org_get_custom_field($response->customFields ?? [], 'Organization/Company Description'));
}
$has_overview = $org_overview_html !== '' && trim(wp_strip_all_tags($org_overview_html)) !== '';

$detail_visible_fields = defined('ORGANIZATION_DETAIL_VISIBLE_FIELDS') ? ORGANIZATION_DETAIL_VISIBLE_FIELDS : array();
$detail_visibility_map = engagifii_org_build_detail_visibility_map($detail_visible_fields);
$detail_field_visible = function($colName, $fieldId = '') use ($detail_visibility_map) {
    return engagifii_org_detail_field_visible($colName, $fieldId, $detail_visibility_map);
};

$overview_field_names = array(
    'Organization Bio',
    'Organization Overview',
    'Overview',
    'Organization/Company Description',
    'Website',
);
$custom_field_groups = engagifii_org_group_custom_fields(
    $response->customFields ?? array(),
    $overview_field_names,
    $detail_field_visible
);

$show_overview = false;
if ($has_overview) {
    if (!$detail_visibility_map['has_settings'] || $detail_field_visible('Overview')) {
        $show_overview = true;
    } else {
        foreach (($response->customFields ?? array()) as $overview_field) {
            $overview_name = trim($overview_field->fieldName ?? '');
            if ($overview_name === '' || !in_array($overview_name, $overview_field_names, true)) {
                continue;
            }
            if ($detail_field_visible($overview_name, $overview_field->fieldId ?? '')) {
                $show_overview = true;
                break;
            }
        }
    }
}

$show_phone = $phone_number !== '' && $detail_field_visible('phoneNumbers');
$show_website = $website_url !== '' && $detail_field_visible('Website');
$show_email = $email_address !== '' && $detail_field_visible('primaryEmail');
$show_social = !empty($social_pages) && $detail_field_visible('SocialPages');
$show_contacts = !empty($website_contacts) && $detail_field_visible('WebsiteContacts');

$guest_mask_keys = engagifii_org_build_guest_mask_keys($guest_hidden_fields, $options);

$mask_phone = engagifii_org_should_mask_field('phoneNumbers', $is_logged_in, $guest_mask_keys);
$mask_email = engagifii_org_should_mask_field('primaryEmail', $is_logged_in, $guest_mask_keys);
$mask_website = engagifii_org_should_mask_field('Website', $is_logged_in, $guest_mask_keys);
$mask_social = engagifii_org_should_mask_field('SocialPages', $is_logged_in, $guest_mask_keys);
$mask_contacts = engagifii_org_should_mask_field('WebsiteContacts', $is_logged_in, $guest_mask_keys);

$mask_overview = false;
if ($show_overview && !$is_logged_in) {
    if (engagifii_org_should_mask_field('Overview', $is_logged_in, $guest_mask_keys)) {
        $mask_overview = true;
    } else {
        foreach (($response->customFields ?? array()) as $overview_field) {
            $overview_name = trim($overview_field->fieldName ?? '');
            if ($overview_name === '' || !in_array($overview_name, $overview_field_names, true)) {
                continue;
            }
            if (engagifii_org_should_mask_field($overview_name, $is_logged_in, $guest_mask_keys, $overview_field->fieldId ?? '')) {
                $mask_overview = true;
                break;
            }
        }
    }
}
?>

<style>
.org-detail-page {
    --org-detail-primary: <?php echo esc_attr($org_detail_section_color); ?>;
    --org-detail-primary-dark: <?php echo esc_attr(darken_color($org_detail_section_color, 1.35)); ?>;
}
.org-detail-header {
    background: var(--org-detail-primary);
    border-radius: 0 0 24px 24px;
    color: #fff;
    padding: 32px;
    display: flex;
    align-items: center;
    gap: 32px;
    margin-bottom: 0;
}
.org-detail-logo-wrap {
    width: 160px;
    height: 160px;
    min-width: 160px;
    background: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 16px;
}
.org-detail-logo-wrap img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.org-detail-info h1 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 12px;
    color: #fff;
}
.org-detail-phone {
    font-size: 1rem;
    margin-bottom: 16px;
}
.org-detail-phone i {
    margin-right: 8px;
}
.org-detail-actions {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.org-detail-website-btn {
    background: #d9edf7;
    color: #1f2d3d !important;
    border: none;
    border-radius: 6px;
    padding: 8px 18px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
}
.org-detail-website-btn:hover {
    background: #c4e3f3;
    text-decoration: none;
    color: #1f2d3d !important;
}
.org-detail-email-icon {
    width: 42px;
    height: 42px;
    border: 2px solid #fff;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-decoration: none;
}
.org-detail-email-icon:hover {
    color: #fff;
    text-decoration: none;
    background: rgba(255,255,255,0.12);
}
.org-detail-icon-links {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.org-detail-social-icon {
    width: 42px;
    height: 42px;
    border: 2px solid #fff;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
}
.org-detail-social-icon:hover {
    color: #fff;
    text-decoration: none;
    background: rgba(255,255,255,0.12);
}
.org-detail-contacts-wrap {
    max-width: 100%;
    margin-top: 0;
}
.org-detail-contacts-tab {
    display: inline-block;
    background: var(--org-detail-primary);
    color: #fff;
    font-weight: 600;
    padding: 8px 18px;
    border-radius: 8px 8px 0 0;
    margin-bottom: 0;
}
.org-detail-contacts-list {
    background: var(--org-detail-primary-dark);
    border-radius: 0 12px 12px 12px;
    max-height: 320px;
    overflow-y: auto;
    padding: 0;
}
.org-detail-contact-row {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    border-bottom: 1px solid rgba(255,255,255,0.25);
    color: #fff;
}
.org-detail-contact-row:last-child {
    border-bottom: none;
}
.org-detail-contact-photo {
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 50%;
    overflow: hidden;
    background: #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
}
.org-detail-contact-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.org-detail-contact-photo i {
    color: #666;
    font-size: 20px;
}
.org-detail-contact-info {
    flex: 1;
    min-width: 0;
}
.org-detail-contact-name {
    font-weight: 700;
    margin-bottom: 2px;
}
.org-detail-contact-title {
    font-style: italic;
    opacity: 0.9;
    font-size: 0.95rem;
}

.org-detail-contact-meta {
    font-size: 0.9rem;
    margin-top: 4px;
    line-height: 1.45;
}
.org-detail-contact-meta a {
    color: #fff;
    text-decoration: underline;
}
.org-detail-contact-meta a:hover {
    color: #fff;
}
.org-detail-contact-meta i {
    width: 16px;
    margin-right: 6px;
    opacity: 0.85;
}

.org-detail-masked {
    filter: blur(4px);
    user-select: none;
}
.org-detail-content {
    margin-top: 24px;
}
.org-detail-section + .org-detail-section {
    margin-top: 28px;
}
.org-detail-overview {
    overflow: hidden;
    background: transparent;
}
.org-detail-overview-body {
    padding: 18px;
    color: #333;
    font-size: 0.95rem;
    line-height: 1.6;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 0 12px 12px 12px;
}
.org-detail-overview-body p:last-child {
    margin-bottom: 0;
}
.org-detail-custom-groups {
    overflow: hidden;
    background: transparent;
}
.org-detail-custom-groups-body {
    padding: 18px;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 0 12px 12px 12px;
}
.org-detail-custom-field {
    margin-bottom: 16px;
}
.org-detail-custom-field:last-child {
    margin-bottom: 0;
}
.org-detail-custom-field-label {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 4px;
}
.org-detail-custom-field-value {
    font-size: 0.95rem;
    color: #222;
    line-height: 1.4;
    word-break: break-word;
}
.org-detail-custom-field-value p:last-child {
    margin-bottom: 0;
}
.org-detail-custom-field-value p + p {
    margin-top: 8px;
}
@media (max-width: 767px) {
    .org-detail-header {
        flex-direction: column;
        text-align: center;
        padding: 24px 16px;
    }
    .org-detail-actions {
        justify-content: center;
    }
    .org-detail-contacts-wrap {
        max-width: 100%;
    }
}
</style>

<div class="org-detail-page mb-4">
    <div class="mb-3">
        <a onclick="window.history.back();" style="cursor:pointer;color:#2568EF;" class="go-back">
            <i class="fal fa-arrow-left mr-2"></i> Go Back
        </a>
    </div>

    <div class="org-detail-header">
        <div class="org-detail-logo-wrap">
            <img src="<?php echo $org_logo; ?>" alt="<?php echo esc_attr($org_name); ?>">
        </div>
        <div class="org-detail-info">
            <h1><?php echo $org_name; ?></h1>

            <?php if ($show_phone) : ?>
                <div class="org-detail-phone <?php echo $mask_phone ? 'org-detail-masked' : ''; ?>">
                    <i class="fas fa-phone"></i>
                    <?php echo $mask_phone ? esc_html__('Hidden', 'engagifii') : esc_html($phone_number); ?>
                </div>
            <?php endif; ?>

            <div class="org-detail-actions">
                <?php if ($show_website) : ?>
                    <?php if ($mask_website) : ?>
                        <span class="org-detail-website-btn org-detail-masked"><?php esc_html_e('Visit Website', 'engagifii'); ?></span>
                    <?php else : ?>
                        <a href="<?php echo esc_url($website_url); ?>" target="_blank" rel="noopener noreferrer" class="org-detail-website-btn">
                            <?php esc_html_e('Visit Website', 'engagifii'); ?>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($show_email || $show_social) : ?>
                    <div class="org-detail-icon-links">
                        <?php if ($show_email) : ?>
                            <?php if ($mask_email) : ?>
                                <span class="org-detail-email-icon org-detail-masked" title="<?php esc_attr_e('Email', 'engagifii'); ?>"><i class="far fa-envelope"></i></span>
                            <?php else : ?>
                                <a href="mailto:<?php echo esc_attr($email_address); ?>" class="org-detail-email-icon" title="<?php echo esc_attr($email_address); ?>">
                                    <i class="far fa-envelope"></i>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($show_social) : ?>
                        <?php foreach ($social_pages as $social_page) : ?>
                            <?php if ($mask_social) : ?>
                                <span class="org-detail-social-icon org-detail-masked" title="<?php echo esc_attr($social_page['platform']); ?>"><i class="<?php echo esc_attr($social_page['icon']); ?>"></i></span>
                            <?php else : ?>
                                <a href="<?php echo esc_url($social_page['url']); ?>" class="org-detail-social-icon" target="_blank" rel="noopener noreferrer" title="<?php echo esc_attr($social_page['platform']); ?>">
                                    <i class="<?php echo esc_attr($social_page['icon']); ?>"></i>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($show_overview) : ?>
        <div class="org-detail-content org-detail-section org-detail-section-overview">
            <div class="org-detail-overview">
                <div class="org-detail-contacts-tab"><?php esc_html_e('Overview', 'engagifii'); ?></div>
                <div class="org-detail-overview-body<?php echo $mask_overview ? ' org-detail-masked' : ''; ?>">
                    <?php if ($mask_overview) : ?>
                        <?php esc_html_e('Hidden', 'engagifii'); ?>
                    <?php else : ?>
                        <?php echo wp_kses_post($org_overview_html); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php foreach ($custom_field_groups as $group_name => $group_fields) :
        if (empty($group_fields)) {
            continue;
        }
        ?>
        <div class="org-detail-content org-detail-section org-detail-section-custom">
            <div class="org-detail-custom-groups">
                <div class="org-detail-contacts-tab"><?php echo esc_html($group_name); ?></div>
                <div class="org-detail-custom-groups-body">
                    <div class="row">
                        <?php foreach ($group_fields as $field) :
                            $field_has_html = trim($field['fieldValue']) !== wp_strip_all_tags($field['fieldValue']);
                            $field_col_class = $field_has_html ? 'col-12' : 'col-md-6';
                            $mask_custom_field = engagifii_org_should_mask_field(
                                $field['fieldName'],
                                $is_logged_in,
                                $guest_mask_keys,
                                $field['fieldId'] ?? ''
                            );
                            ?>
                            <div class="<?php echo esc_attr($field_col_class); ?> org-detail-custom-field">
                                <div class="org-detail-custom-field-label"><?php echo esc_html($field['fieldName']); ?></div>
                                <div class="org-detail-custom-field-value<?php echo $mask_custom_field ? ' org-detail-masked' : ''; ?>">
                                    <?php if ($mask_custom_field) : ?>
                                        <?php esc_html_e('Hidden', 'engagifii'); ?>
                                    <?php else : ?>
                                        <?php echo engagifii_org_render_custom_field_value($field['fieldValue']); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if ($show_contacts) : ?>
        <div class="org-detail-content org-detail-section org-detail-section-contacts">
            <div class="org-detail-contacts-wrap">
                <div class="org-detail-contacts-tab"><?php esc_html_e('Contacts', 'engagifii'); ?></div>
                <div class="org-detail-contacts-list">
                    <?php if ($mask_contacts) : ?>
                        <div class="org-detail-contact-row">
                            <div class="org-detail-contact-info">
                                <span class="org-detail-masked"><?php esc_html_e('Hidden', 'engagifii'); ?></span>
                            </div>
                        </div>
                    <?php else : ?>
                    <?php foreach ($website_contacts as $contact) :
                        $contact_name = esc_html(trim($contact->name ?? ''));
                        $contact_title = esc_html(trim($contact->title ?? ''));
                        $contact_phone = trim($contact->phone ?? '');
                        $contact_email = trim($contact->email ?? '');
                        $contact_linkedin = trim($contact->linkedIn ?? '');

                        $linkedin_url = '';
                        if ($contact_linkedin !== '') {
                            $linkedin_url = preg_match('/^https?:\/\//i', $contact_linkedin)
                                ? $contact_linkedin
                                : 'https://' . ltrim($contact_linkedin, '/');
                        }
                        ?>
                        <div class="org-detail-contact-row">
                            <div class="org-detail-contact-photo">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="org-detail-contact-info">
                                <?php if ($contact_name !== '') : ?>
                                    <div class="org-detail-contact-name"><?php echo $contact_name; ?></div>
                                <?php endif; ?>
                                <?php if ($contact_title !== '') : ?>
                                    <div class="org-detail-contact-title"><?php echo $contact_title; ?></div>
                                <?php endif; ?>
                                <div class="org-detail-contact-meta">
                                    <?php if ($contact_phone !== '') : ?>
                                        <div>
                                            <i class="fas fa-phone"></i>
                                            <?php if ($mask_phone) : ?>
                                                <span class="org-detail-masked"><?php esc_html_e('Hidden', 'engagifii'); ?></span>
                                            <?php else : ?>
                                                <a href="tel:<?php echo esc_attr(preg_replace('/[^\d+]/', '', $contact_phone)); ?>"><?php echo esc_html($contact_phone); ?></a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($contact_email !== '') : ?>
                                        <div>
                                            <i class="far fa-envelope"></i>
                                            <?php if ($mask_email) : ?>
                                                <span class="org-detail-masked"><?php esc_html_e('Hidden', 'engagifii'); ?></span>
                                            <?php else : ?>
                                                <a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($contact_linkedin !== '' && !$mask_social) : ?>
                                        <div>
                                            <i class="fab fa-linkedin"></i>
                                            <a href="<?php echo esc_url($linkedin_url); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($contact_linkedin); ?></a>
                                        </div>
                                    <?php elseif ($contact_linkedin !== '' && $mask_social) : ?>
                                        <div>
                                            <i class="fab fa-linkedin"></i>
                                            <span class="org-detail-masked"><?php esc_html_e('Hidden', 'engagifii'); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
