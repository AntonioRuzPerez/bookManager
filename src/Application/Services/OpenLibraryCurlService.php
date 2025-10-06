<?php
include_once __DIR__."/../../Domain/Models/Book.php";
include_once "FileCache.php";
class OpenLibraryCurlService {
    private const API_BASE_URL_ISBN = 'https://openlibrary.org/api/books?';
    private const API_BASE_URL_NO_ISBN = 'https://openlibrary.org/search.json?';
    private const API_FORMAT_PARAM = "jscmd=data&format=json&sort=new&limit=5";

    private $cache;

    public function __construct() {
        //$this->cache = new FileCache();
    }

    public function searchBooks(array $params): array {
//        //Comprobamos si tenemos los resultados en caché
//        $cacheKey = json_encode($params);
//        $cachedData = $this->cache->get($cacheKey);
//
//        if ($cachedData !== null) {
//            //Devolvemos los datos de la caché en caso de haberlos
//            return $cachedData;
//        }

        $query="";
        $busquedaPorCodigo=false;
        
        if(isset($params["isbn"]) && $params["isbn"]!==""){
            $busquedaPorCodigo=true;
            $query.="bibkeys=ISBN:".$params["isbn"]."&";

            $url = self::API_BASE_URL_ISBN . $query . self::API_FORMAT_PARAM;
        }else if(isset($params["olid"])){ //Para la llamada recursiva
            $busquedaPorCodigo=true;
            $query.="bibkeys=OLID:".$params["olid"]."&";

            $url = self::API_BASE_URL_ISBN . $query . self::API_FORMAT_PARAM;
        }else{
            if($params["title"]!==""){
                $query.="title=".str_replace(" ","%20",$params["title"])."&";
            }
            if($params["author"]!==""){
                $query.="author=".str_replace(" ","%20",$params["author"])."&";
            }
            $url = self::API_BASE_URL_NO_ISBN . $query . self::API_FORMAT_PARAM;
        }

        if (empty($query)) {
            throw new InvalidArgumentException('El campo de búsqueda no puede estar vacío.');
        }

        // Inicializamos cURL
        $ch = curl_init();
        // Configuramos las opciones de cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_USERAGENT, 'bookManager/1.0');

        //Lanzamos la consulta
        $response = curl_exec($ch);

        //Comprobamos si ha habido algun error
        if ($response === false) {
            $error_msg = curl_error($ch);
            $error_no = curl_errno($ch);
            curl_close($ch);
            throw new Exception("Error de cURL ({$error_no}): {$error_msg}");
        }

        //Obtenemos y verificamos el código de estado HTTP
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($http_code !== 200) {
            throw new Exception("Error en la API. Código de estado HTTP: {$http_code}");
        }

        //Decodificamos el JSON response y comprobamos ha ido bien
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Error al decodificar la respuesta JSON: ' . json_last_error_msg());
        }

        //Lanzamos excepción si la consulta se ha lanzado bien, pero no hay resultados
        if(count($data)===0){
            throw new Exception('No se han encontrado libros con los filtros aplicados. ');
        }

        //Si hemos buscado por ISBN, solo vamos a tener un único resultado.
        //Mapeamos los datos de la API como "Book"
        if($busquedaPorCodigo){
            if(isset($params["olid"])){
                //Si hemos hecho la llamada recursiva, devolvemos un book con el OLID en lugar del isbn
                return /*$books =*/ $this->mapSingleBookData($data,false);
            }else{
                return /*$books =*/ $this->mapSingleBookData($data,true);
            }

        }else{
            //Primero tenemos que procesarlos, ya que la api no devuelve ISBN al consultar por autor o título
            $books=[];
            foreach ($data["docs"] as $k=>$book) {
                //Comprobamos si el libro tiene el campo "cover_edition_key" ya que este nos permite buscar por el open library id
                if (isset($book["cover_edition_key"])) {
                    $books[$k]["author"]=$book["author_name"][0];
                    $books[$k]["title"]=$book["title"];
                    $books[$k]["year"]=$book["first_publish_year"];
                    $datosOLID=$this->searchBooks(["olid"=>$book["cover_edition_key"]]);
                    $books[$k]["isbn"]=$datosOLID[0]->getIsbn();
                    $books[$k]["summary"]=$datosOLID[0]->getYear();
                }

            }
            return /*$books =*/ $this->mapMultipleBookData($books,$busquedaPorCodigo);
        }

        //Almacenamos los resultados en caché durante 1 hora (por defecto)
        //$this->cache->set($cacheKey, $books);
        //Y los devolvemos
        //return $books;
    }

    private function mapSingleBookData(array $data, bool $isIsbn): array {
        $books = [];
        foreach ($data as $isbn=>$bookData) {
            $anio=$codigo="";
            if($isIsbn){
                $codigo=str_replace("ISBN:", "", $isbn);
                $anio=(strlen($bookData["publish_date"])==4?$bookData["publish_date"]:DateTime::createFromFormat('d/m/Y', $bookData["publish_date"])->format("Y"));
                $bystmnt=(isset($bookData["subtitle"])?$bookData["subtitle"]:"");
            }else{
                $anio=0;
                $bystmnt=(isset($bookData["subtitle"])?$bookData["subtitle"]:"");
                if(isset($bookData["identifiers"]["isbn_10"]))
                    $codigo=$bookData["identifiers"]["isbn_10"][0];
                elseif(isset($bookData["identifiers"]["isbn_13"]))
                    $codigo=$bookData["identifiers"]["isbn_13"][0];

            }

            $books[]=Book::create(0,
                                     $bookData["title"],
                                     $bookData["authors"][0]["name"],
                                     $codigo,
                                     $anio,
                                     $bystmnt);
        }
        return $books;
    }

    private function mapMultipleBookData(array $data): array {
        $books = [];
        foreach ($data as $isbn=>$bookData) {

            $books[]=Book::create(0,
                $bookData["title"],
                $bookData["author"],
                $bookData["isbn"],
                $bookData["year"],
                $bookData["summary"]);
        }

        return $books;
    }
}

?>

