<?php
require __DIR__ . '/vendor/autoload.php';
echo "Barryvdh\\DomPDF\\Facade\\Pdf exists: ";
var_export(class_exists('Barryvdh\\DomPDF\\Facade\\Pdf'));
echo PHP_EOL;
echo "Barryvdh\\DomPDF\\Facade exists: ";
var_export(class_exists('Barryvdh\\DomPDF\\Facade'));
echo PHP_EOL;

// List files under vendor/barryvdh/laravel-dompdf/src
$dir = __DIR__ . '/vendor/barryvdh/laravel-dompdf/src';
if (is_dir($dir)) {
    echo "Files in $dir:\n";
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($files as $file) {
        if ($file->isFile()) {
            echo str_replace(__DIR__.'\\','', $file->getPathname()) . PHP_EOL;
        }
    }
} else {
    echo "Dir not found: $dir\n";
}
