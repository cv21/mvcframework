<?php
/**
 * @author Vitaly
 * Date: 27.04.12
 */

/*
 * Служит прослойкой между моделью сообщения и работой с DbService
 */

class MessageMapper
{
    private $connection; // хранит ссылку на singleton экземпляр DbServide

    function __construct()
    {
        $this->connection = DbService::instance();
    }

    // выполняет добавление сообщения из модели в БД
    // возвращает id сообщения

    function send(MessageModel $message)
    {
        $messagedata = array(
            'from' => $message->getFrom(),
            'to'   => $message->getTo(),
            'text'   => $message->getText()
        );
        $message->setId($this->connection->insert('messages', $messagedata));
        return $message->getId();
    }

    // возвращает массив сообщений пользователей $sender и $reciever

    function getDialog($sender, $reciever, $leftLimit = null, $rightLimit = null)
    {
        $dialogMessages = $this->connection->fetchAssoc($this->connection->select("messages", "`from`=" . $sender . " AND `to`=" . $reciever . " OR `from`=" . $reciever . " AND `to`=" . $sender, true, $leftLimit, $rightLimit));
        $this->connection->update("messages", "`readed`=1", "`from`=" . $sender . " AND `to`=" . $reciever . " AND `readed`=0");
        return $dialogMessages;
    }

    // возврашает массив всех новых сообщений, отправленных юзеру с id $userid

    function getNewMessages($userid)
    {
        $newMessages = $this->connection->fetchAssoc($this->connection->select("messages", "`to`=" . $userid . " AND `readed`=0", true));
        $this->connection->update("messages", "`readed`=1", "`to`=" . $userid . " AND `readed`=0");
        return $newMessages;
    }

    // выполняет поиск сообщения по id
    // возвращает модель сообщения или false

    function find($id)
    {
        $result = $this->connection->fetchAssocOnce($this->connection->select('messages', 'id=' . $id));
        if(!empty($result))
        {
            $message = new MessageModel();

            $message->setId($result['id'])
                    ->setFrom($result['from'])
                    ->setTo($result['to'])
                    ->setText($result['text'])
                    ->setTime($result['time']);

            return $message;
        }
        else
            return false;
    }

    // выполняет удаление сообщения по id $id

    function delete($id)
    {
        $this->connection->delete("messages","`id`='" . $id . "'");
    }

    // проверяет доступ юзера к действиям над сообщением
    // сверяет id автора и принимающего сообщения и id текущего (авторизованного) пользователя

    function checkAccess($msgid, $userid)//принимает id автора сообщения
    {
        $message = $this->find($msgid);
        return (($userid == $message->getFrom()) || ($userid == $message->getTo()));
    }

    // Усанавливает данные авторов сообщений в ключ ['from'] массива данных сообщения

    function setUsersForMessagesFrom($messages)
    {
        $userMapper = new UserMapper();
        $newMessages = $messages;
        foreach($messages as $key=>$message)
        {
            $newMessages[$key]['from'] = $userMapper->findArray($message['from']);
        }
        return $newMessages;
    }
}