<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->operationsDir,
        $config->application->libraryDir,
        $config->application->pluginsDir,
    ]);


$loader->registerNamespaces(
    [
        'Circuitos\Models'                  => $config->application->modelsDir,
        'Circuitos\Models\Operations'       => $config->application->operationsDir,
        'Circuitos\Controllers'             => $config->application->controllersDir,
        'Circuitos\Library'                 => $config->application->libraryDir,
        'Auth'                              => $config->application->libraryDir . 'Auth/Autentica.php',
        'Util'                              => $config->application->libraryDir . 'Util/Util.php',
        'TemplatesEmails'                   => $config->application->libraryDir . 'Util/TemplatesEmails.php',
        'TokenManager'                      => $config->application->libraryDir . 'Util/TokenManager.php',
        'Infra'                             => $config->application->libraryDir . 'Util/Infra.php',
        'Uploader'                          => $config->application->libraryDir . 'Uploader/Uploader.php'
    ]);
    
$loader->register();