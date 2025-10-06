<?php
class GetBookQueryHandler{
    private BookRepositoryInterface $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository){
        $this->bookRepository = $bookRepository;
    }

    public function handle(GetBookQuery $query): GetBookQueryResponse    {
        $book = $this->bookRepository->findById($query->getBookId());

        if ($book === null) {
            $message=_t("BookNotFoundException");
            throw new BookNotFoundException($message);
        }

        return new GetBookQueryResponse(
            $book->getId(),
            $book->getTitle(),
            $book->getAuthor(),
            $book->getIsbn(),
            $book->getYear(),
            $book->getSummary()
        );
    }
}