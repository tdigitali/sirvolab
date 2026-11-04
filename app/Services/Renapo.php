<?php

namespace App\Services;

use Exception;

/**
 * Consulta de CURP contra el servicio RENAPO de la STPS.
 *
 * Antes este código estaba duplicado en ConvocatoriaController y
 * BoletaController, con la URL y el token escritos en el código. Se
 * conservan exactamente los mismos códigos de retorno:
 *   "1"   CURP dada de baja (BSU)
 *   "2"   CURP dada de baja por defunción (BD)
 *   "3"   Operación NO EXITOSA
 *   "4"   Sin respuesta / respuesta inválida
 *   "5"   Error en la petición
 *   array Datos de la persona (nombres, apellido1, apellido2, ...)
 */
class Renapo
{
    /** Respuesta simulada para pruebas automatizadas (ver fake()). */
    protected static ?\Closure $fake = null;

    /**
     * Permite simular RENAPO en las pruebas: el callback recibe la CURP y
     * regresa el arreglo que respondería el servicio (o null).
     */
    public static function fake(?callable $respuesta): void
    {
        static::$fake = $respuesta ? \Closure::fromCallable($respuesta) : null;
    }

    /**
     * Ejecuta la petición y regresa la respuesta decodificada (o null).
     */
    public static function consultar(string $curp): ?array
    {
        if (static::$fake) {
            return (static::$fake)($curp);
        }

        $param = [
            'consultaCurpProfedet' => config('services.renapo.token'),
            'curp' => $curp,
        ];

        $datap = curl_init();
        curl_setopt($datap, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($datap, CURLOPT_URL, (string) config('services.renapo.url'));
        curl_setopt($datap, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($datap, CURLOPT_POST, true);
        curl_setopt($datap, CURLOPT_POSTFIELDS, json_encode($param));

        if ($timeout = (int) config('services.renapo.timeout')) {
            curl_setopt($datap, CURLOPT_TIMEOUT, $timeout);
        }

        $res = @curl_exec($datap);
        curl_close($datap);

        $renapo = is_string($res) ? json_decode($res, true) : null;

        return is_array($renapo) ? $renapo : null;
    }

    /**
     * Misma lógica que el antiguo validaCURP() de los controladores.
     */
    public static function validaCURP(string $curp): array|string
    {
        try {
            $renapo = static::consultar($curp);
        } catch (Exception $e) {
            return '5';
        }

        if ($renapo) {
            $statusCurp = $renapo['statusCurp'] ?? null;

            if ($statusCurp == 'BD' || $statusCurp == 'BSU') {
                return ($statusCurp == 'BSU') ? '1' : '2';
            }
            if (($renapo['statusOper'] ?? null) == 'NO EXITOSO') {
                return '3';
            }
        } else {
            return '4';
        }

        return $renapo;
    }
}
