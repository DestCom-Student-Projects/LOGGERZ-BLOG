<?php

    class userManager{

        private $pdo;
        private $pdoStatement;

        public function __construct()
        {
            $host = 'db';
            $user = 'root';
            $pass = 'password';
            $mydatabase = 'data';
            $connectorString = 'mysql:host='. $host . ';dbname=' . $mydatabase;
            $this->pdo = new PDO($connectorString, $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }

        public function create(User $user){
            $this->pdoStatement = $this->pdo->prepare("INSERT INTO user (username, password, uid) VALUES (:username, :password, :uid)");

            $this->pdoStatement->bindValue(':username', $user->getUsername(), PDO::PARAM_STR);
            $this->pdoStatement->bindValue(':password', $user->getPassword(), PDO::PARAM_STR);
            $this->pdoStatement->bindValue(':uid', $user->getUid(), PDO::PARAM_STR);

            $executeIsOk = $this->pdoStatement->execute();

            if(!$executeIsOk){
                return false;
            }
            else{
                $id = $this->pdo->lastInsertId();
                $user = $this->read($id);

                return $id;
            }
        }
        
        public function getLastId(){
            $id = $this->pdo->lastInsertId();
            return $id;
        }

        public function read($id){
            $this->pdoStatement = $this->pdo->prepare('SELECT * FROM user WHERE id = :id');

            $this->pdoStatement->bindValue(':id', $id, PDO::PARAM_INT);

            $executeIsOk = $this->pdoStatement->execute();

            if($executeIsOk){
                $user = $this->pdoStatement->fetchObject('User');

                if($user === false){
                    return null;
                }
                else{
                    return $user;
                }
            }
            else{
                return false;
            }
        }

        public function readByUsername($username){
            $this->pdoStatement = $this->pdo->prepare('SELECT * FROM user WHERE username = :username');

            $this->pdoStatement->bindValue(':username', $username, PDO::PARAM_STR);

            $executeIsOk = $this->pdoStatement->execute();

            if($executeIsOk){
                $user = $this->pdoStatement->fetchObject('User');

                if($user === false){
                    return null;
                }
                else{
                    return $user;
                }
            }
            else{
                return false;
            }
        }

        public function readByToken($token){
            $this->pdoStatement = $this->pdo->prepare('SELECT * FROM user WHERE uid = :uid');

            $this->pdoStatement->bindValue(':uid', $token, PDO::PARAM_STR);

            $executeIsOk = $this->pdoStatement->execute();

            if($executeIsOk){
                $user = $this->pdoStatement->fetchObject('User');

                if($user === false){
                    return null;
                }
                else{
                    return $user;
                }
            }
            else{
                return false;
            }
        }
        
        public function readAll(){
            $this->pdoStatement = $this->pdo->query('SELECT * FROM user');

            $users = [];

            while($user = $this->pdoStatement->fetchObject('User')){
                $users[]= $user;
            }

            return $users;
        }

        public function update(User $user){
            $this->pdoStatement = $this->pdo->prepare('UPDATE user SET username=:username, password=:password WHERE id=:id LIMIT 1');

            $this->pdoStatement->bindValue(':username', $user->getUsername(), PDO::PARAM_STR);
            $this->pdoStatement->bindValue(':password', $user->getPassword(), PDO::PARAM_STR);
            $this->pdoStatement->bindValue(':id', $user->getId(), PDO::PARAM_STR);

            return $this->pdoStatement->execute();
        }

        public function delete(User $user){
            $this->pdoStatement = $this->pdo->prepare('DELETE FROM user WHERE id=:id LIMIT 1');

            $this->pdoStatement->bindValue(':id', $user->getId(), PDO::PARAM_INT);

            return $this->pdoStatement->execute();
        }
    }

?>