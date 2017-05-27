<?php

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('Neutron\TemporaryFilesystem\Tests', __DIR__ . '/../tests');

define('DEV_VAR_DIR', realpath(__DIR__ . '/../var'));
