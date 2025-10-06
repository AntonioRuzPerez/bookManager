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

    $ret= "<div id='searchBookApiForm''>
                <h2>"._t('search_books')."</h2>
                <table id='searchForm'>
                    <tr>
                        <td>
                            <label for='isbn'>"._t('isbn_label').":</label>
                            <input type='text' id='isbn' name='isbn' placeholder='"._t('isbn_placeholder')."' /> 
                        </td>
                        
                        <td>
                            <label for='title'>"._t('title_label').":</label>   
                            <input type='text' id='title' name='title' placeholder='"._t('title_placeholder')."' />   
                        
                        <td>
                            <label for='author'>"._t('author_label').":</label>  
                            <input type='text' id='author' name='author' placeholder='"._t('author_placeholder')."' /   >
                        </td>
                        <td>
                            <button id='searchButton' onclick='ajaxLoadBodyOption(\"GetBook\",\"".$lang."\")'>"._t('search')."</button> 
                        </td> 
                    </tr>
                </table>
                <p style='height:20px'></p>
                <div id='searchResults'></div>
                <p style='height:20px'></p>
                <div id='formEditBook'></div>
                <p style='height:20px'></p>
                <div id='searchAPIBookForm'>
                <h2>"._t('labelFormApi')."</h2>
                    <table id='searchForm'>
                        <tr>
                            <td>
                                <label for='isbn_api'>"._t('isbn_label').":</label>
                                <input type='text' id='isbn_api' name='isbn_api' placeholder='"._t('isbn_placeholder')."' /> 
                            </td>
                            <td>
                                <label for='title_api'>"._t('title_label').":</label>   
                                <input type='text' id='title_api' name='title_api' placeholder='"._t('title_placeholder')."' />   
                            
                            <td>
                                <label for='author_api'>"._t('author_label').":</label>  
                                <input type='text' id='author_api' name='author_api' placeholder='"._t('author_placeholder')."' /   >
                            </td>
                            <td>
                                <button id='searchButtonApi' onclick='ajaxBookApiQuery(\"".$lang."\")'>"._t('loadBookInfo')."</button> 
                            </td> 
                        </tr>
                    </table>
                    <p style='height:20px'></p>
                    <div id='searchApiResults'></div>
                </div>
            </div>";

    echo $ret;
?>