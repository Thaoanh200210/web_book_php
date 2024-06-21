<?php
// // Define variable BASE_URL_PATH as root path
define('BASE_URL_PATH', "/");
define('VIEWS_DIR', __DIR__."\\views\\");

// Add Psr4 autoloader namespace
require_once __DIR__."/libraries/Psr4AutoloaderClass.php";

$loader = new Psr4AutoloaderClass;
$loader->register();

$loader->addNamespace("Project\src", __DIR__."/src");
$loader->addNamespace("Project\views", __DIR__."/views");
$loader->addNamespace("Project\model", __DIR__."/model");
$loader->addNamespace("Project\controllers", __DIR__."/controllers");

// Connect to db
try{
    $db = new Project\src\ConnectDB;
    $db = $db->connect([
        "dbhost"=> "localhost",
        "dbname"=> "Project_ct275",
        "dbuser"=> "root",
        "dbpassword"=> ""
    ]);
}
catch (Exception $e) {
    echo "Không thể kết nối đến MYSQL
          <br>Vui lòng kiểm tra lại username/password đến MYSQL<\br>";
    exit("<pre>{$e}<pre>");
}

// start session
session_start();

// add Model
use Project\model\BookModel;
use Project\model\UserModel;
use Project\model\AuthorModel;
use Project\model\TypeModel;

$userModel = new UserModel;
$bookModel = new BookModel;
$authorModel = new AuthorModel;
$typeModel  = new TypeModel;
global $userModel;
global $bookModel;
global $authorModel;
global $typeModel;