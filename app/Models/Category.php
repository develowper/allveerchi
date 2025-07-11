<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'status',
        'parent_id',
        'children',
    ];
    protected $casts = [
        'children' => 'array',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id');
    }

    public function videos()
    {
        return $this->hasMany(Video::class, 'category_id');
    }

    public function podcasts()
    {
        return $this->hasMany(Podcast::class, 'category_id');
    }

    public function banners()
    {
        return $this->hasMany(Banner::class, 'category_id');
    }

    public function children()
    {
        return $this->hasMany(\App\Models\Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(\App\Models\Category::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    function getCategoryTree($parent_id = 0, $spacing = '', $tree_array = array())
    {
        $categories = Category::select('id', 'name', 'parent_id')->where('parent_id', '=', $parent_id)->orderBy('parent_id')->get();
        foreach ($categories as $item) {
            $tree_array[] = ['categoryId' => $item->id, 'categoryName' => $spacing . $item->name];
            $tree_array = $this->getCategoryTree($item->id, $spacing . '--', $tree_array);
        }
        return $tree_array;
    }

    public function hasChildren()
    {
        if ($this->children->count()) {
            return true;
        }

        return false;
    }

    public function findDescendants(Category $category)
    {
        $this->descendants[] = $category->id;

        if ($category->hasChildren()) {
            foreach ($category->children as $child) {
                $this->findDescendants($child);
            }
        }
    }

    public function getDescendants(Category $category)
    {
        $this->findDescendants($category);
        return $this->descendants;
    }

    protected static function getChildren($item, $data)
    {
        $children = $data->where('parent_id', $item->id);
        foreach ($children as $item) {
            $item->checked = $item->status == 'active';
            $item->children = self::getChildren($item, $data);
            $ids = array_column($item->children, 'id');
            $data = $data->whereNotIn('id', $ids);
        }
        return $children->values();
    }

    public static function getTree()
    {
        $data = Category::select('id', 'name', 'parent_id', 'level', 'status', 'children')->get();


//
        foreach ($data as $item) {
            $item->checked = $item->status == 'active';
            $item->children = self::getChildren($item, $data);
            $ids = array_column($item->children, 'id');
            $data = $data->whereNotIn('id', $ids);

        }

        return $data->values();
    }
}
