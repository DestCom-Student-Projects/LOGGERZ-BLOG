<?php
    class User{
        private int $id;
        private string $username;
        private string $password;
        private string $uid;
        private string $created_at;

        public function getId(): int{
            return $this->id;
        }

        public function getUsername(): string{
            return $this->username;
        }

        public function setUsername(string $username): void{
            $this->username = $username;
        }

        public function getPassword(): string{
            return $this->password;
        }

        public function setPassword(string $password): void{
            $this->password = $password;
        }

        public function getUid(): string{
            return $this->uid;
        }

        public function setUid(string $uid): void{
            $this->uid = $uid;
        }

        public function getCreatedAt(): string{
            return $this->created_at;
        }
    }

?>