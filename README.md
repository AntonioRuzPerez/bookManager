# bookManager
## 🛠️ Configuración e Instalación

**El proyecto está encapsulado dentro de un LAMP (Linux-Apache-MySql-PHP), solo es necesario ubicarse en la carpeta "lamp" y ejecutar:**
    ```
    docker-compose up -d --build
    ```
**El código del proyecto se encuentra en ```./LAMP/www/bookManager/```**
-   Una vez los los contenedores estén levantados, accede a la aplicación a través de tu navegador web en `http://localhost/bookManager/public/`.
-   Utiliza las opciones en el menú izquierdo (Añadir Libro, Buscar Libros, etc.) para interactuar con el sistema.
    - Añadir Libro: Permite crear libros y consultar con la api externa datos de los mismos.
    - Buscar Libros: Permite buscar libros en la BD local, así como editar o eliminar libros del listado buscado.
