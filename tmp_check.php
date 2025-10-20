<?php
require __DIR__ . '/vendor/autoload.php';
echo class_exists('Barryvdh\\DomPDF\\Facade\\Pdf') ? 'FOUND' : 'MISSING';
