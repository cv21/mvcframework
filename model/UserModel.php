<?php
/**
 * @author Vitaly
 * Date: 24.04.12
 */
class UserModel
{
    private $id;
    private $firstname;
    private $lastname;
    private $gender;
    private $birthDay;
    private $birthMonth;
    private $birthYear;
    private $avatar;
    private $email;
    private $password;
    private $status;

    function __construct($id = null, $firstname = null, $lastname = null, $gender = null,
                         $birthDay = null, $birthMonth = null, $birthYear = null, $avatar = null,
                         $email = null, $password = null, $status = null)
    {
        $this->setId($id)
             ->setFirstname($firstname)
             ->setLastname($lastname)
             ->setGender($gender)
             ->setBirthDay($birthDay)
             ->setBirthMonth($birthMonth)
             ->setBirthYear($birthYear)
             ->setAvatar($avatar)
             ->setEmail($email)
             ->setPassword($password)
             ->setStatus($status);
    }


    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setBirthDay($birthDay)
    {
        $this->birthDay = $birthDay;
        return $this;
    }

    public function getBirthDay()
    {
        return $this->birthDay;
    }

    public function setBirthMonth($birthMonth)
    {
        $this->birthMonth = $birthMonth;
        return $this;
    }

    public function getBirthMonth()
    {
        return $this->birthMonth;
    }

    public function setBirthYear($birthYear)
    {
        $this->birthYear = $birthYear;
        return $this;
    }

    public function getBirthYear()
    {
        return $this->birthYear;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }
}