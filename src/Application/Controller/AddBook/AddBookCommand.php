<?php

class AddBookCommand{
    private string $title;
    private string $author;
    private string $isbn;
    private int $year;
    private string $summary;

    public function __construct(string $title, string $author, string $isbn, int $year, string $summary){
        $this->title = $title;
        $this->author = $author;
        $this->isbn = $isbn;
        $this->year = $year;
        $this->summary = $summary;
    }

    public function getTitle(): string{
        return $this->title;
    }

    public function getAuthor(): string{
        return $this->author;
    }

    public function getIsbn(): string{
        return $this->isbn;
    }

    public function getYear(): int{
        return $this->year;
    }

    public function getSummary(): string{
        return $this->summary;
    }
}
