<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Convocatoria extends Eloquent implements UserInterface, RemindableInterface
{
    use UserTrait, RemindableTrait, SoftDeletingTrait;
    protected $table = 'convocatoria';
    protected $fillable = ['nombre','nombre_sindicato','fecha_registro_validacion_inicio','fecha_registro_validacion_fin','fecha_consulta_inicio',
                            'fecha_consulta_fin','convocatoria_activa','convocatoria_estatus_id', 'numero_autorizadores_minimo',
                                'aprobacion_confirmacion_contrato', 'seleccionar_dos_cargos'];

    private static $validation_messages = [
        'nombre.required'                                       => 'El campo de nombre es requerido.',
        'nombre.between'                                        => 'El campo de nombre debe tener máximo 300 caracteres.',
        'nombre_sindicato.required'                             => 'El campo de nombre es requerido.',
        'nombre_sindicato.between'                              => 'El campo de nombre del sindicato debe tener máximo 200 caracteres.',
        'fecha_registro_validacion_inicio.required'             => 'El campo de fecha de inicio de registro/validación es requerido.',
        'fecha_registro_validacion_inicio.date_format'          => 'El campo de fecha de inicio de registro/validación debe tener formato YYYY-mm-dd.',
        'fecha_registro_validacion_fin.required'                => 'El campo de fecha de fin de registro/validación es requerido.',
        'fecha_registro_validacion_fin.date_format'             => 'El campo de fecha de fin de registro/validación debe tener formato YYYY-mm-dd.',
        'fecha_consulta_inicio.required'                        => 'El campo de inicio de la consulta es requerido.',
        'fecha_consulta_inicio.date_format'                     => 'El campo de inicio de la consulta debe tener formato YYYY-mm-dd.',
        'fecha_consulta_fin.required'                           => 'El campo de fin de la consulta es requerido.',
        'fecha_consulta_fin.date_format'                        => 'El campo de fin de la consulta debe tener formato YYYY-mm-dd.',
        'numero_autorizadores_minimo.integer'                   => 'El campo de número de autorizadores debe ser un número entero.',
        'numero_autorizadores_minimo.min'                       => 'El campo de número de autorizadores debe ser mayor a 1.',
        'numero_autorizadores_minimo.required'                  => 'El campo de número de autorizadores es requerido.',
        'numero_autorizadores_minimo.max'                       => 'El campo de número de autorizadores debe ser menor a 10.',
    ];

    public static function validarConvocatoria($data, $id){
        $rules = [
            'nombre'                                            => 'required|between:1,300',
            'nombre_sindicato'                                  => 'required|between:1,200',
            'fecha_registro_validacion_inicio'                  => 'required|date_format:Y-m-d',
            'fecha_registro_validacion_fin'                     => 'required|date_format:Y-m-d',
            'fecha_consulta_inicio'                             => 'required|date_format:Y-m-d',
            'fecha_consulta_fin'                                => 'required|date_format:Y-m-d',
            'numero_autorizadores_minimo'                       => 'required|integer|min:1|max:10',
        ];
        
        return Validator::make($data, $rules, self::$validation_messages);
    }
    
    public static function validarTerminarConvocatoria($data, $id){
        $rules = [
            'razon_termino'                                     => 'required|between:1,200',
        ];
        
        return Validator::make($data, $rules, self::$validation_messages);
    }
}
