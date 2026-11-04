<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class ConvocatoriaAutorizacion extends Eloquent implements UserInterface, RemindableInterface
{
    use UserTrait, RemindableTrait, SoftDeletingTrait;
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
