<?php
final class GetBooksQuery{
    private string $isbn;
    private string $author;
    private string $title;

    public function __construct(string $isbn,string $author,string $title){
        $this->isbn = $isbn;
        $this->author = $author;
        $this->title = $title;
    }

    public function getBookIsbn(): string{
        return $this->isbn;
    }
    public function getBookAuthor(): string{
        return $this->author;
    }
    public function getBookTitle(): string{
        return $this->title;
    }

    public function getParamsList(): array{
        $params = [];
        if (!empty($this->isbn)) {
            $params["isbn"] = $this->isbn;
        }
        if (!empty($this->author)) {
            $params["author"] = $this->author;
        }
        if (!empty($this->title)) {
            $params["title"] = $this->title;
        }
        return $params;
    }
}