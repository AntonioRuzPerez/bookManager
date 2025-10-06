<?php
class GetBookQueryResponse{
    public int $id;
    public string $title;
    public string $author;
    public string $isbn;
    private int $year;
    private string $summary;

    public function __construct(int $id, string $title, string $author, string $isbn, int $year, string $summary){
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->isbn = $isbn;
        $this->year = $year;
        $this->summary = $summary;
    }

    public function toArray(): array{
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'isbn' => $this->isbn,
            'year' => $this->year,
            'summary' => $this->summary,
        ];
    }
}
