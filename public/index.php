<?php
// Por defecto, usamos español si no se especifica un idioma válido
$lang = 'es';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['es', 'en'])) {
    $lang = $_GET['lang'];
}
// Cargamos el archivo de idioma correspondiente
$translations = require_once "lang/{$lang}.php";
// Función para la traduccion
function _t($key) {
    global $translations;
    return isset($translations[$key]) ? $translations[$key] : $key;
}

?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo _t('app_title'); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="functions/functions.js?<?php echo (int)microtime(true); ?>" type="text/javascript"></script>

    <div class="container">
        <header>
            <h1><?php echo _t('app_title'); ?></h1>
            <div class="language-selector">
                <span><?php echo _t('language'); ?>:</span>
                <a href="?lang=es" class="<?php echo ($lang == 'es' ? 'active' : ''); ?>"><?php echo _t('spanish'); ?></a>
                <a href="?lang=en" class="<?php echo ($lang == 'en' ? 'active' : ''); ?>"><?php echo _t('english'); ?></a>
            </div>
        </header>
        <div class="main-content-wrapper">
            <nav class="sidebar">
                <ul>
                    <li><a href="#" id="menuBtnAddBook" onclick="ajaxLoadMenuOption('AddBook'  , '<?=$lang;?>')"><?php echo _t('add_book'); ?></a></li>
                    <li><a href="#" id="menuBtnGetBook" onclick="ajaxLoadMenuOption('GetBook'  , '<?=$lang;?>')"><?php echo _t('search_books'); ?></a></li>
                </ul>
            </nav>
            <main class="content-area">
                <div class="content-header"><h2><?php echo _t('welcome_message'); ?></h2></div>
                <div class="content-body"><p><?php echo _t('select_option'); ?></p></div>
            </main>
        </div>
    </div>
</body>
</html>
