<?php
    namespace Project\src;
    require "../vendor/autoload.php";

    use Illuminate\Database\Capsule\Manager as Manager ;

    class ConnectDB {
        public function connect(array $config) {
            [
                'dbhost' => $dbhost,
                'dbname' => $dbname,
                'dbuser' => $dbuser,
                'dbpassword' => $dbpassword
            ] = $config;

            $db = new Manager;
            $db->addConnection([
                'driver' => 'mysql',
                'host' => $dbhost,
                'database' => $dbname,
                'username' => $dbuser,
                'charset' => 'utf8',
                'password' => $dbpassword,
                'prefix' => ''
            ]);
            $db->setAsGlobal();
            $db->bootEloquent();
            return $db;
        }
    }