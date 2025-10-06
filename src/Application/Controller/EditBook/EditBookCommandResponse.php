<?php

class EditBookCommandResponse{
    private bool $success;
    private ?int $bookId;
    private ?string $errorMessage;

    public function __construct(bool $success, ?int $bookId = null, ?string $errorMessage = null){
        $this->success = $success;
        $this->bookId = $bookId;
        $this->errorMessage = $errorMessage;
    }

    public function isSuccess(): bool{
        return $this->success;
    }

    public function getBookId(): ?int{
        return $this->bookId;
    }

    public function getErrorMessage(): ?string{
        if (empty($this->errorMessage)) {
            return null;
        }
        $message = $this->errorMessage;
        //Formateamos el mensaje para que sea mas legible por los usuarios
        if (preg_match("/Duplicate entry '(\d+)' for key 'Book\.isbnUnique'/i", $message, $matches)) {
            $isbn = $matches[1];
            return "Ya existe un libro con el ISBN '$isbn'.";
        }
        return $this->errorMessage;
    }
}
