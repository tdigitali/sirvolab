<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Esquema de SIRVOLAB INFERIDO a partir del código (columnas que leen y
 * escriben controladores y vistas). Solo se usa para las pruebas
 * automatizadas con SQLite en memoria.
 *
 * NO es el esquema oficial de producción y NO debe ejecutarse contra la base
 * de datos real: el proyecto original no incluía migraciones de estas
 * tablas (las de app/database/migrations pertenecían a otro sistema).
 */
return new class extends Migration
{
    public function up(): void
    {
        $catalogo = function (string $tabla, array $extra = []) {
            Schema::create($tabla, function (Blueprint $t) use ($extra) {
                $t->increments('id');
                $t->string('nombre')->nullable();
                foreach ($extra as $columna) {
                    $t->integer($columna)->nullable();
                }
                $t->timestamps();
                $t->softDeletes();
            });
        };

        $catalogo('ambiente');
        $catalogo('rol');
        $catalogo('convocatoria_estatus');
        $catalogo('entidad_federativa');
        $catalogo('motivo_rechazo');
        $catalogo('convocatoria_centro_trabajo', ['convocatoria_id']);

        Schema::create('usuario', function (Blueprint $t) {
            $t->increments('id');
            $t->string('nombre')->nullable();
            $t->string('apellido_paterno')->nullable();
            $t->string('apellido_materno')->nullable();
            $t->string('curp', 18)->nullable();
            $t->string('email')->nullable();
            $t->string('correo_electronico_notificaciones')->nullable();
            $t->string('password')->nullable();
            $t->text('metadata')->nullable();
            $t->string('verify_password')->nullable();
            $t->boolean('enabled')->nullable();
            $t->boolean('default_password')->nullable();
            $t->boolean('cambio_contrasena')->nullable();
            $t->integer('rol_id')->nullable();
            $t->integer('convocatoria_id')->nullable();
            $t->string('token_autorizacion')->nullable();
            $t->dateTime('vigencia_token_autorizacion')->nullable();
            $t->rememberToken();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('convocatoria', function (Blueprint $t) {
            $t->increments('id');
            $t->string('nombre', 300)->nullable();
            $t->string('nombre_sindicato', 200)->nullable();
            $t->dateTime('fecha_registro_validacion_inicio')->nullable();
            $t->dateTime('fecha_registro_validacion_fin')->nullable();
            $t->dateTime('fecha_consulta_inicio')->nullable();
            $t->dateTime('fecha_consulta_fin')->nullable();
            $t->dateTime('fecha_publicacion_resultados')->nullable();
            $t->boolean('convocatoria_activa')->nullable();
            $t->integer('convocatoria_estatus_id')->nullable();
            $t->integer('numero_autorizadores_minimo')->nullable();
            $t->boolean('aprobacion_confirmacion_contrato')->nullable();
            $t->boolean('seleccionar_dos_cargos')->nullable();
            $t->string('razon_termino', 200)->nullable();
            $t->string('ruta_acta')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('convocatoria_autorizaciones', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('usuario_id')->nullable();
            $t->integer('convocatoria_id')->nullable();
            $t->dateTime('fecha_autorizacion')->nullable();
            $t->integer('fase_autorizada')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('convocatoria_padron', function (Blueprint $t) {
            $t->increments('id');
            $t->string('curp', 18)->nullable();
            $t->string('nombre_completo', 200)->nullable();
            $t->string('correo_electronico')->nullable();
            $t->string('cadena_validacion')->nullable();
            $t->integer('centro_trabajo_id')->nullable();
            $t->integer('entidad_federativa_id')->nullable();
            $t->integer('convocatoria_id')->nullable();
            $t->integer('validador_id')->nullable();
            $t->integer('motivo_rechazo_id')->nullable();
            $t->dateTime('fecha_carga')->nullable();
            $t->dateTime('fecha_registro')->nullable();
            $t->dateTime('fecha_validacion')->nullable();
            $t->dateTime('fecha_rechazo')->nullable();
            $t->boolean('voto_emitido')->nullable();
            $t->string('ruta_ine_frente')->nullable();
            $t->string('ruta_ine_reverso')->nullable();
            $t->string('ruta_selfie_con_ine')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('convocatoria_padron_cargado', function (Blueprint $t) {
            $t->increments('id');
            $t->string('ruta')->nullable();
            $t->integer('convocatoria_id')->nullable();
            $t->dateTime('fecha_carga')->nullable();
            $t->integer('registros_cargados')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('boleta', function (Blueprint $t) {
            $t->increments('id');
            $t->string('cargo_postulacion', 200)->nullable();
            $t->integer('numero_boleta')->nullable();
            $t->integer('convocatoria_id')->nullable();
            $t->integer('nulos')->nullable();
            $t->string('ruta_acta')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('boleta_candidato', function (Blueprint $t) {
            $t->increments('id');
            $t->string('curp', 18)->nullable();
            $t->string('nombre_completo', 200)->nullable();
            $t->string('planilla_color')->nullable();
            $t->integer('boleta_id')->nullable();
            $t->integer('convocatoria_id')->nullable();
            $t->integer('conteo')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('voto', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('convocatoria_padron_id')->nullable();
            $t->integer('boleta_id')->nullable();
            $t->integer('convocatoria_id')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('conteo', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('boleta_id')->nullable();
            $t->integer('convocatoria_id')->nullable();
            $t->integer('boleta_candidato_id')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('bitacora', function (Blueprint $t) {
            $t->increments('id');
            $t->string('accion_realizada')->nullable();
            $t->dateTime('fecha_accion')->nullable();
            $t->integer('accion_realizada_id')->nullable();
            $t->integer('modulo_id')->nullable();
            $t->integer('usuario_id')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
    }
};
