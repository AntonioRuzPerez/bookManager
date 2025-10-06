# bookManager
## 🛠️ Configuración e Instalación

**El proyecto está pensado para correr encapsulado dentro de un LAMP (Linux-Apache-MySql-PHP).**
**Hay que descargar ese proyecto primero, desde este repositorio: https://github.com/sprintcube/docker-compose-lamp y descomprimir este proyecto dentro de www ```./LAMP/www/bookManager/```**
**La configuracion de los contenedores está en un archivo .env dentro de este proyecto, en **
- Posteriormente, bastaría con ubicarse en la raiz del proyecto LAMP y lanzar:
    ```
    docker-compose up -d --build
    ```
-   Una vez los los contenedores estén levantados, accede a la aplicación a través de tu navegador web en `http://localhost/bookManager/public/`.
-   Utiliza las opciones en el menú izquierdo (Añadir Libro, Buscar Libros, etc.) para interactuar con el sistema.
    - Añadir Libro: Permite crear libros y consultar con la api externa datos de los mismos.
    - Buscar Libros: Permite buscar libros en la BD local, así como editar o eliminar libros del listado buscado.
