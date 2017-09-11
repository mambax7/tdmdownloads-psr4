<?php

/**
 * @see http://www.php-fig.org/psr/psr-4/examples/
 */
spl_autoload_register(function ($class) {
    $prefix   = 'Xoopsmodules\\tdmdownloads\\';
    $base_dir = __DIR__ . '/class/';
    $len      = strlen($prefix);

    if (0 !== strncmp($prefix, $class, $len)) {
        return;
    }

    $relative_class = substr($class, $len);
    $file           = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
