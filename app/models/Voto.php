<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Voto extends Eloquent implements UserInterface, RemindableInterface{
    use UserTrait, RemindableTrait, SoftDeletingTrait;
    protected $table = 'voto';
    protected $fillable = ['convocatoria_padron_id', 'boleta_id'];

    private static $validation_messages = [
        'convocatoria_padron_id.required'          => 'El campo de miembro del padrón es requerido.',
        'convocatoria_padron_id.exists'            => 'Debe de ser un miembro válido del padrón.',
        'boleta_id.required'                       => 'El campo de boleta es requerido.',
        'boleta_id.exists'                         => 'Debe seleccionar una boleta válida.'
    ];

    public static function validarUsuario($data){
        $rules = [
            'convocatoria_padron_id'               => 'required|exists:convocatoria_padron,id',
            'boleta_id'                            => 'required|exists:boleta,id'
        ];
        
        return Validator::make($data, $rules, self::$validation_messages);
    }
}
