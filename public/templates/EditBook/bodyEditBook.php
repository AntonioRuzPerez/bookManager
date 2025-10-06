<?php
include "../../../src/Infrastructure/Database/MySQLDatabase.php";
include "../../../src/Infrastructure/Database/DBConnInfo.php";
include "../../../src/Domain/Repository/BookRepository.php";
include "../../../src/Domain/Exceptions/BookNotFoundException.php";
include "../../../src/Application/Controller/EditBook/EditBookCommand.php";
include "../../../src/Application/Controller/EditBook/EditBookCommandHandler.php";
include "../../../src/Application/Controller/EditBook/EditBookCommandResponse.php";

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
$editBookCommandHandler = new EditBookCommandHandler($bookRepository);
try {
    $command = new EditBookCommand($params["id"], $params["title"], $params["author"], $params["isbn"],$params["year"],$params["summary"]);
    $responseDto = $editBookCommandHandler->handle($command);

    $ret=($responseDto->isSuccess()?_t("BookEditedSuccesfully"):$responseDto->getErrorMessage());
} catch (BookNotFoundException $e) {
    $ret= $e->getMessage();
} catch (\Throwable $e) {
    $ret= $e->getMessage();
}

echo $ret;
