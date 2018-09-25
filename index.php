<?php

use App\Parser;

$start = microtime(true);

require __DIR__.'/vendor/autoload.php';

$settings = include 'config.php';

$parser = new Parser(__DIR__, $settings);
$parser->cleanFile();
$parser->startParser();

echo 'Total execution time: '.round(microtime(true) - $start, 4).' sec.';
