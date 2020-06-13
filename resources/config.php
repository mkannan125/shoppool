<?php

ob_start();

session_start();

defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);

defined("TEMPLATE_FRONT") ? null : define("TEMPLATE_FRONT", __DIR__ . DS . "templates/front");

defined("TEMPLATE_BACK") ? null : define("TEMPLATE_BACK", __DIR__ . DS . "templates/back");

defined("UPLOAD_DIRECTORY") ? null : define("UPLOAD_DIRECTORY", __DIR__ . DS . "uploads");
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
defined("DB_HOST") ? null : define("DB_HOST", $cleardb_url['host']);
defined("DB_USER") ? null : define("DB_USER", $cleardb_url['user']);
defined("DB_PASS") ? null : define("DB_PASS", $cleardb_url['pass']);
defined("DB_NAME") ? null : define("DB_NAME", substr($cleardb_url["path"], 1));


$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
//$connection = mysqli_connect('localhost', 'root', '', 'ecom_db');
require_once("functions.php");
require_once("cart.php");

require_once (__DIR__ . "/vendor/phpmailer/phpmailer/src/PHPMailer.php");
require_once (__DIR__ . "/vendor/phpmailer/phpmailer/src/SMTP.php");
require_once (__DIR__ . "/vendor/phpmailer/phpmailer/src/Exception.php");


?>
