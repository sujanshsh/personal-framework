<?php

/* 
 * Application Startup
 */

function __autoload($name) {
    $name = str_replace('\\','/',$name);
    include_once('../library/classes/'.$name.'.php');
}

function custom_error_handler( $errno ,  $errstr ,  $errfile ,  $errline) {
    
    echo '<div style="white-space: pre; width: 100%; border-bottom: 1px solid #777">';
    switch ($errno) {
        case E_USER_WARNING:
            echo "E_USER_WARNING"; break;
        case E_USER_NOTICE:
            echo "E_USER_NOTICE"; break;
        case E_WARNING:
            echo "E_WARNING"; break;
        case E_CORE_WARNING:
            echo "E_CORE_WARNING"; break;
        case E_COMPILE_WARNING:
            echo "E_COMPILE_WARNING"; break;
        case E_NOTICE:
            echo "E_NOTICE"; break;
        case E_ERROR:
            echo "E_ERROR"; break;
        case E_PARSE:
            echo "E_PARSE"; break;
        case E_CORE_ERROR:
            echo "E_CORE_ERROR"; break;
        case E_COMPILE_ERROR:
            echo "E_COMPILE_ERROR"; break;
        case E_USER_ERROR:
            echo "E_USER_ERROR"; break;   
        default:
            echo "(unknown error)"; break;
    }

    echo '<br />';
    echo '<strong>'.$errstr.'</strong>'.'<br />';
    echo 'File:&nbsp;'.$errfile.'<br />';
    echo 'Line:&nbsp;'.$errline.'<br />';
    echo '</div>';
    
    return true;
}

set_error_handler('custom_error_handler',E_ALL|E_STRICT);

include __DIR__.'/../library/safe_read.php';
include __DIR__.'/../library/path_info.php';

$pathInfo = new PathInfo();
$pathInfo->update();

// load some libraries

// use Url to define adaptive path constants
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off')
    $global_protocol =  'https';
else
    $global_protocol = 'http';

$global_port = $_SERVER['SERVER_PORT'];

if($global_port!='80') {
    $global_port_str = ':'.$global_port;
} else {
    $global_port_str = '';
}

define('SITE_PROTOCOL',$global_protocol);
define('SITE_NAME',$_SERVER['HTTP_HOST']);


$global_app_dir = str_replace('\\','/',__DIR__);
// site directory = www.site.com/site/directory/, includes / e.g /site
$global_site_directory = str_replace($_SERVER['DOCUMENT_ROOT'],'',$global_app_dir);

$global_base_path = $global_protocol.'://'.SITE_NAME.$global_port_str.$global_site_directory;
$global_base_app_url = $global_base_path.'/index.php';


define('BASE_URL',$global_base_app_url);
define('BASE_PATH',$global_base_path);


$application_started = true;

// for old html or php files directly required:

// following file contains checking of session as per requirement
// session has to be started by it
if(file_exists('session_check.php'))
    include 'session_check.php';

if($pathInfo->number_of_paths==0) {
    // load home controller
    if(file_exists(__DIR__.'/../controllers/index.php'))
        include __DIR__.'/../controllers/index.php';
}
else if($pathInfo->number_of_paths==1) {
    $global_ctrl_filename = str_replace('.php','',$pathInfo->path_info_array[1]);
    $global_ctrl_file = __DIR__.'/../controllers/'.$global_ctrl_filename.'.php';
    if(file_exists($global_ctrl_file))
        include $global_ctrl_file;
    else
        include __DIR__.'/../responses/404.php';
}
else if($pathInfo->number_of_paths>=2) {
    $global_ctrl_filename = str_replace('.php','',$pathInfo->path_info_array[2]);
    $global_ctrl_file = __DIR__.'/../controllers/'.$pathInfo->path_info_array[1].'/'.
                    $global_ctrl_filename.'.php';
    if(file_exists($global_ctrl_file))
        include $global_ctrl_file;
    else
        include __DIR__.'/../responses/404.php';
}



