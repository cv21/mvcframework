<?php
/**
 * @author Vitaly
 * @Date: 24.04.12
 */

/*
 * Обрабатывает все действия, связанные с пользователями
 * Включает в себя регистрацию, вход, выход и отображение самой страницы пользователя
 */

class UserActionController extends ActionController
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

    // выполняет регистрацию пользователя
    // проверяет, существует ли юзер с таким логином/почтой

    function register()
    {
        if(!empty($this->args['firstname']) &&
           !empty($this->args['lastname'])  &&
           !empty($this->args['gender'])    &&
           !empty($this->args['birthday'])  &&
           !empty($this->args['email'])     &&
           !empty($this->args['password']))
        {
            $userMapper = new UserMapper();
            if(!($userMapper->checkExist($this->args['email'])))
            {
                $user = new UserModel();
                $birthDay = explode("/", $this->args['birthday']);

                $user->setFirstname($this->args['firstname'])
                     ->setLastname($this->args['lastname'])
                     ->setGender($this->args['gender'])
                     ->setBirthDay((int)$birthDay[1])
                     ->setBirthMonth((int)$birthDay[0])
                     ->setBirthYear((int)$birthDay[2])
                     ->setEmail($this->args['email'])
                     ->setPassword($this->args['password']);

                $_SESSION['userid'] = $userMapper->add($user);
                header('Location: /user');
            }
            else
            {
                $this->view->setTitle('You are not registered')
                           ->setStructure('user_register');
                $this->view->text = 'User with similar login or email is registered yet';
            }
        }
        else
        {
            $this->view->setStructure('user_register')
                       ->setTitle('Register');
        }
        $this->view->draw();
    }

    // обрабатывает страницу пользователя
    // срабатывает при обращении к "/user"

    function action()
    {
        if(!empty($this->user))
        {
            $messageMapper = new MessageMapper();
            $this->view->newMessages = $messageMapper->setUsersForMessagesFrom($messageMapper->getNewMessages($_SESSION['userid']));
            $this->view->user = $this->user;
            $this->view->setStructure('user')
                 ->setTitle('User')
                 ->draw();
        }
        else
        {
            header('Location: /user/login');
        }
    }

    // авторизует пользователя
    // в случае успеха передает в сессию id пользователя

    function login()
    {
        if(!empty($this->args['email']) && !empty($this->args['password']))
        {
            $userMapper = new UserMapper();
            if($user = $userMapper->extFind("`email`='" . $this->args['email'] . "'"))
            {
                if($user->getPassword() == $this->args['password'])
                {
                    $_SESSION['userid'] = $user->getId();
                    header('Location: /user');
                }
                else header('Location: /user/login');
            }
            else header('Location: /user/login');
        }
        else
        {
            $this->view->setStructure('user_login')
                       ->setTitle('Log in')
                       ->draw();
        }
    }

    function changeAvatar()
    {
        if(!empty($this->user))
        {
            if(($_FILES["avatar"]["size"] < 512*1024) && ($_FILES["avatar"]["type"] == "image/jpeg") && is_uploaded_file($_FILES["avatar"]["tmp_name"]))
            {
                $filename = md5($this->user->getEmail() . $this->user->getPassword());
                move_uploaded_file($_FILES["avatar"]["tmp_name"], AVATARS_PATH . $filename . ".jpg");
                ImageService::resize(AVATARS_PATH . $filename . ".jpg", AVATARS_PATH . $filename . ".jpg", 200);
                ImageService::crop(AVATARS_PATH . $filename . ".jpg", AVATARS_PATH . $filename . ".quad.jpg");
                ImageService::resize(AVATARS_PATH . $filename . ".quad.jpg", AVATARS_PATH . $filename . ".small.quad.jpg", 48);
                $this->user->setAvatar($filename);
                $userMapper = new UserMapper();
                $userMapper->update($this->user);
            }
            header('Location: /user');
        }
        else header('Location: /user/login');
    }

    // выполняет "выход" пользователя очищая текущую сессию

    function logout()
    {
        session_destroy();
        header('Location: /user/login');
    }
}
