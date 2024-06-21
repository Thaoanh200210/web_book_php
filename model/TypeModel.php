<?php 

namespace Project\model;

require "../vendor/autoload.php";

use Illuminate\Database\Eloquent\Model;

class TypeModel extends Model
{
    protected $table = 'types';

    public function getAllType() {
        return TypeModel::all("type")->toArray();
    }    

    public function addType(array $types) {
        $typesId = [];
        foreach($types as $type){
                $isExist = TypeModel::where("type", "like", $type)->get()->count();
                if($isExist == 0){
                    $newId = TypeModel::all()->last()->id + 1;
                    TypeModel::insert(["id" => $newId,"type" => $type]);
                }
                array_push($typesId, TypeModel::where("type", "like", $type)->first()->id);
            }
        return $typesId;
    }
}