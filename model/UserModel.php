<?php
namespace Project\model;

require "../vendor/autoload.php";

use Exception;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'users';
    public $timestamps = false;

    public function create($user)
    {
        return UserModel::insert($user);
    }                                                                             

    public function getAllUser() {
        return UserModel::all()->toArray();
    }

    public function getAllUserBorrow()
    {
        $users = UserModel::where("username", "!=", "thuthu")->get();
        return $users;
    }

    public function findByUsername($username)
    {
        return UserModel::where('username', "=", $username)->first();
    }

    public function updatePassword($username, $password)
    {
        return UserModel::where("username", "=", $username)->update([
            'password' => $password
        ]);
    }

    public function books() {
        return $this->belongsToMany(BookModel::class, 'borrow', 'user_id', 'book_id')->withPivot('borrow_at', 'borrow_end');
    }

    public function getUserBorrow($username) {
        $user = $this->findByUsername($username);
        $bookArr = [];
        foreach ($user->books as $book) {
            array_push($bookArr, $book->pivot->toArray());
        }
        return $bookArr;
    }

    public function createBorrow($username, $bookid) {
        $user = $this->findByUsername($username);
        try {
            $user->books()->attach($bookid);
            return $bookid;
        }
        catch(Exception $e) {
            return null;
        }
    }

    public function getAllBorrow() {
        $users = $this->getAllUserBorrow();
        $borrows = [];
        foreach ($users as $user) {
            $borrow = ["username" => $user->username, "books" => []];

            foreach ($user->books as $index => $book) {
                array_push($borrow['books'], [
                    "bookId" => $book->id,
                    "bookName" => $book->name,
                    "borrowAt" => $book->pivot->borrow_at,
                    "borrowEnd" => $book->pivot->borrow_end
                ]);
            }
            
            array_push($borrows, $borrow);
        }
        return $borrows;
    }

    public function deleteBorrow($username, $bookId) {
        $user = $this->findByUsername($username);
        $user->books()->wherePivot("book_id", $bookId)->detach();
    }

    public function checkBookBorrowed($username, $bookid) {
        $user = $this->findByUsername($username);
        foreach ($user->books as $book) {
            if($book->pivot->book_id == $bookid) 
            return true;
        }
        return false;
    }
}