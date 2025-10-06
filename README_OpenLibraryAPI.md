# Open Library API:
https://openlibrary.org
## Book API

La Book API te permite obtener detalles específicos sobre libros utilizando identificadores como ISBN, OCLC, LCCN, y OLID.

### Endpoint

GET /api/books


### Parámetros

*   **`bibkeys`**: Identificadores del libro.  Usa cualquiera de los siguientes formatos:
    *   `ISBN:{código ISBN}`
    *   `OCLC:{código OCLC}`
    *   `LCCN:{código LCCN}`
    *   `OLID:{código OLID}`
    *   Ejemplo: `bibkeys=ISBN:0451526538`
*   **`jscmd`**: Conjunto de datos a obtener.
    *   `viewapi`: Devuelve información básica.
        *   `bib_key`, `info_url`, `preview`, `preview_url`, `thumbnail_url`
    *   `data`: Devuelve un conjunto de datos más completo y estable. (Recomendado)
        *   `url`, `title and subtitle`, `authors`, `identifiers`, `classifications`, `subjects`, `subject_places`, `subject_people`, `subject_times`, `publishers`, `publish_places`, `publish_date`, `excerpts`, `links`, `cover`, `ebooks`, `number_of_pages`, `weight`
    *   `details`: Proporciona información adicional a `viewapi`.
    *   Ejemplo: `jscmd=data`
*   **`format`**: Formato de la respuesta.
    *   `json` (predeterminado)
    *   `javascript`
    *   Ejemplo: `format=json`

### Ejemplo de Uso

GET /api/books?bibkeys=ISBN:0451526538&jscmd=data&format=json


### Respuesta

La API devuelve un objeto JSON con información detallada sobre el libro solicitado.

## Search API

La Search API te permite buscar libros por autor, título y otros criterios.

### Endpoint

GET /search.json


### Parámetros

*   **`author`**: Nombre del autor.
    *   Ejemplo: `author=Mark Twain`
*   **`title`**: Título del libro.
    *   Ejemplo: `title=Las Aventuras de Tom Sawyer`
*   **`q`**:  Término de búsqueda general (busca en título, autor, etc.).
    *   Ejemplo: `q=ciencia ficción`
*   **`subject`**: Tema del libro.
    *   Ejemplo: `subject=ciencia ficción`
*   **`language`**: Idioma del libro (código ISO 639-1).
    *   Ejemplo: `language=es`
*   **`limit`**: Número máximo de resultados a devolver.
    *   Ejemplo: `limit=10`
*   **`offset`**:  Número de resultados a omitir para paginación.
    *   Ejemplo: `offset=0`

### Ejemplo de Uso

GET /search.json?author=Mark Twain&title=Las Aventuras de Tom Sawyer&limit=10


### Respuesta

La API devuelve un objeto JSON con una lista de resultados que coinciden con tus criterios de búsqueda.