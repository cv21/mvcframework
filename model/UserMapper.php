<?php
/**
 * @author Vitaly
 * Date: 25.04.12
 */

/*
 * Служит прослойкой между моделью юзера и работой с DbService
 */

class UserMapper
{
    private $connection; // хранит ссылку на singleton экземпляр DbService

    function __construct()
    {
        $this->connection = DbService::instance();
    }

    // выполняет добавление юзера из модели в БД
    // возвращает id юзера

    function add(UserModel $user)
    {
        $userdata = array(
            'firstname'  => $user->getFirstname(),
            'lastname'   => $user->getLastname(),
            'gender'     => $user->getGender(),
            'birthDay'   => $user->getBirthDay(),
            'birthMonth' => $user->getBirthMonth(),
            'birthYear'  => $user->getBirthYear(),
            'email'      => $user->getEmail(),
            'password'   => $user->getPassword()
        );
        $user->setId($this->connection->insert('users', $userdata));
        return $user->getId();
    }

    // находит юзера и возвращает его данные в виде массива

    function findArray($id)
    {
        return $this->connection->fetchAssocOnce($this->connection->select('users', 'id=' . $id));
    }

    // обновляет юзера в бд, отталкиваясь от модели $user

    function update(UserModel $user)
    {
        $this->connection->update("users",
                                  "`firstname`='"  . $user->getFirstname()  . "', " .
                                  "`lastname`='"   . $user->getLastname()   . "', " .
                                  "`gender`='"     . $user->getGender()     . "', " .
                                  "`birthday`='"   . $user->getBirthDay()   . "', " .
                                  "`birthmonth`='" . $user->getBirthMonth() . "', " .
                                  "`birthyear`='"  . $user->getBirthYear()  . "', " .
                                  "`avatar`='"     . $user->getAvatar()     . "', " .
                                  "`password`='"   . $user->getPassword()   . "' " ,
                                  "`id`='" . $user->getId() . "'"
        );
    }

    // выполняет поиск юзера по id
    // возвращает модель юзера или false

    function find($id)
    {
        $result = $this->connection->fetchAssocOnce($this->connection->select('users', 'id=' . $id));
        if(!empty($result))
        {
            $newUserModel = new UserModel($result['id'],
                                          $result['firstname'],
                                          $result['lastname'],
                                          $result['gender'],
                                          $result['birthday'],
                                          $result['birthmonth'],
                                          $result['birthyear'],
                                          $result['avatar'],
                                          $result['email'],
                                          $result['password'],
                                          $result['status']
            );
            return $newUserModel;
        }
        else
            return false;
    }

    // выполняет "расширенный" поиск
    // параметром $whereExpression используется как "WHERE $whereExpression"
    // возвращает модель юзера или false

    function extFind($whereExpression)
    {
        $result = $this->connection->fetchAssocOnce($this->connection->select('users', $whereExpression));
        if(!empty($result))
        {
            $newUserModel = new UserModel($result['id'],
                                          $result['firstname'],
                                          $result['lastname'],
                                          $result['gender'],
                                          $result['birthday'],
                                          $result['birthmonth'],
                                          $result['birthyear'],
                                          $result['avatar'],
                                          $result['email'],
                                          $result['password'],
                                          $result['status']
            );
            return $newUserModel;
        }
        else return false;
    }

    // выполняет удаление юзера из БД по id $id

    function delete($id)
    {
        $this->connection->delete("users", "`id`="  . $id);
    }

    // проверяет существование в таблице юзеров строк с почтой $email или ником $nick
    // возвращает количество таких строк

    function checkExist($email)
    {
        return mysql_num_rows($this->connection->select("users", "`email`='" . $email . "'"));
    }

    // выполняет выборку случайных пользователей
    // исключая пользователя, который сейчас залогинен

    function getRandomUsers($curUser = 0, $limit)
    {
        return $this->connection->fetchAssoc($this->connection->extSelect("*", "users", " WHERE `id`<>" . $curUser . " ORDER BY rand() LIMIT " . $limit));
    }

    // выполняет поиск юзеров по имени, полу и возрасту, не включая в результат юзера с id $curUser

    function searchUsers($name, $gender, $age, $curUser, $leftLimit = null, $rightLimit = null)
    {
        return $this->connection->fetchAssoc($this->connection->extSelect("*", "users", "WHERE " .
                                                                         ((!empty($name)) ? ("(MATCH (`firstname` ,  `lastname`) AGAINST ('" . $name . "*' IN BOOLEAN MODE)) ") : ("")) .
                                                                         ((!empty($name) && !empty($gender)) ? " AND " : "") . ((!empty($gender)) ? (" `gender`='" . $gender . "'") : "") .
                                                                         (((!empty($name) || (!empty($gender))) && !empty($age)) ? " AND " : "") . ((!empty($age)) ? " `birthYear` >= year(CURRENT_TIMESTAMP)-" . $age : "") .
                                                                         " AND `id`<>" . $curUser .
                                                                         ((isSet($leftLimit)) ? (" LIMIT " . $leftLimit) : '') .
                                                                         ((isSet($rightLimit)) ? "," . $rightLimit : '')));
    }
}
