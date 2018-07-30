<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->formsDir,
        $config->application->libraryDir
    ]);


$loader->registerNamespaces(
    [
        'App\Models'      => $config->application->modelsDir,
        'App\Controllers' => $config->application->controllersDir,
        'App\Forms'       => $config->application->formsDir,
        'App\Library'     => $config->application->libraryDir
    ]);

$loader->register();