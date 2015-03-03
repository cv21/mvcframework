<?php
/**
 * @author Vitaly
 * Date: 09.05.12
 */

/*
 * Обрабатывает ajax-запросы
 */

class AjaxActionController extends ActionController
{

    // выполняется при каждом обращении к контроллеру
    // логинит юзера или просто не выполняется дальше

    function before()
    {
        if(!empty($_SESSION['userid']))
        {
            $userMapper = new UserMapper();
            $this->user = $userMapper->find($_SESSION['userid']);
        }
        else
        {
            exit;
        }
    }

    // завершает работу скрипта, если не указан метод

    function action()
    {
        exit;
    }

    // выдает результаты поиска (по ajax)
    // выполняется только если юзер залогинен

    function getSearchResults()
    {
        if(((!empty($this->args['name']) || !empty($this->args['gender']) || !empty($this->args['age']))) && isSet($this->args['ll']) && isSet($this->args['rl']))
        {
            $userMapper = new UserMapper();
            $found = $userMapper->searchUsers($this->args['name'], $this->args['gender'], (int)$this->args['age'], (int)$this->user->getId(), (int)($this->args['ll']), (int)($this->args['rl']));
            if(!empty($found))
            {
                $this->view->found = $found;
            }
            else
            {
                exit;
            }
            $this->view->setStructure('ajaxSearchResults','empty', 'empty');
            $this->view->draw();
        }
    }

    // выполняет ajax-удаление сообщения
    // выполняется только если юзер  залогинен

    function deleteMessage()
    {
        $msgMapper = new MessageMapper();
        if(!empty($this->args['id']) && $msgMapper->checkAccess((int)$this->args['id'], $this->user->getId()))
        {
            $msgMapper->delete($this->args['id']);
            echo "OK";
        }
        else
        {
            echo "ERROR";
        }
    }

    // выполняет ajax-загрузку сообщений между двумя пользователями (диалог)
    // выполняется только если юзер залогинен

    function getMessages()
    {
        if(!empty($this->args['viewuser']) && isSet($this->args['ll']) && isSet($this->args['rl']))
        {
            $messageMapper = new MessageMapper();
            $userMapper = new UserMapper();
            $messages = $messageMapper->getDialog((int)$this->args['viewuser'], $this->user->getId(), (int)$this->args['ll'], (int)$this->args['rl']);
            if(!empty($messages))
            {
                $this->view->messages = $messages;
            }
            else
            {
                exit;
            }
            $this->view->user = $this->user;
            $this->view->viewUser = $userMapper->find((int)$this->args['viewuser']);
            $this->view->setStructure('ajaxGetMessages', 'empty', 'empty');
            $this->view->draw();
        }
        else
        {
            echo "ERROR";
        }
    }
}