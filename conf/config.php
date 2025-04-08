<?php
header("Cache-Control: max-age=300, must-revalidate"); // CORRIGINDO O HISTORY.BACK
                                                       // DEFINIR TIMEZONE PADRÃO
date_default_timezone_set("America/Rio_Branco");
// OCULTAR OS WARNING DO PHP
// error_reporting(E_ALL ^ E_WARNING);
// ini_set("display_errors", 0 );
// DEFININDO OS DADOS DE ACESSO AO BANCO DE DADOS
define("DB", 'mysql');
define("DB_HOST", "localhost");
define("DB_NAME", "zatu");
define("DB_USER", "root");
define("DB_PASS", "root");
// CONFIGURACOES PADRAO DO SISTEMA
define("PORTAL_URL", 'http://localhost/delfos/');
define("TITULO_SISTEMA", 'Sistema de Gestão Municipal - ZATU');
define("FAVICON_SISTEMA", 'http://localhost/delfos/assets/images/delfos_favicon.png');
define("LOGO_DASHBOARD", 'http://localhost/delfos/assets/images/delfos_logo.png');
define("ASSETS_FOLDER", 'http://localhost/delfos/assets/');
define("CSS_FOLDER", 'http://localhost/delfos/assets/css/');
define("FONTS_FOLDER", 'http://localhost/delfos/assets/fontes/');
define("IMG_FOLDER", 'http://localhost/delfos/assets/images/');
define("ICONS_FOLDER", 'http://localhost/delfos/assets/icons/');
define("JS_FOLDER", 'http://localhost/delfos/assets/js/');
define("PLUGINS_FOLDER", 'http://localhost/delfos/assets/plugins/');
define("AVATAR_FOLDER", 'http://localhost/delfos/assets/avatar/');
define("PORTAL_URL_SERVIDOR", 'C:/xampp/htdocs/delfos/');
define("UTILS_FOLDER", 'http://localhost/delfos/assets/utils/');
// CONFIGURACAO DE ENVIO DE E-MAIL
define('EMAIL_NOME', 'delfos');
define('EMAIL_ENDERECO', 'suporte@delfos.com.br');
define('EMAIL_TITULO', nl2br('ZATU - Administração - Prefeitura de Tarauacá-AC'));
define('EMAIL_DESENVOLVIMENTO', nl2br('ZATU - SISTEMA DE ATUALIZAÇÃO CADASTRAL'));
define('URL_ENDERECO', 'http://localhost');
// ADICIONAR CLASSE DE CONEXÃO
include_once ("Conexao.class.php");
?>