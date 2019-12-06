<?php

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('Neutron\TemporaryFilesystem\Tests', __DIR__ . '/../tests');

if (PHP_VERSION_ID >= 70000 && !class_exists('PHPUnit_Framework_TestCase')) {
    class_alias('PHPUnit\Framework\TestCase', 'PHPUnit_Framework_TestCase');
}
