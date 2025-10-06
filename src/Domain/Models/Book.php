<?php
class Book{
    private int $id;
    private string $title;
    private string $author;
    private string $isbn;
    private int $year;
    private string $summary;

    private function __construct(int $id, string $title, string $author, string $isbn, int $year, string $summary){
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->isbn = $isbn;
        $this->year = $year;
        $this->summary = $summary;
    }

    public static function create(int $id, string $title, string $author, string $isbn, int $year, string $summary): self{
        return new self($id, $title, $author, $isbn, $year, $summary);
    }

    public function getId(): int { return $this->id; }
    public function setId($nId): void { $this->id=$nId; }
    
    public function getTitle(): string { return $this->title; }
    public function setTitle($nTitle): void { $this->title=$nTitle; }
    
    public function getAuthor(): string { return $this->author; }
    public function setAuthor($nAuthor): void { $this->author=$nAuthor; }
    
    public function getIsbn(): string { return $this->isbn; }
    public function setIsbn($nIsbn): void { $this->isbn=$nIsbn; }
    
    public function getYear(): int { return $this->year; }
    public function setYear($nYear): void { $this->year=$nYear; }

    public function getSummary(): string { return $this->summary; }
    public function setSummary($nSummary): void { $this->summary=$nSummary; }
}