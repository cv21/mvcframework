<?php
/*********************
 * User: Vitaly
 * Date: 22.05.12
 *********************/
class ViewUserActionController extends ActionController
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
        else
        {
            header('Location: /user/login');
        }
    }

    function action()
    {
        if(!empty($this->args['id']))
        {
            $messageMapper = new MessageMapper();
            $userMapper = new UserMapper();
            $this->view->user = $this->user;
            $this->view->viewUser = $userMapper->find((int)$this->args['id']);
            $this->view->messages = $messageMapper->getDialog((int)$this->args['id'], $_SESSION['userid'], 5);
            $this->view->setStructure("viewuser");
            $this->view->setTitle($this->view->viewUser->getFirstname() . " " . $this->view->viewUser->getLastname());
            $this->view->draw();
        }
        else
        {
            header('Location : /user');
        }
    }
}
