<?php
/**
 * @author Vitaly
 * Date: 03.05.12
 */

/*
 * Админ-контроллер
 * Обрабатывает все действия в режиме администрирования
 */

class AdminActionController extends ActionController
{
    // выполняется при каждом обращении к контроллеру
    // логинит юзера и добавляет во view его модель (удобно для отрисовки страницы)

    function before()
    {
        if(!empty($_SESSION['userid']))
        {
            $userMapper = new UserMapper();
            $this->user = $userMapper->find($_SESSION['userid']);
        }
        else
        {
            header('Location: /user/login');
        }
    }

    // выполняется при обращении к url "/admin"
    // различает кто (простой юзер или админ) залогинен
    // и далее обрабатывает все действия админа

    function action()
    {
        $userMapper = new UserMapper();
        $adminMapper = new AdminMapper();
        if($this->user->getStatus() == 1)
        {
            if(!empty($this->args['delete']))
            {
                $userMapper->delete($this->args['delete']);
            }
            $this->view->setStructure('admin', 'admin_header')
                 ->setTitle("Admin panel")
                 ->users = $adminMapper->selectAll();
        }
        else
        {
            $this->view->setStructure('dummy')
                 ->setTitle("Restricted area")
                 ->text = "You don't have permissions for access";
        }
        $this->view->draw();
    }
}
