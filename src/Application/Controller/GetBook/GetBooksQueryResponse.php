<?php
class GetBooksQueryResponse{
    public $booksList;

    public function __construct(array $booksList){
        $this->booksList = $booksList;
    }

    public function getBooksList(): array{
        return $this->booksList;
    }
}
