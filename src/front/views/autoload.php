<?php

/**
 * include file containing autoloaders
 */

// require_once '/var/www/vendor/autoload.php';

spl_autoload_register(
    function ($className) {
        $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
        // only try autoload if it's one of our classes
        if (str_contains($className, "mhl")) {
            include_once '/var/www/html/classes/' . $className . '.php';
        }
    }
);
