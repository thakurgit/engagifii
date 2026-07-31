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
$org_default_img = ENGAGIFII_ASSETS_URL . '/images/org-list-grey.png';
$contact_default_img = ENGAGIFII_ASSETS_URL . '/images/org-list-grey.png';
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

if (!function_exists('engagifii_org_should_mask_field')) {
    function engagifii_org_should_mask_field($fieldKey, $is_logged_in, $guest_hidden_fields) {
        if ($is_logged_in) {
            return false;
        }
        $normalized = preg_replace('/[^a-z0-9]/', '', strtolower($fieldKey));
        foreach ($guest_hidden_fields as $hiddenField) {
            if ($normalized === preg_replace('/[^a-z0-9]/', '', strtolower($hiddenField))) {
                return true;
            }
        }
        return false;
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
$key_contacts = !empty($response->keyContacts) && is_array($response->keyContacts) ? $response->keyContacts : [];
$mask_phone = engagifii_org_should_mask_field('phoneNumbers', $is_logged_in, $guest_hidden_fields);
$mask_email = engagifii_org_should_mask_field('primaryEmail', $is_logged_in, $guest_hidden_fields);
$mask_website = engagifii_org_should_mask_field('website', $is_logged_in, $guest_hidden_fields);

$org_overview_html = trim(engagifii_org_get_custom_field($response->customFields ?? [], 'Organization Bio'));
if ($org_overview_html === '') {
    $org_overview_html = trim(engagifii_org_get_custom_field($response->customFields ?? [], 'Overview'));
}
if ($org_overview_html === '') {
    $org_overview_html = trim(engagifii_org_get_custom_field($response->customFields ?? [], 'Organization/Company Description'));
}
?>

<style>
.org-detail-page {
    --org-detail-primary: <?php echo esc_attr($theme_color); ?>;
    --org-detail-primary-dark: <?php echo esc_attr(darken_color($theme_color, 1.35)); ?>;
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
.org-detail-contact-link {
    color: #fff;
    text-decoration: underline;
    white-space: nowrap;
    font-size: 0.95rem;
}
.org-detail-contact-link:hover {
    color: #fff;
}
.org-detail-masked {
    filter: blur(4px);
    user-select: none;
}
.org-detail-content {
    margin-top: 24px;
    align-items: flex-start;
}
.org-detail-overview {
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
    height: 100%;
}
.org-detail-overview-title {
    background: #f5f7fa;
    border-bottom: 1px solid #e0e0e0;
    color: #1f2d3d;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0;
    padding: 12px 18px;
}
.org-detail-overview-body {
    padding: 18px;
    color: #333;
    font-size: 0.95rem;
    line-height: 1.6;
}
.org-detail-overview-body p:last-child {
    margin-bottom: 0;
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

            <?php if ($phone_number !== '') : ?>
                <div class="org-detail-phone <?php echo $mask_phone ? 'org-detail-masked' : ''; ?>">
                    <i class="fas fa-phone"></i>
                    <?php echo $mask_phone ? esc_html__('Hidden', 'engagifii') : esc_html($phone_number); ?>
                </div>
            <?php endif; ?>

            <div class="org-detail-actions">
                <?php if ($website_url !== '') : ?>
                    <?php if ($mask_website) : ?>
                        <span class="org-detail-website-btn org-detail-masked"><?php esc_html_e('Visit Website', 'engagifii'); ?></span>
                    <?php else : ?>
                        <a href="<?php echo esc_url($website_url); ?>" target="_blank" rel="noopener noreferrer" class="org-detail-website-btn">
                            <?php esc_html_e('Visit Website', 'engagifii'); ?>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($email_address !== '') : ?>
                    <?php if ($mask_email) : ?>
                        <span class="org-detail-email-icon org-detail-masked"><i class="far fa-envelope"></i></span>
                    <?php else : ?>
                        <a href="mailto:<?php echo esc_attr($email_address); ?>" class="org-detail-email-icon" title="<?php echo esc_attr($email_address); ?>">
                            <i class="far fa-envelope"></i>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (!empty($key_contacts) || $org_overview_html !== '') : ?>
        <div class="row org-detail-content">
            <?php if (!empty($key_contacts)) : ?>
                <div class="<?php echo $org_overview_html !== '' ? 'col-lg-5' : 'col-12'; ?> mb-4 mb-lg-0">
                    <div class="org-detail-contacts-wrap">
                        <div class="org-detail-contacts-tab"><?php esc_html_e('Contacts', 'engagifii'); ?></div>
                        <div class="org-detail-contacts-list">
                            <?php foreach ($key_contacts as $contact) :
                                $contact_name = esc_html($contact->fullName ?? trim(($contact->firstName ?? '') . ' ' . ($contact->lastName ?? '')));
                                $contact_title = '';
                                if (!empty($contact->peoplePosition[0]->positionName)) {
                                    $contact_title = esc_html($contact->peoplePosition[0]->positionName);
                                } elseif (!empty($contact->title)) {
                                    $contact_title = esc_html($contact->title);
                                }

                                $contact_img = engagifii_org_is_valid_image($contact->imageThumbUrl ?? '')
                                    ? esc_url($contact->imageThumbUrl)
                                    : esc_url($contact_default_img);

                                $profile_link = !empty($contact->email)
                                    ? 'mailto:' . esc_attr($contact->email)
                                    : '#';
                                ?>
                                <div class="org-detail-contact-row">
                                    <div class="org-detail-contact-photo">
                                        <?php if (engagifii_org_is_valid_image($contact->imageThumbUrl ?? '')) : ?>
                                            <img src="<?php echo $contact_img; ?>" alt="<?php echo esc_attr($contact_name); ?>">
                                        <?php else : ?>
                                            <i class="fas fa-user"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="org-detail-contact-info">
                                        <div class="org-detail-contact-name"><?php echo $contact_name; ?></div>
                                        <?php if ($contact_title !== '') : ?>
                                            <div class="org-detail-contact-title"><?php echo $contact_title; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?php echo esc_url($profile_link); ?>" class="org-detail-contact-link">
                                        <?php esc_html_e('View Profile', 'engagifii'); ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($org_overview_html !== '') : ?>
                <div class="<?php echo !empty($key_contacts) ? 'col-lg-7' : 'col-12'; ?>">
                    <div class="org-detail-overview">
                        <h2 class="org-detail-overview-title"><?php esc_html_e('Overview', 'engagifii'); ?></h2>
                        <div class="org-detail-overview-body">
                            <?php echo wp_kses_post($org_overview_html); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
