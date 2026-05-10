<?php
// safety guard
if ( php_sapi_name() !== 'cli' ) die('CLI only');

$pdo = new PDO('mysql:host=localhost;dbname=beanchilling_wp;charset=utf8mb4', 'root', '');
$rows = $pdo->query("SELECT option_name, option_value FROM wp_options WHERE option_name IN ('siteurl','home') ORDER BY option_name")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $r) {
    echo $r['option_name'] . ' => ' . $r['option_value'] . PHP_EOL;
}

// Also scan post content and post meta for any localhost URLs in image src
$img = $pdo->query("SELECT ID, post_title, post_content FROM wp_posts WHERE post_status='publish' AND post_content LIKE '%localhost%'")->fetchAll(PDO::FETCH_ASSOC);
echo PHP_EOL . "Posts with 'localhost' in content: " . count($img) . PHP_EOL;
foreach ($img as $p) {
    echo "  ID:{$p['ID']} — {$p['post_title']}" . PHP_EOL;
}

$meta = $pdo->query("SELECT post_id, meta_key FROM wp_postmeta WHERE meta_value LIKE '%localhost%' AND meta_key IN ('_elementor_data','_elementor_page_settings')")->fetchAll(PDO::FETCH_ASSOC);
echo PHP_EOL . "Post meta with 'localhost' in Elementor data: " . count($meta) . PHP_EOL;
foreach ($meta as $m) {
    echo "  post_id:{$m['post_id']} meta_key:{$m['meta_key']}" . PHP_EOL;
}
