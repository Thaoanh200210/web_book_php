<?php

$router->get(
    "/signup",
    "Project\controllers\UserController@signUpForm"
);
$router->get(
    "/login",
    "Project\controllers\UserController@logInForm"
);
$router->get(
    "/changepass",
    "Project\controllers\UserController@changePasswordForm"
);

$router->get(
    "/user",
    "Project\controllers\UserController@showuser"
);

$router->post(
    "/signup",
    "Project\controllers\UserController@signUpProcess"
);

$router->post(
    "/login",
    "Project\controllers\UserController@logInProcess"
);
$router->post(
    "/changepass",
    "Project\controllers\UserController@changePassProcess"
);
$router->get(
    "/logout",
    "Project\controllers\UserController@logOutProcess"
);

$router->get(
    "/userTable",
    "Project\controllers\UserController@userTable"
);