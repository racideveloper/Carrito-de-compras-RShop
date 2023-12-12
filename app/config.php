<?php
/**
 * Inicialización de sesión de usuario
 */
session_start();

/**
 * URL constante
 */
define('PORT'     , '8848');
define('BASEPATH' , '/ProyectoCarrito/');
define('URL'      , 'http://127.0.0.1:'.PORT.BASEPATH);

/**
 * constantes para los paths de archivos
 */
define('DS'       , DIRECTORY_SEPARATOR);
define('ROOT'     , getcwd().DS);
define('APP'      , ROOT.'app'.DS);
define('INCLUDES' , ROOT.'includes'.DS);
define('VIEWS'    , ROOT.'views'.DS);

define('ASSETS'   , URL.'assets/');
define('CSS'      , ASSETS.'css/');
define('IMAGES'   , ASSETS.'images/');
define('JS'       , ASSETS.'js/');
define('PLUGINS'  , ASSETS.'plugins/');

/**
 * Constantes adicionales
 */
define('SHIPPING_COST' , 50.00);

/**
 * incluir todas nuestras funciones personalizadas
 */
require_once APP.'functions.php';