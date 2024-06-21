<?php

namespace Project\controllers;

require "../vendor/autoload.php";

use Project\model\AuthorModel;
use Project\model\BookModel;
use Project\model\TypeModel;
use function Project\src\checkUploadFile;
use function Project\src\redirect;
use function Project\src\render_view_twig;

use Exception;

class BookController { 
    protected $bookModel;
    protected $typeModel;
    protected $authorModel;
    private $limit;

    public function __construct() {
        $this->bookModel = new BookModel;
        $this->authorModel = new AuthorModel;
        $this->typeModel = new TypeModel;
        $this->limit = 20;
    }
    private function arrayForm($data, $separator) {
        print_r($data);
        if(is_array($data))
            $string = trim(implode($separator, $data));
        else $string = $data;
        return explode($separator, $string);
    }

    public function filterBook($filter, $value, $currentpage = 0) {
        switch($filter){
            case "author": {
                $books = [
                    ...$this->bookModel->filterAuthor($value),
                 ];
                 break;
            }
            case "type":{
                $books = [
                    ...$this->bookModel->filterType($value),
                 ];
                 break;
            }
        }
        if(!empty($books)) {
            $pageNumber = ceil(count($books) / $this->limit);    
            $books = array_slice($books, $currentpage, $this->limit);
        }
        $this->showBook($currentpage, $pageNumber,$books, "fitler/".$filter."/".$value."/");
    }

    public function searchBook() {
        $search = $_REQUEST['search']; 
        $currentpage = isset($_REQUEST['currentpage'])? intval($_REQUEST['currentpage']):0;
        $message = "";
        $pageNumber = 0;    
        if(isset($search)){
            $books = [
                        ...$this->bookModel->findBook("name", $search),
            ];
            if(empty($books))
               $message = "Không tìm thấy sách bạn đã nhập";
            else {
                $pageNumber = ceil(count($books) / $this->limit);
                $books = array_slice($books, $currentpage, $this->limit);
            }

            $this->showBook($currentpage ,$pageNumber,$books, "search?search=".$search."&currentpage=",$message);
        }
        else redirect("/");
    }

    public function mainBook($currentpage = 0, $message = "") {
        $books = $this->bookModel->getPageBook($currentpage);
        $pageNumber =  ceil($this->bookModel->countAll() / $this->limit);
        $this->showBook($currentpage, $pageNumber,$books);
    }

    public function addBookForm() {
        render_view_twig("addBookForm.twig", []);
    }

    public function retrieveValue($arr, $reg) {
        $newArr = [];
        foreach($arr as $key=>$value) {
            if(!empty($value) && preg_match($reg, $key)){
                array_push($newArr, $value);
            }
        }
        return implode(" - ", $newArr);
    }

    public function addBookProcess() {
        $message = ''; 
        $success = false;

        $checkBookexisted = $this->bookModel->findBook('name', $_POST['name']); 
        if(!empty($checkBookexisted)) {
            $message = "Sách đã có trong thư viện";
        }   
        else {
            try{
                    $this->bookModel->addBook([
                        'name' => $_POST['name'],
                        'src' => "",
                        'quantity' => $_POST['quantity'],
                    ]);
                    
                    $id = $this->bookModel->getLastId();
                    
                    $types = explode(" - ", $this->retrieveValue($_POST, "/type-[0-9]/"));
                    $authors = explode(" - ", $this->retrieveValue($_POST, "/author-[0-9]/"));
            
                    $typesId = $this->typeModel->addType($types);
                    $authorsId = $this->authorModel->addAuthor($authors);
            
                    $this->bookModel->updateType($id, $typesId);
                    $this->bookModel->updateAuthor($id, $authorsId);
        
        
                    $imgSrc = "src/img/".$id.".jpg";
        
                    $tempFile = $_FILES["image"]['tmp_name'];
                    $upload = checkUploadFile($_FILES["image"]["name"], $tempFile);
                    if($upload) {
                        move_uploaded_file($tempFile, $imgSrc);
                        $this->bookModel->updateBook($id, ["src" => $imgSrc]);
        
                        $message = "Thêm sách thành công";
                        $success = true;
                    }
            }
            catch (Exception $e) {
                $message = "Đã có lỗi xảy ra. Hãy Thử lại";
            }
        }

        render_view_twig("addBookForm.twig", ['message'=> $message, 'success' => $success]);
    }   

    public function showBookTable() {
        $books = $this->bookModel->getAllBook();
        foreach($books as $index => $book) {
            $book["type"] = explode(" - ", $book["type"]);
            $book["author"] = explode(" - ",$book["author"]);
            $books[$index] = $book;
        }
        render_view_twig("bookTable.twig", ["books" => $books]);
    }

    public function updateForm($id) {
        $book = $this->bookModel->findId($id);
        $book['type'] = explode(" - ", $book['type']);
        $book['author'] = explode(" - ", $book['author']);

        render_view_twig("updateBookForm.twig", ["book" => $book]);
    }

    public function updateBookProcess(int $id) {
        try{
            $book = $this->bookModel->findId($id);
            if(file_exists($_FILES['image']['tmp_name'])){
                unlink($book["src"]);
    
                $tempFile = $_FILES["image"]['tmp_name'];
                $upload = checkUploadFile($_FILES["image"]["name"], $tempFile);
                if($upload) {
                    move_uploaded_file($tempFile, $book['src']);
                }
            }
    
            $this->bookModel->updateBook($id ,
                [
                    'name' => $_POST['name'],
                    'src' => $book['src'],
                    'quantity' => $_POST['quantity']
                ]);
            $types = explode(" - ", $this->retrieveValue($_POST, "/type-[0-9]/"));
            $authors = explode(" - ", $this->retrieveValue($_POST, "/author-[0-9]/"));

            $typesId = $this->typeModel->addType($types);
            $authorsId = $this->authorModel->addAuthor($authors);
            
            $this->bookModel->updateType($id, $typesId);
            $this->bookModel->updateAuthor($id, $authorsId);
    
            redirect("/booktable"); 
        }
        catch(Exception $e) {
            render_view_twig("updateBookForm.twig", ['message'=> "Có lỗi xảy ra.Hãy thử lại"]);
        }
    }

    public function deleteBookProcess($id) {
        $book = $this->bookModel->findId("$id");
        unlink($book['src']);

        $this->bookModel->deleteBook($id);
        redirect("/booktable");
    }

    public function showBook($currentpage = 0 , $pageNumber = 0, array $books = [], $urlPath = "page/",string $message = "") {
        $types =  $this->typeModel->getAllType();
        $authors =  $this->authorModel->getAllAuthor();

        render_view_twig("book.twig",
                        [
                            'books' =>$books, 
                            'types' => $types, 
                            'authors' => $authors,
                            'message' => $message,
                            'urlPath' => $urlPath,
                            'pageNumber' => $pageNumber,
                            'currentpage' => ($currentpage / $this->limit),
                            'limit' => $this->limit     
                        ]);
    }
}