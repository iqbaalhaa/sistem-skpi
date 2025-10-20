<?php
require __DIR__ . '/vendor/autoload.php';
$found = false;
foreach (get_declared_classes() as $c) {
    if (stripos($c, 'barryvdh') !== false || stripos($c, 'dompdf') !== false) {
        echo $c . PHP_EOL;
        $found = true;
    }
}
if (! $found) echo "(none)\n";
