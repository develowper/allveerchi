<?php

namespace App\Models;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Log extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'model',
        'from',
        'to',
    ];

    static function seed()
    {
        set_time_limit(0);
        $i = 0;
        Catalog::truncate();
        $res = Util::fromCSV(Storage::path('catalog.csv'));
        foreach ($res as $row) {
            $row['price'] = intVal($row['price'] ?? 0);
            $row['in_shop'] = intVal($row['in_shop'] ?? 0);
            $row['in_repo'] = intVal($row['in_repo'] ?? 0);
            Catalog::create($row);
            echo "$i created " . PHP_EOL;
            $i++;
        }
    }
}