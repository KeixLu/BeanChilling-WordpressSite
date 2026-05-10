<?php
// TODO: delete this file — one-time diagnostic helper
die('Please delete this file.');
chdir(__DIR__);
require __DIR__ . '/wp-load.php';
global $wpdb;

// Check current state of nav pages and any existing section posts for them
foreach ([6 => 'Team Zenith', 7 => 'IMRAD', 8 => 'App Showcase'] as $id => $title) {
    $raw  = $wpdb->get_var("SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id=$id AND meta_key='_elementor_data' LIMIT 1");
    $data = json_decode($raw, true);
    $p    = get_post($id);
    echo "ID:$id | type:{$p->post_type} | title:$title" . PHP_EOL;

    // Check if first widget is a shortcode (already delegated) or text-editor (raw content)
    $widget = $data[0]['elements'][0] ?? null;
    if ($widget) {
        $wtype = $widget['widgetType'] ?? '?';
        if ($wtype === 'shortcode') {
            echo "  → already shortcode: " . ($widget['settings']['shortcode'] ?? '') . PHP_EOL;
        } elseif ($wtype === 'text-editor') {
            $preview = substr(strip_tags($widget['settings']['editor'] ?? ''), 0, 100);
            echo "  → text-editor: $preview" . PHP_EOL;
        }
    }
}
