<?php
include_once __DIR__ ."/../Models/Book.php";
interface BookRepositoryInterface{
    public function findById(string $id): ?Book;
}