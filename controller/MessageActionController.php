<?php
/**
 * @author Vitaly
 * Date: 27.04.12
 */

/*
 * Обрабатывает действия пользователя с сообщениями
 */

class MessageActionController extends ActionController
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
            exit;
        }
    }

    function action()
    {
        header('Location: /');
    }

    // добавляет сообщение с текстом "text"

    function send()
    {
        if((!empty($this->args['text'])))
        {
            $msgMapper = new MessageMapper();
            $message = new MessageModel();
            $message->setFrom($this->user->getId())
                    ->setTo((int)$this->args['to'])
                    ->setText($this->args['text'])
                    ->setId($msgMapper->send($message));

        }
        header('Location: /viewuser?id=' . (int)$this->args['to']);
    }
}