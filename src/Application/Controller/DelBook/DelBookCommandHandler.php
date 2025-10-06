<?php

class DelBookCommandHandler{
    private BookRepository $bookRepository;

    public function __construct(BookRepository $bookRepository){
        $this->bookRepository = $bookRepository;
    }

    public function handle(DelBookCommand $command): DelBookCommandResponse{
        $delId=$command->getId();

        try {
            $deleted = $this->bookRepository->delete($delId);
            if ($deleted) {
                return new DelBookCommandResponse(true, null);
            } else {
                return new DelBookCommandResponse(false, null, "Failed to save book for unknown reason.");
            }
        } catch (\Exception $e) {
            return new DelBookCommandResponse(false, null, $e->getMessage());
        }
    }
}
