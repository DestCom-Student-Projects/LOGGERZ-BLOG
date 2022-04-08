<?php 

class Post{
    private int $id;
    private string $author_uid;
    private string $title;
    private string $content;
    private string $created_at;

    public function getId(): int{
        return $this->id;
    }

    public function getAuthorUid(): string{
        return $this->author_uid;
    }

    public function setAuthorUid(string $author_uid): void{
        $this->author_uid = $author_uid;
    }

    public function getTitle(): string{
        return $this->title;
    }

    public function setTitle(string $title): void{
        $this->title = $title;
    }

    public function getContent(): string{
        return $this->content;
    }

    public function setContent(string $content): void{
        $this->content = $content;
    }

    public function getCreatedAt(): string{
        return $this->created_at;
    }
}

?>