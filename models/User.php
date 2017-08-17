<?php

/**
 * This is an example of User Class using PDO.
 *
 */

namespace models;

use lib\Core;
use PDO;

class User
{
    protected $core;

    public function __construct()
    {
        $this->core = Core::getInstance();
        //$this->core->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Get all users
    public function getUsers()
    {
        $data = array();

        $sql = 'SELECT * FROM evnt_usuario';
        $stmt = $this->core->dbh->prepare($sql);
        //$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data = 0;
        }

        return $data;
    }

    // Get user by the Id
    public function getUserById($idx)
    {
        $data = array();

        $sql = "SELECT nombre * evnt_usuario WHERE id=$idx";
        $stmt = $this->core->dbh->prepare($sql);
        //$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data = 0;
        }

        return $data;
    }

    // Get user by the Login
    public function getUserByLogin($email, $pass)
    {
        $data = array();

        $sql = 'SELECT * FROM user WHERE email=:email AND password=:pass';
        $stmt = $this->core->dbh->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data = 0;
        }

        return $data;
    }

    // Insert a new user
    public function insertUser($data)
    {
        try {
            $sql = 'INSERT INTO user (name, email, password, role) 
					VALUES (:name, :email, :password, :role)';
            $stmt = $this->core->dbh->prepare($sql);
            if ($stmt->execute($data)) {
                return $this->core->dbh->lastInsertId();
            }

            return '0';
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // Update the data of an user
    public function updateUser($data)
    {
    }

    // Delete user
    public function deleteUser($idx)
    {
    }
}
