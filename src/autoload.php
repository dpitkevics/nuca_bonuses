<?php

define ('_ROOT_', __DIR__);
define ('_VENDOR_', _ROOT_ . '/../vendor');

 function autoloader($className)
 {
     $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);

     $classPath = __DIR__;
     $filePath = $classPath . DIRECTORY_SEPARATOR . $className . ".php";
     if (file_exists($filePath)) {
         include_once $filePath;
     }
 }

spl_autoload_register('autoloader');

require_once __DIR__ . '/../vendor/autoload.php';