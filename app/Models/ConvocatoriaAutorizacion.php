<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class ConvocatoriaAutorizacion extends Model
{
    use SoftDeletes;
    protected $table = 'convocatoria_autorizaciones';
    protected $fillable = ['usuario_id', 'convocatoria_id', 'fecha_autorizacion', 'fase_autorizada'];

    private static $validation_messages = [

    ];

    public static function validarConvocatoriaAutorizacion($data, $id){
        $rules = [

        ];

        return Validator::make($data, $rules, self::$validation_messages);
    }
}
