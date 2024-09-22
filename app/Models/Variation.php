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

    public static function makeBarcode($id, mixed $produced_at, mixed $guarantee_months)
    {
        $seperated = explode('/', $produced_at);
        $divideToDay = 1;
        foreach ($seperated as $idx => $item) {
            $seperated[$idx] = str_pad($item, 2, "0", STR_PAD_LEFT);

        }
        $produced_at = join('', $seperated);
        $guarantee_months = str_pad($guarantee_months, 2, "0", STR_PAD_LEFT);
        $res = "$id$produced_at$guarantee_months";

        $checksum = self::getChecksum($res);
        return "$res$checksum";
    }

    public static function validateBarcode($value)
    {
        if (!$value || strlen("$value") < 11) return false;
        $valueChecksum = substr($value, -2);
        $checksum = self::getChecksum(substr($value, 0, strlen($value) - 2));
        return $valueChecksum == $checksum;
    }

    public static function getChecksum($value)
    {
        $checksum = 0;
        $day = intval(substr($value, -4, 2)) ?? 1;

        foreach (str_split($value) as $idx => $char) {
            $checksum += ($char * ($idx + 1));
        }
        $res = round(($checksum / $day) % 100);
        return str_pad($res, 2, "0", STR_PAD_LEFT);
    }

    public function repository()
    {
        return $this->belongsTo(Repository::class, 'repo_id');
    }
}
