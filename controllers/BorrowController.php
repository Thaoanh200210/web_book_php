<?php

namespace Project\controllers;

require "../vendor/autoload.php";

use Project\model\BookModel;
use Project\model\UserModel;
use function Project\src\render_view_twig;
use function Project\src\redirect;

class BorrowController
{
    private $dbUser;
    private $dbBook;

    function __construct()
    {
        $this->dbUser = new UserModel;
        $this->dbBook = new BookModel;
    }

    public function showBorrow() {
        $books = $this->dbUser->getUserBorrow($_SESSION['username']);
        
        foreach($books as $index=>$book) {
            $id = $book['book_id'];
            $bookRow = $this->dbBook->findId($id);
            
            $books[$index]['name'] = $bookRow['name'];
        }

        render_view_twig("borrow.twig", ["books" => $books]);
    }

    public function returnBook() {
        $borrows = $this->dbUser->getAllBorrow();
        render_view_twig("returnBook.twig", ['borrows' => $borrows]);
    }

    public function returnBookProcess($username, $bookId) {
        $this->dbUser->deleteBorrow($username, $bookId);
        $this->dbBook->increaseQuantity($bookId);
        redirect("/returnbook");
    }

    public function borrowProcess($bookid) {
        if(isset($_SESSION['username'])){
            $username = $_SESSION['username'];

            if(!$this->dbUser->checkBookBorrowed($username, $bookid)){
                $result = $this->dbUser->createBorrow($username,$bookid);
                
                if(isset($result)) {
                    $this->dbBook->decreaseQuantity($bookid);
                    echo "success";
                }
            }
            else{
                echo "borrowed";
            }

        }
        else echo "unlogin";
    }
}
