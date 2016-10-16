<?php

namespace rokorolov\parus\user\commands;

/**
 * UpdateUserCommand
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class UpdateUserCommand
{
    public $model;
    
    private $id;
    private $email;
    private $username;
    private $password;
    private $status;
    private $name;
    private $surname;
    private $language;
    private $role;
    
    public function __construct($id, $email, $username, $password, $status, $name, $surname, $language, $role, $model)
    {
        $this->id = $id;
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->status = $status;
        $this->name = $name;
        $this->surname = $surname;
        $this->language = $language;
        $this->role = $role;
        $this->model = $model;
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
    
    public function getStatus()
    {
        return $this->status;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getSurname()
    {
        return $this->surname;
    }
    
    public function getRole()
    {
        return $this->role;
    }
    
    public function getModel()
    {
        return $this->model;
    }
    
    public function getId()
    {
        return $this->model->id;
    }
}
