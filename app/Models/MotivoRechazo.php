<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class MotivoRechazo extends Eloquent implements UserInterface, RemindableInterface
{
	use UserTrait, RemindableTrait, SoftDeletingTrait;
	protected $table = 'motivo_rechazo';
	protected $fillable = ['nombre'];
        
        private static $validation_messages = [
            'nombre.required'           => 'El campo de nombre es requerido'
	];
        
        public static function validarMotivos($data, $id){
            $rules = [
                'nombre'                => 'required|between:1,255'
            ];
            return Validator::make($data, $rules, self::$validation_messages);
	}
}
