<?php
    require "../vendor/autoload.php";

    $router = new \Bramus\Router\Router();

    require "../router/borrowRouter.php";
    require "../router/userRouter.php";
    require "../router/bookRouter.php";
    require "../router/errorRouter.php";

    $router->run();





    