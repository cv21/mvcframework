<?php
/**
 * @author Vitaly
 * @Date: 24.04.12
 */

/*
 * Контроллер главной страницы
 * показывает сообщения (возможна филтрация по тегу или автору)
 * выдает в свою view массив со всеми необходимыми для отображения данными
 */

class IndexActionController extends ActionController
{
    // выполняется при каждом обращении к контроллеру
    // логинит юзера

    function before()
    {
        if(!empty($_SESSION['userid']))
        {
            $userMapper = new UserMapper();
            $this->user = $userMapper->find($_SESSION['userid']);
        }
    }

    function action()
    {
        $userMapper = new UserMapper();
        if(!empty($this->user))
        {
            $this->view->user = $this->user;
        }
        $this->view->randomUsers = $userMapper->getRandomUsers(((!empty($this->user)) ? $this->user->getId() : 0), 25);
        $this->view->setTitle('PhotoWall');
        $this->view->setStructure('index');
        $this->view->draw();
    }
}