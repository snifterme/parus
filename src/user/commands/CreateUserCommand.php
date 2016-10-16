<?php

namespace rokorolov\parus\user\commands;

/**
 * CreateUserCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class CreateUserCommand
{
    public $model;
    
    private $email;
    private $username;
    private $language;
    private $password;
    private $status;
    private $name;
    private $surname;
    private $role;
    
    public function __construct($email, $username, $password, $status, $name, $surname, $language, $role)
    {
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->status = $status;
        $this->name = $name;
        $this->surname = $surname;
        $this->language = $language;
        $this->role = $role;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getLanguage()
    {
        return $this->language;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getSurname()
    {
        return $this->surname;
    }
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function getRole()
    {
        return $this->role;
    }
    
    public function getId()
    {
        return $this->model->id;
    }
}
