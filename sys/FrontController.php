<?php
/**
 * @author Vitaly
 * @Date: 24.04.12
 */

/*
 * Единая точка входа
 * singleton
 * Разбирает строку запроса и передает действие нужному контроллеру
 * Обрабатывает аргументы $_REQUEST
 */

class FrontController
{
    private $controller;
    private $method;
    private $args;
    private static $fcUnit; // singleton
    private $view;

    private function __construct()
    {
        $urlInfo = parse_url(strtolower($_SERVER["REQUEST_URI"]));
        $urlPath = explode('/', trim($urlInfo['path'], '/'));
        $this->controller = $urlPath[0];
        $this->method = (!empty($urlPath[1])) ? $urlPath[1] : null;
        $this->args = CheckService::checkArgs($_REQUEST);
        $this->view = new View();
    }

    // получает/создает singleton

    static function instance()
    {
        if (FrontController::$fcUnit == null)
        {
            FrontController::$fcUnit = new FrontController();
        }
        return FrontController::$fcUnit;
    }

    // обрабатывает строку url-запроса и передает действие определенному контроллеру

    function start()
    {
        if(empty($this->controller))
        {
            $actionController = ActionControllerFabric::getController('index', $this->view, null, $this->args);
        }
        else
        {
            switch($this->controller)
            {
                case 'ajax':
                    {
                        $actionController = ActionControllerFabric::getController('ajax', $this->view, $this->method, $this->args);
                        break;
                    }
                case 'admin':
                    {
                        $actionController = ActionControllerFabric::getController('admin', $this->view, $this->method, $this->args);
                        break;
                    }
                case 'user':
                    {
                        $actionController = ActionControllerFabric::getController('user', $this->view, $this->method, $this->args);
                        break;
                    }
                case 'viewuser':
                    {
                        $actionController = ActionControllerFabric::getController('viewuser', $this->view, $this->method, $this->args);
                        break;
                    }
                case 'message':
                    {
                        $actionController = ActionControllerFabric::getController('message', $this->view, $this->method, $this->args);
                        break;
                    }
                default:
                    {
                        $actionController = ActionControllerFabric::getController('notFound', $this->view);
                    }
            }
        }


        // обработка существующих/отсутствующих методов "before" и позже "after"
        // вызывается "before" (если существует)

        if(method_exists($actionController, "before"))
        {
            $actionController->before();
        }

        // попытка вызова метода, указанного в url или метода "action"

        if($methodname = $actionController->getMethod())
        {
            if(method_exists($actionController,$methodname))
            {
                $actionController->$methodname();
            }
            else
            {
                $actionController = ActionControllerFabric::getController('notFound', $this->view, $this->method, $this->args);
                $actionController->action();
            }
        }
        else
            $actionController->action();

        // вызывается "after" (если существует)

        if(method_exists($actionController, "after"))
        {
            $actionController->after();
        }

        // выводим все нужные данные работы скрипта

        if(DebugService::isEnabled())
        {
            echo "Time: " . round(DebugService::instance()->getTime(),4) . " msec <br>";
            echo "Memory: " . DebugService::instance()->getMemoryUsage() . " bytes <br>";
            echo "DbQueries: " . count(DebugService::instance()->getQueries()) . " query";
            var_dump(DebugService::instance()->getQueries());
        }
    }
}
