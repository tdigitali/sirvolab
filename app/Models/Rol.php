<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;

class Rol extends Model
{
    use SoftDeletes;
    protected $table = 'rol';
    protected $fillable = ['nombre'];

        private static $validation_messages = [
            'nombre.required'           => 'El campo de nombre es requerido.'
    ];

        public static function validateUserProfile($data, $id){
            $rules = [
                'nombre'                => 'required|between:1,255'
            ];
            return Validator::make($data, $rules, self::$validation_messages);
    }
}
