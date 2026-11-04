<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conteo extends Model
{
    use SoftDeletes;
    protected $table = 'conteo';
    protected $fillable = ['boleta_id'];
}
