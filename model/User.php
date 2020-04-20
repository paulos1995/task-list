<?php

namespace Model;

use Core\Model;
use Libs\Message;

class User extends Model
{

    public $id;
    public $login;
    public $email;
    public $password;
    public $passwordConfirm;

    public function __construct()
    {
        parent::__construct();
        $this->table = 'users';
    }

    /**
     * Validate function before save
     * @return bool
     */
    public function validate() {

        $hasError = false;

        if (!$this->login) {
            Message::Error('Login can not be empty');
            $hasError = true;
        }

        if (strlen($this->login) > 60) {
            Message::Error('Login must be less than 60 characters');
            $hasError = true;
        }

        if (!$this->email) {
            Message::Error('Email can not be empty');
            $hasError = true;
        }

        if (strlen($this->email) > 120) {
            Message::Error('Email must be less than 120 characters');
            $hasError = true;
        }

        if (!$this->password) {
            Message::Error('Password can not be empty');
            $hasError = true;
        }

        if (!$this->passwordConfirm) {
            Message::Error('Password confirm can not be empty');
            $hasError = true;
        }

        if ($this->password && $this->passwordConfirm && $this->password !== $this->passwordConfirm) {
            Message::Error('Passwords do not match');
            $hasError = true;
        }

        if (!$hasError && $this->checkLogin()) {
            Message::Error('Login already exists');
            $hasError = true;
        }

        if (!$hasError && $this->checkEmail()) {
            Message::Error('Email already exists');
            $hasError = true;
        }

        return $hasError ? false : true;
    }

    /**
     * Function save new user to DB
     * @return mixed
     */
    public function save()
    {
        $query = "INSERT INTO " . $this->table . " (login, password_hash, email) VALUES ('" .
            $this->login . "', '" .
            password_hash($this->password, PASSWORD_DEFAULT) . "', '" .
            $this->email . "');";
        return $this->mysqli->query($query);
    }

    /**
     * Login user function
     * @return bool
     */
    public function login()
    {
        $user = $this->findUserByLogin()->fetch_assoc();
        if (password_verify($this->password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_login'] = $user['login'];
            return true;
        }
        return false;
    }

    /**
     * Check user is log in
     * @return bool
     */
    public static function isLogin()
    {
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        }
        return false;
    }

    public static function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_login']);
    }

    /**
     * Check user login in db.
     * Return 1 if login exist.
     * @return mixed
     */
    private function checkLogin()
    {
        return $this->findUserByLogin()->num_rows;
    }

    /**
     * Check user email in db.
     * Return 1 if login exist.
     * @return mixed
     */
    private function checkEmail()
    {
        return $this->findUserByEmail()->num_rows;
    }

    /**
     * Find user by login in DB.
     * @return mixed
     */
    private function findUserByLogin()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE login LIKE '" . $this->login . "' LIMIT 1";
        return $this->mysqli->query($query);
    }

    /**
     * Find user by email in DB.
     * @return mixed
     */
    private function findUserByEmail()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE email LIKE '" . $this->email . "' LIMIT 1";
        return $this->mysqli->query($query);
    }

    public function getCurrentUser()
    {
        if (self::isLogin()) {
            $query = "SELECT * FROM " . $this->table . " WHERE id = " . $_SESSION['user_id'] . " LIMIT 1";
            $user = $this->mysqli->query($query)->fetch_assoc();
            $this->id = $user['id'];
            $this->login = $user['login'];
            $this->email = $user['email'];

            return true;
        }

        return false;
    }
}