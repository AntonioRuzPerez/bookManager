<?php
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

$ret="<div id='addBookForm'>
            <h2>"._t('edit_book')."</h2>
            <table id='createForm'>
                <tr>
                    <td>
                        <input type='hidden' id='edit_id' name='edit_id' value='".$params["id"]."'/> 
                        <label for='edit_title'>"._t('title_label').":</label>
                        <input type='text' id='edit_title' name='edit_title' required value='".$params["title"]."'/> 
                    </td>
                    <td>
                        <label for='edit_author'>"._t('author_label').":</label> 
                        <input type='text' id='edit_author' name='edit_author' required value='".$params["author"]."'/> 
                    </td>
                    <td>
                        <label for='edit_isbn'>"._t('isbn_label').":</label>
                        <input type='text' id='edit_isbn' name='edit_isbn' required value='".$params["isbn"]."'/> 
                    </td>
                    <td>
                        <label for='edit_year'>"._t('year_label').":</label>
                        <input type='number' id='edit_year' name='edit_year' required value='".$params["year"]."'/> 
                    </td>
                </tr>
                <tr>
                    <td colspan='4'>
                        <label for='edit_summary'>"._t('summary_label').":</label><br>
                        <textarea id='edit_summary' name='edit_summary' required style='width: 100%; box-sizing: border-box; height: 100px; resize: vertical; overflow-y: auto;' >".$params["summary"]."</textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan='3'>
                    </td>
                    <td>
                        <button onclick='ajaxLoadBodyOption(\"EditBook\",\"".$lang."\")'>"._t('edit')."</button>
                    </td>
            </table>
        </div>";

echo $ret;