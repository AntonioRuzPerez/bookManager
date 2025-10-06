<?php

class GetBooksQueryHandler{
    private BookRepositoryInterface $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository){
        $this->bookRepository = $bookRepository;
    }

    public function handle(GetBooksQuery $query): GetBooksQueryResponse{
        $paramsList=$query->getParamsList();

        $types = str_repeat('s', count($paramsList));
        $booksList = $this->bookRepository->findBy($paramsList,$types);

        if ($booksList === null or count($booksList)==0) {
            $message=_t("BookNotFoundException");
            throw new BookNotFoundException($message);
        }

        return new GetBooksQueryResponse($booksList);
    }
}