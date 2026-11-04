<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class BoletaCandidato extends Eloquent implements UserInterface, RemindableInterface
{
    use UserTrait, RemindableTrait, SoftDeletingTrait;
    protected $table = 'boleta_candidato';
    protected $fillable = ['curp','nombre_completo','planilla_color','boleta_id'];

    private static $validation_messages = [
        'curp.required'                                    => 'El campo de CURP es requerido.',
        'curp.between'                                     => 'El campo de CURP debe tener 18 caracteres.',
        'planilla_color.required'                          => 'El campo de color de planilla es requerido.',
        'boleta_id.required'                               => 'El campo de boleta es requerido.',
        'boleta_id.exists'                                 => 'Debe seleccionar una boleta válida.',
        'nombre_completo.required'                         => 'El campo de Nombre completo es requerido.',
        'nombre_completo.between'                          => 'El campo de Nombre completo debe tener 200 caracteres.',
    ];

    public static function validarBoletaCandidato($data){
        $rules = [
            'curp'                                         => 'required|between:1,18',
            'nombre_completo'                              => 'required|between:1,200',
            'planilla_color'                               => 'required',
        ];
        
        return Validator::make($data, $rules, self::$validation_messages);
    }
}
