<?php
/**
 * @author Vitaly
 * @Date: 24.04.12
 */

/*
 * Класс - родитель всех контроллеров
 * содержит всё то что должно обязательно быть в каждом классе-контроллере
 */

class ActionController
{
    protected $view;
    protected $method;
    protected $args;
    protected $session;

    function __construct(View $view, $method, $args)
    {
        $this->view = $view;
        $this->method = (($method != "before") && ($method != "after")) ? $method : null;//необходимо для запрета доступа к before и after
        $this->args = $args;
    }

    function setView($view)
    {
        $this->view = $view;
        return $this;
    }

    function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    function setArgs($args)
    {
        $this->args = $args;
        return $this;
    }

    function getView()
    {
        return $this->view;
    }

    function getMethod()
    {
        if(!empty($this->method))
            return $this->method;
        else return false;
    }

    function getArgs()
    {
        return $this->args;
    }
}