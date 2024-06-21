<?php  

$router->get(
    "/",
    "Project\controllers\BookController@mainBook"
);

$router->get(
    "/updatebook/{id}",
    "Project\controllers\BookController@updateForm"
);

$router->get(
    "/deletebook/{id}",
    "Project\controllers\BookController@deleteBookProcess"
);

$router->post(
    "/updatebook/{id}",
    "Project\controllers\BookController@updateBookProcess"
);

$router->get(
    "/filter/{filter}/{value}",
    "Project\controllers\BookController@filterBook"
);

$router->get(
    "/borrowsuccess",
    "Project\controllers\BookController@messageSuccess"
);

$router->get(
    "/borrowfail",
    "Project\controllers\BookController@messageFail"
);

$router->get(
    "/filter/{filter}/{value}/{currentpage}",
    "Project\controllers\BookController@filterBook"
);

$router->get(
    "/search",
    "Project\controllers\BookController@searchBook"
);

$router->get(
    "/addbook",
    "Project\controllers\BookController@addBookForm"
);

$router->post(
    "/addbook",
    "Project\controllers\BookController@addBookProcess"
);

$router->get(
    "/booktable",
    "Project\controllers\BookController@showBookTable"
);

$router->get(
    "/page/{currentpage}",
    "Project\controllers\BookController@mainBook"
);



