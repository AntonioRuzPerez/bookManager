<?php
    include_once "../../../src/Infrastructure/Database/MySQLDatabase.php";
    include_once "../../../src/Infrastructure/Database/DBConnInfo.php";
    include_once "../../../src/Domain/Repository/BookRepository.php";
    include_once "../../../src/Domain/Exceptions/BookNotFoundException.php";
    include_once "../../../src/Application/Controller/GetBook/GetBooksQuery.php";
    include_once "../../../src/Application/Controller/GetBook/GetBooksQueryHandler.php";
    include_once "../../../src/Application/Controller/GetBook/GetBooksQueryResponse.php";

    // Por defecto, usamos español si no se especifica un idioma válido
    $lang = 'es';
    if (isset($_POST['lang']) && in_array($_POST['lang'], ['es', 'en'])) {
        $lang = $_POST['lang'];
        unset($_POST['lang']);
    }

    // Cargamos el archivo de idioma correspondiente
    $translations = require_once "../../lang/{$lang}.php";
    // Función para la traduccion
    function _t($key) {
        global $translations;
        return isset($translations[$key]) ? $translations[$key] : $key;
    }

    // Cargamos los parametros para la consulta
    $params=$_POST;
    unset($_POST);
    // Inicializar conexión a la base de datos
    $mysqlDatabase = new MySQLDatabase(HOSTNAME, USERNAME, PASSWORD, DATABASE);
    $mysqlDatabase->connect();
    $bookRepository = new BookRepository($mysqlDatabase);
    $getBookQueryHandler = new GetBooksQueryHandler($bookRepository);
    try {
        $query = new GetBooksQuery($params["isbn"], $params["author"], $params["title"]);
        $responseDto = $getBookQueryHandler->handle($query)->getBooksList();

        $tableHeaders="<tr style='strong'>
                            <th>"._t('id_label')."</th>
                            <th>"._t('title_label')."</th>
                            <th>"._t('author_label')."</th>
                            <th>"._t('isbn_label')."</th>
                            <th>"._t('year_label')."</th>
                            <th>"._t('summary_label')."</th>
                            <th>"._t('edit')."</th>
                            <th>"._t('delete')."</th>
                        </tr>";
        $tableBody="";
        foreach($responseDto as $bookOrder=>$bookData) {
            $bookId=$bookData->getId();
            $tableBody.="<tr id='book_".$bookId."'>
                            <td id='book_".$bookId."_id'>"     .$bookId.     "</td>
                            <td id='book_".$bookId."_title'>"  .$bookData->getTitle().  "</td>
                            <td id='book_".$bookId."_author'>" .$bookData->getAuthor(). "</td>
                            <td id='book_".$bookId."_isbn'>"   .$bookData->getIsbn().   "</td>
                            <td id='book_".$bookId."_year'>"   .$bookData->getYear().   "</td>
                            <td id='book_".$bookId."_summary'>".$bookData->getSummary()."</td>
                            <td> <button id='btnEditBook_".$bookId."' onclick='ajaxLoadEditForm(\"".$bookId."\",\"".$lang."\")'>"._t('edit')."</button> </td>
                            <td> <button id='btnDelBook_". $bookId."' onclick='ajaxLoadDeleteForm(\"".$bookId."\",\"".$lang."\")'>"._t('delete')."</button> </td>
                         </tr>";
        }
        $ret = "<table id='bookResult' style='width: 100%; border-collapse: collapse;'>".$tableHeaders.$tableBody."</table>";
    } catch (BookNotFoundException $e) {
        $ret= "<p>".$e->getMessage()."</p>";
    } catch (\Throwable $e) {
        $ret= "<p>".$e->getMessage()."</p>";
    }

    echo $ret;