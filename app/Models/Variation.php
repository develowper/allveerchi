<?php

namespace App\Models;

use App\Http\Helpers\Variable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Variation extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'category_id',
        'unit',
        'product_id',
        'grade',
        'pack_id',
        'repo_id',
        'in_repo',
        'in_shop',
        'agency_id',
        'agency_level',
        'price',
        'auction_price',
        'in_auction',
        'weight',
        'description',
        'min_allowed',
        'barcode',
        'admin_id',
        'guarantee_expires_at',
        'produced_at',
        'guarantee_months',
        'customer_id',
        'unit',
        'categories',
        'prices',
        'brand_id',
    ];

    protected $casts = [
        'in_auction' => 'boolean',
        'categories' => 'array',
        'prices' => 'array',
    ];

    public static function getImages($id)
    {

        $images = array_fill(0, Variable::VARIATION_IMAGE_LIMIT, null);
        if (!$id) return $images;
        $allFiles = Storage::allFiles("public/" . Variable::IMAGE_FOLDERS[Variation::class] . "/$id");
        foreach ($allFiles as $idx => $path) {
            $images[$idx] = route('storage.variations') . "/$id/" . basename($path, ""); //suffix=format
        }

        return $images;
    }


    public function repository()
    {
        return $this->belongsTo(Repository::class, 'repo_id');
    }
}
