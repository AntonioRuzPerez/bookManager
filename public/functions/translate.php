<?php
// Por defecto, usamos español si no se especifica un idioma válido
$lang = 'es';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['es', 'en'])) {
    $lang = $_GET['lang'];
}
// Cargamos el archivo de idioma correspondiente
$translations = require_once __DIR__ . "/../lang/{$lang}.php";
// Función para la traduccion
function _t($key) {
    global $translations;
    return isset($translations[$key]) ? $translations[$key] : $key;
}