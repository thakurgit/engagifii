<?php
/**
 * Shared card layout template picker for directory grid settings.
 *
 * Expected vars:
 * - $card_layout_settings_path array e.g. ['organization_settings', 'grid']
 * - $card_layout_field_name string e.g. 'card_layout'
 * - $cards_per_row_field_name string e.g. 'classic_cards_per_row'
 * - $cards_per_row_wrapper_id string e.g. 'org-classic-cards-per-row-wrapper'
 * - $options array ebt_api_settings
 */
if (!isset($options) || !is_array($options)) {
    $options = get_option('ebt_api_settings');
}
$settings_ref = $options;
foreach ((array) $card_layout_settings_path as $segment) {
    $settings_ref = isset($settings_ref[$segment]) && is_array($settings_ref[$segment]) ? $settings_ref[$segment] : array();
}
$current_layout = isset($settings_ref[$card_layout_field_name]) ? $settings_ref[$card_layout_field_name] : 'classic';
if ($current_layout === 'detailed') {
    $current_layout = 'classic';
}
$settings_name_prefix = 'ebt_api_settings';
foreach ((array) $card_layout_settings_path as $segment) {
    $settings_name_prefix .= '[' . $segment . ']';
}
$layout_field_name = $settings_name_prefix . '[' . $card_layout_field_name . ']';
$cards_per_row_field_name_full = $settings_name_prefix . '[' . $cards_per_row_field_name . ']';
$current_cards_per_row = isset($settings_ref[$cards_per_row_field_name]) ? intval($settings_ref[$cards_per_row_field_name]) : 4;
$show_cards_per_row = ($current_layout === 'classic') ? 'block' : 'none';
$layouts = array(
    'classic' => array(
        'name' => 'Standard',
        'description' => 'Traditional card with image on top and details below',
        'preview' => ENGAGIFII_ASSETS_URL . '/images/layout-classic.png',
    ),
    'modern' => array(
        'name' => 'Modern',
        'description' => 'Clean design with side image and horizontal layout',
        'preview' => ENGAGIFII_ASSETS_URL . '/images/layout-modern.png',
    ),
    'minimal' => array(
        'name' => 'Minimal',
        'description' => 'Simple card with icon and minimal details',
        'preview' => ENGAGIFII_ASSETS_URL . '/images/layout-minimal.png',
    ),
);
?>
<style>
.card-layout-selection .layout-preview {
    background: #f9f9f9;
    padding: 10px;
    border-radius: 4px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.card-layout-selection .layout-preview img {
    display: block;
    margin: 0 auto;
    width: auto;
    height: auto;
    object-fit: contain;
    border-radius: 2px;
}
.card-layout-selection .layout-preview--classic {
    min-height: 0;
    max-height: 240px;
}
.card-layout-selection .layout-preview--classic img {
    max-height: 220px;
    max-width: 85%;
}
.card-layout-selection .layout-preview--modern {
    min-height: 0;
    max-height: 130px;
}
.card-layout-selection .layout-preview--modern img {
    max-height: 115px;
    max-width: 100%;
    object-position: center center;
}
.card-layout-selection .layout-preview--minimal {
    min-height: 0;
    max-height: 160px;
}
.card-layout-selection .layout-preview--minimal img {
    max-height: 145px;
    max-width: 100%;
}
</style>
<div class="cols-wrapper card-layout-selection">
    <h3><span class="dashicons dashicons-screenoptions"></span>&nbsp;&nbsp;Card Layout Template</h3><i>Select the card layout style for grid view.</i><hr>
    <div class="layout-options" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-top: 15px;">
        <?php foreach ($layouts as $layout_key => $layout_info) :
            $checked = ($current_layout === $layout_key) ? 'checked' : '';
            ?>
            <div class="layout-option" style="border: 2px solid #ddd; padding: 15px; border-radius: 8px; cursor: pointer; <?php echo $checked ? 'border-color: #0073aa; background-color: #f0f8ff;' : ''; ?>" data-layout="<?php echo esc_attr($layout_key); ?>">
                <label style="cursor: pointer; display: block;">
                    <input type="radio"
                           name="<?php echo esc_attr($layout_field_name); ?>"
                           value="<?php echo esc_attr($layout_key); ?>"
                           <?php echo $checked; ?>
                           style="margin-right: 10px;">
                    <strong><?php echo esc_html($layout_info['name']); ?></strong>
                    <p style="margin: 10px 0; color: #666; font-size: 13px;"><?php echo esc_html($layout_info['description']); ?></p>
                    <div class="layout-preview layout-preview--<?php echo esc_attr($layout_key); ?>">
                        <img src="<?php echo esc_url($layout_info['preview']); ?>" alt="<?php echo esc_attr($layout_info['name']); ?> layout preview" />
                    </div>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
    jQuery(document).ready(function($) {
        $('.card-layout-selection .layout-option').click(function() {
            var $section = $(this).closest('.card-layout-selection');
            $section.find('.layout-option').css({'border-color': '#ddd', 'background-color': 'transparent'});
            $(this).css({'border-color': '#0073aa', 'background-color': '#f0f8ff'});
            $(this).find('input[type="radio"]').prop('checked', true);
            var selectedLayout = $(this).data('layout');
            $('#<?php echo esc_js($cards_per_row_wrapper_id); ?>').toggle(selectedLayout === 'classic');
        });
    });
    </script>

    <div id="<?php echo esc_attr($cards_per_row_wrapper_id); ?>" style="display:<?php echo esc_attr($show_cards_per_row); ?>; margin-top: 20px; padding: 15px; background: #f0f8ff; border: 1px solid #bde; border-radius: 8px;">
        <label style="font-weight: 600; font-size: 14px; display: block; margin-bottom: 8px;">
            <span class="dashicons dashicons-grid-view" style="vertical-align: middle;"></span>&nbsp;
            Cards Per Row (Standard View)
        </label>
        <p style="color: #666; font-size: 13px; margin-bottom: 10px;">Choose how many cards appear in each row for the Standard card layout.</p>
        <select name="<?php echo esc_attr($cards_per_row_field_name_full); ?>"
                style="width: 120px; padding: 6px 10px; border-radius: 4px; border: 1px solid #ccc;">
            <?php foreach ([2, 3, 4] as $n) : ?>
                <option value="<?php echo $n; ?>" <?php selected($current_cards_per_row, $n); ?>>
                    <?php echo $n; ?> per row
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
