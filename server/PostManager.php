<?php 

    class postManager{

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

        public function create(Post $post){
            $this->pdoStatement = $this->pdo->prepare("INSERT INTO posts (author_uid, title, content) VALUES (:author_uid, :title, :content)");

            $this->pdoStatement->bindValue(':author_uid', $post->getAuthorUid(), PDO::PARAM_STR);
            $this->pdoStatement->bindValue(':title', $post->getTitle(), PDO::PARAM_STR);
            $this->pdoStatement->bindValue(':content', $post->getContent(), PDO::PARAM_STR);

            $executeIsOk = $this->pdoStatement->execute();

            if(!$executeIsOk){
                return false;
            }
            else{
                $id = $this->pdo->lastInsertId();
                $post = $this->read($id);

                return $id;
            }
        }

        public function getLastId(){
            $id = $this->pdo->lastInsertId();
            return $id;
        }

        public function read($id){
            $this->pdoStatement = $this->pdo->prepare('SELECT * FROM posts WHERE id = :id');

            $this->pdoStatement->bindValue(':id', $id, PDO::PARAM_INT);

            $executeIsOk = $this->pdoStatement->execute();

            if($executeIsOk){
                $post = $this->pdoStatement->fetchObject('Post');

                if($post === false){
                    return null;
                }
                else{
                    return $post;
                }
            }
            else{
                return null;
            }
        }

        public function readAll(){
            $this->pdoStatement = $this->pdo->prepare('SELECT * FROM posts');

            $executeIsOk = $this->pdoStatement->execute();

            if($executeIsOk){
               $post = [];

               $posts = [];

                while($post = $this->pdoStatement->fetchObject('Post')){
                    $posts[]= $post;
                }

                if($posts === false){
                    return null;
                }
                else{
                    return $posts;
                }
            }
            else{
                return null;
            }
        }

        public function update(Post $post){
            $this->pdoStatement = $this->pdo->prepare("UPDATE posts SET author_uid = :author_uid, title = :title, content = :content, created_at = :created_at WHERE id = :id");

            $this->pdoStatement->bindValue(':author_uid', $post->getAuthorUid(), PDO::PARAM_STR);
            $this->pdoStatement->bindValue(':title', $post->getTitle(), PDO::PARAM_STR);
            $this->pdoStatement->bindValue(':content', $post->getContent(), PDO::PARAM_STR);
            $this->pdoStatement->bindValue(':created_at', $post->getCreatedAt(), PDO::PARAM_STR);
            $this->pdoStatement->bindValue(':id', $post->getId(), PDO::PARAM_INT);

            $executeIsOk = $this->pdoStatement->execute();

            if(!$executeIsOk){
                return false;
            }
            else{
                $post = $this->read($post->getId());

                return $post;
            }
        }

        public function delete($id){
            $this->pdoStatement = $this->pdo->prepare('DELETE FROM posts WHERE id = :id');

            $this->pdoStatement->bindValue(':id', $id, PDO::PARAM_INT);

            $executeIsOk = $this->pdoStatement->execute();

            if(!$executeIsOk){
                return false;
            }
            else{
                return true;
            }
        }

        public function getPostsByUser($userId){
            $this->pdoStatement = $this->pdo->prepare('SELECT * FROM posts WHERE author_uid = :userId');

            $this->pdoStatement->bindValue(':userId', $userId, PDO::PARAM_STR);

            $executeIsOk = $this->pdoStatement->execute();

            if($executeIsOk){
                $posts = $this->pdoStatement->fetchAll(PDO::FETCH_CLASS, 'Post');

                if($posts === false){
                    return null;
                }
                else{
                    return $posts;
                }
            }
            else{
                return null;
            }
        }

        
    }

?>