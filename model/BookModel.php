<?php
namespace Project\model;

require "../vendor/autoload.php";

use Illuminate\Database\Eloquent\Model;

class BookModel extends Model
{
    protected $table = 'books';
    public $timestamps = false;

    public function getPageBook($page)
    {
        $result = [];
        $books = BookModel::offset($page)->limit(20)->where("quantity", ">", 0)->get();
        foreach($books as $book) {
            $result[$book["id"]] = $this->fillBook($book);
        }
        return $result;
    }

    public function fillBook($book) {
        $type = $this->getAllTypeOf($book);
        $author = $this->getAllAuthorOf($book); 
        $book = $book->toArray();
        $book["type"] = implode(" - ", $type);
        $book["author"] = implode(" - ", $author);
        return $book;
    }

    public function countAll(){
        return BookModel::all()->count();
    }

    public function getAllBook() {
        $result = [];
        $books = BookModel::all();
        foreach($books as $book) {
            $result[$book["id"]] = $this->fillBook($book); 
        }
        return $result;
    }
    
    public function addBook(array $book){
       BookModel::insert($book);
    }

    public function findBook($filter, $value){
        $books =  BookModel::where($filter, 'regexp',$value)->get();
        $result = [];
        foreach($books as $book) {
            $result[$book["id"]] = $this->fillbook($book);
        }

        return $result;
    }
    public function types() {
        return $this->belongsToMany(TypeModel::class, "have","book_id", "type_id");
    }

    public function authors() {
        return $this->belongsToMany(AuthorModel::class, "write", "book_id", "author_id");
    }

    public function filterType($value) {
        $result = [];
        $books = BookModel::all();

        foreach($books as $book){
            foreach($book->types as $type)
                if($type["type"] == $value && !array_key_exists($book["id"], $result)) {
                    $result[$book["id"]] = $this->fillBook($book);
                }
        }
        return $result;
    }

    public function filterAuthor($value) {
        $result = [];
        $books = BookModel::all();

        foreach($books as $book){
            foreach($book->authors as $author)
                if($author["name"] == $value && !array_key_exists($book["id"], $result)) {
                    $result[$book["id"]] = $this->fillBook($book);
                }
        }
        return $result;
    }

    public function updateType(int $id, array $typesId) {
        $book = BookModel::find($id);
        $book->types()->detach();
        foreach($typesId as $typeId){
            $book->types()->attach($typeId);
        }
    }

    public function updateAuthor(int $id, array $authorsId) {
        $book = BookModel::find($id);
        $book->authors()->detach();
        foreach($authorsId as $authorId){
            $success = $book->authors()->attach($authorId);
            print_r($success);
        }
    }

    public function findId($id) {
        $book = BookModel::where('id', $id)->first();
        return $this->fillBook($book);
    }

    public function getLastId() {
        return BookModel::all()->last()->id;
    }
    
    public function decreaseQuantity($id) {
        return BookModel::where('id', $id)->decrement('quantity', 1);
    }

    public function increaseQuantity($id) {
        return BookModel::where('id', $id)->increment('quantity', 1);
    }

    public function getAllTypeOf($book) {
        $types = [];
        foreach($book->types as $index => $type) {
            $types[$index] = $type["type"]; 
        }
        return $types;
    }

    public function getAllAuthorOf($book) {
        $authors = [];
        foreach($book->authors as $author) {
            array_push($authors, $author["name"]);
        }
        return $authors;
    }

    public function updateBook(int $id, array $book){
        return BookModel::where('id', $id)->update($book);
    }

    public function deleteBook(int $id){
        return BookModel::find($id)->delete();
    }
}