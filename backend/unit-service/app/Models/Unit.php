<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nama_unit',
        'kode_unit',
        'created_at',
    ];
}
