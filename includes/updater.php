<?php
defined( 'ABSPATH' ) || exit;

define('ENGAGIFII_GITHUB_REPO', 'thakurgit/engagifii');

/**
 * 1. Check for plugin updates via GitHub Releases API
 */
add_filter('pre_set_site_transient_update_plugins', 'engagifii_check_github_update');
function engagifii_check_github_update($transient) {
    if (empty($transient->checked)) {
        return $transient;
    }

    $remote = engagifii_get_github_release_data();
    if (!$remote) {
        return $transient;
    }

    if (version_compare(ENGAGIFII_VERSION, $remote->version, '<')) {
        $transient->response[plugin_basename(PLUGIN_FILE_PATH)] = (object) array(
            'slug'        => 'engagifii',
            'plugin'      => plugin_basename(PLUGIN_FILE_PATH),
            'new_version' => $remote->version,
            'url'         => 'https://github.com/' . ENGAGIFII_GITHUB_REPO,
            'package'     => $remote->download_url,
            'icons'       => array(
				'1x' => ENGAGIFII_ASSETS_URL . '/images/icon_128x128.png'
			),
        );
    }

    return $transient;
}

/**
 * 2. Supply plugin information popup modal in WP Admin
 */
add_filter('plugins_api', 'engagifii_github_plugin_info', 20, 3);
function engagifii_github_plugin_info($res, $action, $args) {
    if ('plugin_information' !== $action || basename(dirname(PLUGIN_FILE_PATH)) !== $args->slug) {
        return $res;
    }

    $remote = engagifii_get_github_release_data();
    if (!$remote) {
        return $res;
    }

    $res = new stdClass();
    $res->name          = 'Engagifii';
    $res->slug          = 'engagifii';
    $res->author        = '<a href="https://engagifii.com/">Engagifii</a>';
    $res->author_profile= 'https://engagifii.com/';
    $res->version       = $remote->version;
    $res->tested        = '6.7.1';
    $res->requires      = '6.0';
    $res->requires_php  = '7.4';
    $res->download_link = $remote->download_url;
    $res->trunk         = $remote->download_url;
    $res->last_updated  = $remote->last_updated;
    $res->sections      = array(
        'description'  => 'Engagifii API to fetch Bills, events, courses and classes.',
        'installation' => 'Click the activate button and that\'s it.',
        'screenshots'  => 'Plugin Images Goes Here',
        'changelog'    => $remote->changelog,
    );
    $res->banners = array(
		'low'  => ENGAGIFII_ASSETS_URL . '/images/banner-772x250.jpg',
		'high' => ENGAGIFII_ASSETS_URL . '/images/banner-1544x500.jpg'
	);
	$res->icons = array(
		'1x' => ENGAGIFII_ASSETS_URL . '/images/icon_128x128.png'
	);
    return $res;
}

/**
 * Helper: Fetch & Cache GitHub Latest 3 Releases Info
 */
function engagifii_get_github_release_data() {
    $transient_key = 'engagifii_github_release_info';
    $cached = get_transient($transient_key);
    if ($cached !== false) {
        return $cached;
    }

    // Fetch the 3 most recent releases from GitHub API
    $url = 'https://api.github.com/repos/' . ENGAGIFII_GITHUB_REPO . '/releases?per_page=3';
    $response = wp_remote_get($url, array(
        'timeout' => 10,
        'headers' => array(
            'User-Agent' => 'WordPress/' . get_bloginfo('version') . '; ' . get_bloginfo('url')
        )
    ));

    if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
        return false;
    }

    $releases = json_decode(wp_remote_retrieve_body($response));
    if (empty($releases) || !is_array($releases)) {
        return false;
    }

    // The first item in array is the latest release
    $latest = $releases[0];
    if (empty($latest->tag_name)) {
        return false;
    }

    $release_info = new stdClass();
    $release_info->version      = ltrim($latest->tag_name, 'v');
    $release_info->download_url = 'https://github.com/' . ENGAGIFII_GITHUB_REPO . '/releases/latest/download/engagifii.zip';
    $release_info->last_updated = date('Y-m-d H:i:s', strtotime($latest->published_at));

    // Build multi-release changelog HTML for the last 3 releases
    $changelog_html = '';
    foreach ($releases as $rel) {
        $ver_num  = ltrim($rel->tag_name, 'v');
        $rel_date = !empty($rel->published_at) ? date('F d, Y', strtotime($rel->published_at)) : '';

        $changelog_html .= '<h4>' . esc_html($ver_num) . ($rel_date ? ' – ' . esc_html($rel_date) : '') . '</h4>';
        if (!empty($rel->body)) {
            $changelog_html .= engagifii_format_changelog_markdown($rel->body);
        } else {
            $changelog_html .= '<p>Release ' . esc_html($ver_num) . '</p>';
        }
    }

    // Append link to full CHANGELOG.md file on GitHub
    $changelog_html .= '<p style="margin-top: 15px;"><em>Full changelog available at <a href="https://github.com/' . ENGAGIFII_GITHUB_REPO . '/blob/master/CHANGELOG.md" target="_blank" rel="noopener">CHANGELOG.md</a></em></p>';

    $release_info->changelog = $changelog_html;

    // Cache release info for 6 hours
    set_transient($transient_key, $release_info, 6 * HOUR_IN_SECONDS);

    return $release_info;
}

/**
 * Format markdown release body into HTML for WP Admin modal
 */
function engagifii_format_changelog_markdown($text) {
    if (empty($text)) {
        return '';
    }
    // Convert headers like ### Changed to <strong>Changed</strong>
    $text = preg_replace('/^###\s+(.+)$/m', '<strong>$1</strong>', $text);
    // Convert list items like - item to <li>item</li>
    $text = preg_replace('/^-\s+(.+)$/m', '<li>$1</li>', $text);
    // Group consecutive <li> elements into <ul>
    $text = preg_replace('/((?:<li>.*?<\/li>\s*)+)/s', '<ul>$1</ul>', $text);
    return wpautop($text);
}
