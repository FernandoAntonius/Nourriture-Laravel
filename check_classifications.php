#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Foundation\Console\Kernel');

$classifications = \App\Models\AgeClassification::all();
echo "Age Classifications:\n";
echo "==================\n";
foreach ($classifications as $c) {
    echo "{$c->id}. {$c->name} ({$c->min_age}-{$c->max_age} tahun)\n";
}
echo "\nTotal: " . count($classifications) . " classifications\n";
