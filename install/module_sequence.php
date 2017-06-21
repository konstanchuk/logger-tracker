#!/usr/bin/env php
<?php

/**
 * Logger Tracker Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

if (PHP_SAPI !== 'cli') {
    echo 'module_sequence.php must be run as a CLI application' . PHP_EOL;
    exit(1);
}

$bootstrapFile = DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'bootstrap.php';
$configFile = DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'config.php';
$diFile = DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'di.xml';
$envFile = DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'etc' . DIRECTORY_SEPARATOR . 'env.php';

$currentDirectory = __DIR__;
while (true) { //find magento root directory
    $fullBootstrapFile = $currentDirectory . $bootstrapFile;
    $fullConfigFile = $currentDirectory . $configFile;
    $fullDiFile = $currentDirectory . $diFile;
    $fullEnvFile = $currentDirectory . $envFile;
    if (file_exists($fullBootstrapFile)
        && file_exists($fullConfigFile)
        && file_exists($fullDiFile)
        && file_exists($fullEnvFile)
    ) {
        break;
    }
    $currentDirectory = dirname($currentDirectory);
    if ($currentDirectory == '.') {
        echo 'Magento root directory was not found' . PHP_EOL;
        exit(1);
    }
}
$rootDir = $currentDirectory;

require $rootDir . $bootstrapFile;
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
$om = $bootstrap->getObjectManager();

/** @var \Magento\Framework\Module\ModuleList $moduleList */
$moduleList = $om->create('Magento\Framework\Module\ModuleList');
$modules = $moduleList->getNames();
$nodes = [];
foreach ($modules as $module) {
    if ($module != 'Konstanchuk_LoggerTracker') { //ignore current module
        $nodes[] = '<module name="' . $module . '"/>';
    }
}

$content = <<<TEXT
<!-- Insert this list into the file [Konstanchuk_LoggerTracker]/etc/module.xml in the <sequence>. -->
TEXT;
$content .= "\n" . implode("\n", $nodes);
$outputFile = __DIR__ . '/output.txt';
file_put_contents($outputFile, $content);
echo 'Module list was generated.' . PHP_EOL;
echo 'See ' . $outputFile . PHP_EOL;
