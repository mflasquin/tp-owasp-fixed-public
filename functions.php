<?php
require_once 'vendor/autoload.php';

use ZxcvbnPhp\Zxcvbn;

function connectDb()
{
    try {
        $conn = new PDO("mysql:host=127.0.0.1;dbname=authentication", 'mflasquin', 'mflasquin');
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}

function logUser($email, $password)
{
    $connexion = connectDb();
    $sql = 'SELECT * FROM users WHERE email = ?';
    $values = [$email];
    $stmt = $connexion->prepare($sql);
    $stmt->execute($values);
    $users = $stmt->fetchAll(PDO::FETCH_OBJ);
    if ($users) {
        $user = $users[0];
        if(password_verify($password, $user->password)) {
            return $user;
        }
    }

    return false;
}

function getUser($id) {
    $connexion = connectDb();
    $sql = 'SELECT * FROM users WHERE id = ?';
    $values = [$id];
    $stmt = $connexion->prepare($sql);
    $stmt->execute($values);

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getUserByEmail($email) {
    $connexion = connectDb();
    $sql = 'SELECT * FROM users WHERE email = ?';
    $values = [$email];
    $stmt = $connexion->prepare($sql);
    $stmt->execute($values);

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function saveUser($email, $username, $password) {
    $connexion = connectDb();
    $sql = 'INSERT INTO users(username,email,password) VALUES(?,?,?)';
    $values = [$email, $username, password_hash($password, PASSWORD_DEFAULT)];
    $stmt = $connexion->prepare($sql);

    return $stmt->execute($values);
}

function testPassworsStrenght($password) {
    $zxcvbn = new Zxcvbn();
    $weak = $zxcvbn->passwordStrength($password);
    if($weak['score'] < 4) {
        return false;
    }

    return true;
}