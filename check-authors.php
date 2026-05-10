<?php
chdir(__DIR__);
require __DIR__ . '/wp-load.php';
global $wpdb;

$rows = $wpdb->get_results("SELECT ID, post_title, post_author FROM {$wpdb->posts} WHERE post_status='publish' AND post_type IN ('post','page') ORDER BY ID");
foreach ($rows as $r) {
    $u = get_userdata($r->post_author);
    echo "ID:{$r->ID} | author_id:{$r->post_author} (" . ($u ? $u->user_login : 'unknown') . ") | {$r->post_title}" . PHP_EOL;
}
