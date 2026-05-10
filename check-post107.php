<?php
if (php_sapi_name() !== 'cli') die('CLI only');
$pdo = new PDO('mysql:host=localhost;dbname=beanchilling_wp;charset=utf8mb4', 'root', '');
$row = $pdo->query("SELECT post_content FROM wp_posts WHERE ID=107")->fetch(PDO::FETCH_ASSOC);
echo "RAW post_content:\n";
echo $row['post_content'] . "\n\n";

// Also check _elementor_data for any image URLs
$meta = $pdo->query("SELECT meta_value FROM wp_postmeta WHERE post_id=107 AND meta_key='_elementor_data'")->fetch(PDO::FETCH_ASSOC);
if ($meta) {
    preg_match_all('/"url"\s*:\s*"([^"]+)"/', $meta['meta_value'], $m);
    echo "Elementor data URLs:\n";
    foreach (array_unique($m[1]) as $u) echo "  $u\n";
}
