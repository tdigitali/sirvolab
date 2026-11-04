<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Usuario extends Eloquent implements UserInterface, RemindableInterface{
    use UserTrait, RemindableTrait, SoftDeletingTrait;
    protected $table = 'usuario';
    protected $fillable = ['nombre', 'apellido_paterno', 'apellido_materno', 'rol_id', 'enabled', 'cambio_contrasena', 'password', 'email', 
        'enabled', 'default_password', 'curp', 'correo_electronico_notificaciones'];
    protected $hidden = array('password', 'remember_token');

    private static $validation_messages = [
        'nombre.required'                                    => 'El campo de nombre es requerido.',
        'nombre.between'                                     => 'El campo de nombre debe tener máximo 100 caracteres.',
        'apellido_paterno.required'                          => 'El campo de apellido paterno es requerido.',
        'apellido_paterno.between'                           => 'El campo de apellido paterno debe ser máximo 100 caracteres.',
        'apellido_materno.between'                           => 'El campo de apellido materno debe ser máximo 100 caracteres.',
        'rol_id.required'                                    => 'El campo de rol es requerido.',
        'rol_id.exists'                                      => 'Debe seleccionar un rol válido.',
        'email.required'                                     => 'El campo de correo electrónico es requerido.',
        'email.unique'                                       => 'El campo de correo electrónico ya se encuentra ocupado.',
        'email.email'                                        => 'El campo de correo electrónico debe tener un formato válido.',
        'email.between'                                      => 'El campo de correo electrónico debe tener máximo 60 caracteres.',
        'password.required'                                  => 'El campo de contraseña es requerido.',
        'password_confirmation.required'                     => 'El campo de confirmar contraseña es requerido.',
        'password.between'                                   => 'El campo de contraseña debe ser mayor a 8 y menor a 16 caracteres.',
        'password_confirmation.between'                      => 'El campo de confirmar contraseña debe ser mayor a 8 y menor a 16 caracteres.',
        'curp.required'                                      => 'El campo de CURP es requerido.',
        'curp.between'                                       => 'El campo de CURP debe tener 18 caracteres.',
        'correo_electronico_notificaciones.required'         => 'El campo de correo electrónico para notificaciones es requerido.',
        'correo_electronico_notificaciones.between'          => 'El campo de correo electrónico para notificaciones debe ser máximo 60 caracteres.',
    ];

    public static function validarUsuario($data){
        $rules = [
            'nombre'                            => 'required|between:1,50',
            'apellido_paterno'                  => 'required|between:1,50',
            'apellido_materno'                  => 'between:1,50',
            'rol_id'                            => 'required|exists:rol,id',
            'email'                             => 'required|between:1,60|email|unique:usuario,email'
        ];
        
        return Validator::make($data, $rules, self::$validation_messages);
    }
    
    public static function validarUsuarioUpdate($data){
        $rules = [
            'nombre'                            => 'required|between:1,50',
            'apellido_paterno'                  => 'required|between:1,50',
            'apellido_materno'                  => 'between:1,50',
            'rol_id'                            => 'required|exists:rol,id',
        ];
        
        return Validator::make($data, $rules, self::$validation_messages);
    }
    
    public static function validarUsuarioCorto($data){
        $rules = [
            'nombre'                            => 'required|between:1,50',
            'apellido_paterno'                  => 'required|between:1,50',
            'apellido_materno'                  => 'between:1,50',
            'email'                             => 'required|between:1,60|email|unique:usuario,email'
        ];
        
        return Validator::make($data, $rules, self::$validation_messages);
    }
    
    public static function validarComite($data){
        $rules = [
            'curp'                              => 'required|between:1,18',
            'rol_id'                            => 'required|exists:rol,id',
            'email'                             => 'required|between:1,60',
            'correo_electronico_notificaciones' => 'required|between:1,60|email'
        ];
        
        return Validator::make($data, $rules, self::$validation_messages);
    }
    
    public static function validarUsuarioPassword($data){
        $rules = [
            'password'                         => 'required|between:8,16'
        ];
        return Validator::make($data, $rules, self::$validation_messages);
    }
}
