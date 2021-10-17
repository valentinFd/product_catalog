<?php

namespace App\Repositories;

use App\Exceptions\LogInException;
use App\Models\User;

class MySQLUsersRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        try
        {
            $this->pdo = new \PDO("mysql:host=localhost;dbname=db;charset=UTF8", "root", "");
        }
        catch (\PDOException $e)
        {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function validate(string $username, string $password): void
    {
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!empty($user) && password_verify($password, $user["password"]))
        {
            $_SESSION["userId"] = (int)$user["id"];
        }
        throw new LogInException("Incorrect username or password");
    }

    public function get(int $id): ?User
    {
        $sql = "SELECT * FROM user WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!empty($user)) return new User($user["username"], $user["password"], $user["id"]);
        return null;
    }

    public function create(User $user): void
    {
        $sql = "INSERT INTO user (username, password) VALUES(?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user->getUsername(), $user->getPassword()]);
    }

    public function exists(string $username): bool
    {
        $sql = "SELECT username FROM user WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        $username = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!empty($username)) return true;
        return false;
    }
}
