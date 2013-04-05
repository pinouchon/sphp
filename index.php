<?php

require_once 'Slim/View.php';
require_once 'Slim/Slim.php';
require_once 'Slim/MyView.class.php';
require_once 'Granada/eager.php';
require_once 'Granada/idiorm.php';
require_once 'Granada/granada.php';
require_once 'model/MyModel.class.php';


define('_PATH_', $_SERVER['DOCUMENT_ROOT'].'/');

// General class include
foreach (glob(_PATH_ . "classes/*.class.php") as $filename) {
    include_once $filename;
}
foreach (glob(_PATH_ . "model/*.class.php") as $filename) {
    include_once $filename;
}

App::getInstance()->Run();
