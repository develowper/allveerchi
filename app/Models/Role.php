<?php

namespace App\Models;

use App\Http\Helpers\Variable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $fillable = [
        'agency_level',
        'name',
        'accesses',
    ];
    protected $casts = ['accesses' => 'array'];

    public static function getTree($selectedAccesses = [])
    {

        $res = [];

        foreach (Variable::ACCESSES as $model => $array) {

            $children = [];
            foreach ($array as $key => $access) {
                if (!is_array($access)) {
                    $children[] = ['text' => $access, 'checked' => in_array("$model:$access", $selectedAccesses) ? true : false, 'key' => "$model:$access", 'children' => []];
                } else {
                    $children[] = ['text' => $key, 'checked' => in_array("$model:$key", $selectedAccesses) ? true : false, 'key' => "$model:$key", 'children' => array_map(fn($e) => ['text' => $e, 'checked' => in_array("$model:$key:$e", $selectedAccesses) ? true : false, 'key' => "$model:$key:$e", 'children' => []/*, 'parent' => ['text' => $key, 'key' => "$model:$key"]*/], $access,)];
                }
            }
//            foreach ($children as &$child) {
//                $child['parent'] = ['text' => $model, 'key' => "$model"];
//            }
//            unset($child);
            $res[] = ['text' => $model, 'checked' => in_array("$model", $selectedAccesses) ? true : false, 'key' => "$model", 'children' => $children];
        }

        return $res;
    }
}
