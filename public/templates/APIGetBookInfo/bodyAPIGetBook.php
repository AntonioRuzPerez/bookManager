<?php
    include_once "../../../src/Application/Services/OpenLibraryCurlService.php";

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

    //Inicializamos el servicio de consulta a la API
    $openLibraryService = new OpenLibraryCurlService();
    try {
        $responseDto = $responseDto=$openLibraryService->searchBooks($params);

        $tableHeaders="<tr style='strong'>
                            <th>"._t('title_label')."</th>
                            <th>"._t('author_label')."</th>
                            <th>"._t('isbn_label')."</th>
                            <th>"._t('year_label')."</th>
                            <th>"._t('summary_label')."</th>
                        </tr>";
        $tableBody="";
        foreach($responseDto as $bookOrder=>$bookData) {
            $bookId=$bookData->getId();
            $tableBody.="<tr id='book_".$bookId."'>
                            <td id='book_".$bookId."_title'>"  .$bookData->getTitle().  "</td>
                            <td id='book_".$bookId."_author'>" .$bookData->getAuthor(). "</td>
                            <td id='book_".$bookId."_isbn'>"   .$bookData->getIsbn().   "</td>
                            <td id='book_".$bookId."_year'>"   .$bookData->getYear().   "</td>
                            <td id='book_".$bookId."_summary'>".$bookData->getSummary()."</td>
                         </tr>";
        }
        $ret = "<table id='bookResult' style='width: 100%; border-collapse: collapse;'>".$tableHeaders.$tableBody."</table>";
    } catch (BookNotFoundException $e) {
        $ret= "<p>".$e->getMessage()."</p>";
    } catch (\Throwable $e) {
        $ret= "<p>".$e->getMessage()."</p>";
    }

    echo $ret;