<?php 

namespace Project\model;

require "../vendor/autoload.php";

use Exception;
use Illuminate\Database\Eloquent\Model;

class AuthorModel extends Model
{
    protected $table = 'authors';

    public function getAllAuthor() {
        return AuthorModel::all()->toArray();
    }

    public function addAuthor(array $authors) {
        $authorsId = [];
        foreach($authors as $author){
            $isExist = AuthorModel::where("name", "like", $author)->get()->count();
            if($isExist == 0){
                $newId = AuthorModel::all()->last()->id + 1;
                AuthorModel::insert(["id" => $newId,"name" => $author]);
            }
            array_push($authorsId, AuthorModel::where("name", "like", $author)->first()->id);
        }

        return $authorsId;
    }
}