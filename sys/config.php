<?php
/**
 * @author Vitaly
 * @Date: 24.04.12
 */

/*
 * Config-файл с настройками путей и параметрами DB
 */

    // Пути

    define('ROOT_PATH','D:/xampp/htdocs/online/www/');//путь к корню
    define('CLASS_PATH', ROOT_PATH . 'class/');
    define('CONTROLLER_PATH', ROOT_PATH . 'controller/');
    define('VIEW_PATH', ROOT_PATH . 'view/');
    define('TEMPLATES_PATH', VIEW_PATH);
    define('INDEX_URL', 'http://online.loc');
    define('AVATARS_URL', INDEX_URL . "/resources/avatars/");
    define('AVATARS_PATH', ROOT_PATH . "resources/avatars/");

    // СУБД

    define('DB_NAME','online');
    define('DB_USER','SimpleBlog');
    define('DB_PASSWORD','090192');
    define('DB_SERVER','localhost');