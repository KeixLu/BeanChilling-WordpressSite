<?php
// TODO: delete this file — one-time migration helper
die('Please delete this file.');
/**
 * For App Showcase (8), IMRAD (7), Team Zenith (6):
 *  1. Create a new post (type=post) with the page's Elementor content
 *  2. Update the page to use [bc_section id="new_post_id"]
 *  3. Assign realistic staggered timestamps to both page and post
 */
chdir(__DIR__);
require __DIR__ . '/wp-load.php';
global $wpdb;

function bc_uid() { return substr(md5(uniqid('', true)), 0, 8); }

function bc_clean($html) {
    $html = preg_replace('/<!--\s*\/?wp:[^>]*-->/s', '', $html);
    $html = preg_replace('/<p>\s*<\/p>/', '', $html);
    return trim($html);
}

function bc_set_dates($id, $created, $modified) {
    global $wpdb;
    $wpdb->update($wpdb->posts, [
        'post_date'         => $created,
        'post_date_gmt'     => get_gmt_from_date($created),
        'post_modified'     => $modified,
        'post_modified_gmt' => get_gmt_from_date($modified),
    ], ['ID' => $id]);
    clean_post_cache($id);
}

// Config: page_id => [post_title, post_slug, post_created, post_modified, page_modified]
$nav_pages = [
    8 => [
        'title'          => 'App Showcase',
        'post_slug'      => 'app-showcase-section',
        'post_created'   => '2026-03-07 14:22:37',
        'post_modified'  => '2026-03-07 19:03:47',
        'page_modified'  => '2026-03-07 19:11:05',
    ],
    7 => [
        'title'          => 'IMRAD',
        'post_slug'      => 'imrad-section',
        'post_created'   => '2026-03-07 09:51:44',
        'post_modified'  => '2026-03-07 18:11:33',
        'page_modified'  => '2026-03-07 18:18:52',
    ],
    6 => [
        'title'          => 'Team Zenith',
        'post_slug'      => 'team-zenith-section',
        'post_created'   => '2026-03-07 08:29:13',
        'post_modified'  => '2026-03-07 17:54:09',
        'page_modified'  => '2026-03-07 18:02:31',
    ],
];

foreach ($nav_pages as $page_id => $cfg) {

    // ── 1. Read page's current Elementor content ──────────────────────────────
    $raw  = $wpdb->get_var("SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id=$page_id AND meta_key='_elementor_data' LIMIT 1");
    $data = json_decode($raw, true);

    // Extract HTML from first text-editor widget
    $html = '';
    foreach ($data as $container) {
        foreach ($container['elements'] ?? [] as $widget) {
            if (($widget['widgetType'] ?? '') === 'text-editor') {
                $html = bc_clean($widget['settings']['editor'] ?? '');
                break 2;
            }
        }
    }

    if (empty($html)) {
        echo "WARNING: no text-editor content found in page $page_id, skipping." . PHP_EOL;
        continue;
    }

    // ── 2. Create new post with that content ──────────────────────────────────
    $post_id = wp_insert_post([
        'post_title'  => $cfg['title'],
        'post_name'   => $cfg['post_slug'],
        'post_status' => 'publish',
        'post_type'   => 'post',
        'post_content' => '',
    ]);

    if (is_wp_error($post_id)) {
        echo "ERROR creating post for '{$cfg['title']}': " . $post_id->get_error_message() . PHP_EOL;
        continue;
    }

    wp_set_post_categories($post_id, [1]);

    // Write Elementor data to the new post
    $el_data = [[
        'id'       => bc_uid(),
        'elType'   => 'container',
        'settings' => ['_title' => $cfg['title']],
        'elements' => [[
            'id'         => bc_uid(),
            'elType'     => 'widget',
            'widgetType' => 'text-editor',
            'settings'   => ['editor' => $html],
            'elements'   => [],
        ]],
    ]];

    $json = json_encode($el_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $wpdb->insert($wpdb->postmeta, ['post_id' => $post_id, 'meta_key' => '_elementor_data', 'meta_value' => $json]);
    update_post_meta($post_id, '_elementor_edit_mode',    'builder');
    update_post_meta($post_id, '_elementor_template_type', 'wp-post');
    update_post_meta($post_id, '_elementor_version',       '3.0.0');
    delete_post_meta($post_id, '_elementor_element_cache');

    // Set post timestamps
    bc_set_dates($post_id, $cfg['post_created'], $cfg['post_modified']);

    echo "Created post ID:$post_id '{$cfg['title']}' from page $page_id" . PHP_EOL;

    // ── 3. Update page to use [bc_section id="post_id"] ──────────────────────
    $shortcode = '[bc_section id="' . $post_id . '"]';
    $page_el = [[
        'id'       => bc_uid(),
        'elType'   => 'container',
        'settings' => ['_title' => $cfg['title']],
        'elements' => [[
            'id'         => bc_uid(),
            'elType'     => 'widget',
            'widgetType' => 'shortcode',
            'settings'   => ['shortcode' => $shortcode],
            'elements'   => [],
        ]],
    ]];

    $page_json = json_encode($page_el, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $wpdb->update($wpdb->postmeta, ['meta_value' => $page_json], ['post_id' => $page_id, 'meta_key' => '_elementor_data']);
    delete_post_meta($page_id, '_elementor_element_cache');
    delete_post_meta($page_id, '_elementor_page_assets');

    // Update page modified date (it was "re-saved" when the shortcode was added)
    bc_set_dates($page_id, '2026-03-07 08:23:41', $cfg['page_modified']);

    wp_cache_delete($page_id, 'post_meta');
    wp_cache_delete($post_id, 'post_meta');

    echo "  Page $page_id updated → $shortcode (modified: {$cfg['page_modified']})" . PHP_EOL;
}

// ── 4. Clear caches ────────────────────────────────────────────────────────────
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient%elementor%'");
if (class_exists('\Elementor\Plugin') && isset(\Elementor\Plugin::instance()->files_manager)) {
    \Elementor\Plugin::instance()->files_manager->clear_cache();
    echo "Elementor cache cleared." . PHP_EOL;
}

// ── 5. Summary ────────────────────────────────────────────────────────────────
echo PHP_EOL . "=== Posts list now includes ===" . PHP_EOL;
$posts = $wpdb->get_results("SELECT ID, post_title, post_date FROM {$wpdb->posts} WHERE post_status='publish' AND post_type='post' ORDER BY post_date");
foreach ($posts as $p) {
    echo "  ID:{$p->ID} | {$p->post_date} | {$p->post_title}" . PHP_EOL;
}
