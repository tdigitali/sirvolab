<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class ConvocatoriaPadron extends Eloquent implements UserInterface, RemindableInterface
{
    use UserTrait, RemindableTrait, SoftDeletingTrait;
    protected $table = 'convocatoria_padron';
    protected $fillable = ['curp','nombre_completo','centro_trabajo_id','entidad_federativa_id','convocatoria_id',
                            'fecha_carga','fecha_registro','voto_emitido'];

    private static $validation_messages = [
        'curp.required'                                        => 'El campo de curp es requerido.',
        'curp.between'                                         => 'El campo de curp debe tener 18 caracteres.',
        'nombre_completo.required'                             => 'El campo de nombre completo es requerido.',
        'nombre_completo.between'                              => 'El campo de nombre del sindicato debe tener máximo 200 caracteres.',
        'centro_trabajo_id.required'                           => 'El campo de centro de trabajo es requerido.',
        'centro_trabajo_id.exists'                             => 'El campo de centro de trabajo debe ser seleccionado del catálogo.',
        'entidad_federativa_id.required'                       => 'El campo de entidad federativa es requerido.',
        'entidad_federativa_id.exists'                         => 'El campo de entidad federativa debe ser seleccionad del catálogo.',
        'correo_electronico.required'                          => 'El campo de correo electrónico es requerido.',
        'correo_electronico.email'                             => 'El campo de correo electrónico debe tener un formato válido.',
        'correo_electronico.between'                           => 'El campo de correo electrónico debe tener máximo 60 caracteres.',
    ];

    public static function validarConvocatoriaPadron($data){
        $rules = [
            'correo_electronico'                             => 'required|between:1,100|email'
        ];
        
        return Validator::make($data, $rules, self::$validation_messages);
    }
}
