<?php
if (php_sapi_name() !== 'cli') die('CLI only');
$pdo = new PDO('mysql:host=localhost;dbname=beanchilling_wp;charset=utf8mb4', 'root', '');

// post 107 Elementor data - look for any image path
$meta = $pdo->query("SELECT meta_value FROM wp_postmeta WHERE post_id=107 AND meta_key='_elementor_data'")->fetch(PDO::FETCH_ASSOC);
if ($meta) {
    // Elementor stores HTML inside text widgets as JSON-encoded string
    $decoded = json_decode($meta['meta_value'], true);
    $json_str = json_encode($decoded); // normalise encoding
    // Find all image src patterns
    preg_match_all('/themes[\/\\\\][^"\\\\]+\.png/', $json_str, $m);
    echo "Theme image refs in post 107 _elementor_data:\n";
    foreach (array_unique($m[0]) as $u) echo "  $u\n";

    // Also look for raw img src
    preg_match_all('/src=\\\\?"([^"\\\\]+)\\\\?"/', $json_str, $m2);
    echo "\nAll src= values:\n";
    foreach (array_unique($m2[1]) as $u) echo "  $u\n";
} else {
    echo "No _elementor_data for post 107\n";
}
