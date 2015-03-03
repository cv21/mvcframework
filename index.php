<?php
/**
 * @author Vitaly
 * @Date: 24.04.12
 */

    // загрузка конфига

    require 'sys/config.php';

    // автозагрузка классов

    function __autoload($className)
    {
        @include(ROOT_PATH . 'controller/' . $className . '.php');
        @include(ROOT_PATH . 'service/' . $className . '.php');
        @include(ROOT_PATH . 'model/' . $className . '.php');
        @include(ROOT_PATH . 'sys/' . $className . '.php');
    }

    // DSE - DebugServiceEnabled
    // Для включения режима Debug необходимо установить Cookie DSE=255

    if(!empty($_COOKIE['DSE']) && ($_COOKIE['DSE'] == 255))
    {
        DebugService::instance();
    }

    $fc = FrontController::instance();
    $fc->start();