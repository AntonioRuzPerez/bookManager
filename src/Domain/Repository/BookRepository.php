<?php
include_once __DIR__ ."/../Models/Book.php";
include_once "BookRepositoryInterface.php";

class BookRepository implements BookRepositoryInterface{
    private MySQLDatabase $db;

    public function __construct(MySQLDatabase $db){
        $this->db = $db;
    }

    public function findById(string $id): ?Book {
        $sql = "SELECT id, title, author, isbn, year, summary FROM Book WHERE id = ?";

        $result = $this->db->query($sql, [$id], 's');

        if ($result === false || $result->num_rows === 0) {
            return null; // Libro no encontrado
        }
        $bookData = $result->fetch_assoc();

        return Book::create($bookData['id'],$bookData['title'],$bookData['author'],$bookData['isbn'],$bookData['year'],$bookData['summary']);
    }

    public function findBy(array $params, string $types): array {
        $sql = "SELECT id, title, author, isbn, year, summary FROM Book";
        $qParams=[];
        if(count($params)>0){
            $where="";
            foreach ($params as $key => $value) {
                //Búsqueda completa
                //$where.=($where==="")?"$key = ?":" and $key = ?";
                //$qParams[]=$value;
                //Búsqueda parcial
                $where.=($where==="")?"$key like ? ":"and $key like ?";
                $qParams[]="%".$value."%";
            }
            $sql.=" WHERE ".$where;
        }

        $result = $this->db->query($sql, $qParams, $types);

        if ($result === false || $result->num_rows === 0) {
            return []; // Libro no encontrado, devolvemos lista vacía
        }
        $bookListData = $result->fetch_all(MYSQLI_ASSOC);

        $arrBook=[];
        foreach ($bookListData as $bookData) {
            $arrBook[]=Book::create($bookData['id'],$bookData['title'],$bookData['author'],$bookData['isbn'],$bookData['year'],$bookData['summary']);
        }
        return $arrBook;
    }

    public function save(Book $book) {
        if ($book->getId()) {
            // UPDATE
            $sql = "UPDATE Book SET title = ?, author = ?, isbn = ?, year = ?, summary = ? WHERE id = ?";
            $params = [
                $book->getTitle(),
                $book->getAuthor(),
                $book->getIsbn(),
                $book->getYear(),
                $book->getSummary(),
                $book->getId()
            ];
            $types = 'sssisi';
        } else {
            // INSERT
            $sql = "INSERT INTO Book (title, author, isbn, year, summary) VALUES (?, ?, ?, ?, ?)";
            $params = [
                $book->getTitle(),
                $book->getAuthor(),
                $book->getIsbn(),
                $book->getYear(),
                $book->getSummary()
            ];
            $types = 'sssis';
        }

        $result = $this->db->executeCommand($sql, $params, $types);

//        if ($result === false) {
//            return false;
//        }

        if (!$book->getId()) {
            $book->setId($this->db->getLastInsertId());
        }

        return $result;
        //return true;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM Book WHERE id = ?";
        $params = [$id];
        $types = 'i';

        return $this->db->executeCommand($sql, $params, $types);
    }

}