<?php
if (php_sapi_name() !== 'cli') die('CLI only');

$pdo = new PDO('mysql:host=localhost;dbname=beanchilling_wp;charset=utf8mb4', 'root', '');
$ids = [6, 7, 8, 13, 107, 108, 109, 110, 137, 138, 139];

foreach ($ids as $id) {
    $row = $pdo->query("SELECT meta_value FROM wp_postmeta WHERE post_id=$id AND meta_key='_elementor_data'")->fetch(PDO::FETCH_ASSOC);
    if (!$row || !$row['meta_value']) { echo "ID:$id — no elementor data\n"; continue; }
    preg_match_all('/uploads\/[^\\\\"\'\\s]+/', $row['meta_value'], $m);
    if (!empty($m[0])) {
        echo "ID:$id has uploads refs:\n";
        foreach (array_unique($m[0]) as $ref) echo "  $ref\n";
    } else {
        echo "ID:$id — no uploads image refs\n";
    }
}

// Also check post content (for shortcode pages)
echo "\n--- post_content checks ---\n";
foreach ($ids as $id) {
    $row = $pdo->query("SELECT post_title, post_content FROM wp_posts WHERE ID=$id")->fetch(PDO::FETCH_ASSOC);
    if (!$row) continue;
    preg_match_all('/src=["\']([^"\']+)["\']/', $row['post_content'], $m);
    if (!empty($m[1])) {
        echo "ID:$id ({$row['post_title']}) img srcs:\n";
        foreach (array_unique($m[1]) as $src) echo "  $src\n";
    }
}
