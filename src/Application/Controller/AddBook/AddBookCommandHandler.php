<?php

class AddBookCommandHandler{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository){
        $this->bookRepository = $bookRepository;
    }

    public function handle(AddBookCommand $command): AddBookCommandResponse{
        $book = Book::create(0,$command->getTitle(),$command->getAuthor(),$command->getIsbn(),
               $command->getYear(),$command->getSummary()
        );

        try {
            $saved = $this->bookRepository->save($book);
            if ($saved) {
                return new AddBookCommandResponse(true, $book->getId());
            } else {
                return new AddBookCommandResponse(false, null, "Failed to save book for unknown reason.");
            }
        } catch (\Exception $e) {
            return new AddBookCommandResponse(false, null, $e->getMessage());
        }
    }
}
