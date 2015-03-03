<?php
/**
 * @author Vitaly
 * @Date: 24.04.12
 */

/*
 * Класс - фабрика контроллеров
 */

class ActionControllerFabric
{
    static function getController($name, View $view, $method = null, $args = null)
    {
        $name = $name . 'ActionController';
        $actionController = new $name($view, $method, $args);
        return $actionController;
    }
}
