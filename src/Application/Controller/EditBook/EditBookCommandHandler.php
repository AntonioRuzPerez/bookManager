<?php

class EditBookCommandHandler{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository){
        $this->bookRepository = $bookRepository;
    }

    public function handle(EditBookCommand $command): EditBookCommandResponse{
        $book = Book::create($command->getId(),$command->getTitle(),$command->getAuthor(),$command->getIsbn(),
            $command->getYear(),$command->getSummary()
        );

        try {
            $saved = $this->bookRepository->save($book);
            if ($saved) {
                return new EditBookCommandResponse(true, $book->getId());
            } else {
                return new EditBookCommandResponse(false, null, "Failed to save book for unknown reason.");
            }
        } catch (\Exception $e) {
            return new EditBookCommandResponse(false, null, $e->getMessage());
        }
    }
}
