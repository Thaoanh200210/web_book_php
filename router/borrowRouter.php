<?php  

$router->get(
    "/borrow",
    "Project\controllers\BorrowController@showBorrow"
);
$router->get(
    "/borrow/{bookid}",
    "Project\controllers\BorrowController@borrowProcess"
);

$router->get(
    "/returnbook",
    "Project\controllers\BorrowController@returnBook"
);

$router->get(
    "/returnbook/{username}/{book}",
    "Project\controllers\BorrowController@returnBookProcess"
);