<?php
if (php_sapi_name() !== 'cli') die('CLI only');
$pdo = new PDO('mysql:host=localhost;dbname=beanchilling_wp;charset=utf8mb4', 'root', '');

// Check post 107 meta
$rows = $pdo->query("SELECT meta_key, LENGTH(meta_value) as len FROM wp_postmeta WHERE post_id=107 ORDER BY meta_key")->fetchAll(PDO::FETCH_ASSOC);
echo "Post 107 meta keys:\n";
foreach ($rows as $r) echo "  {$r['meta_key']} (len={$r['len']})\n";

echo "\n";

// Raw string search for image filename in _elementor_data
$meta = $pdo->query("SELECT meta_value FROM wp_postmeta WHERE post_id=107 AND meta_key='_elementor_data'")->fetch(PDO::FETCH_ASSOC);
if ($meta) {
    $raw = $meta['meta_value'];
    // Search for the image file name
    $pos = strpos($raw, 'coffee_farm');
    if ($pos !== false) {
        echo "Found 'coffee_farm' at position $pos:\n";
        echo substr($raw, max(0, $pos - 100), 300) . "\n";
    } else {
        echo "No 'coffee_farm' found in _elementor_data\n";
    }

    // also search for 'themes'
    $pos2 = strpos($raw, 'themes');
    if ($pos2 !== false) {
        echo "\nFound 'themes' at position $pos2:\n";
        echo substr($raw, max(0, $pos2 - 50), 200) . "\n";
    }
}
