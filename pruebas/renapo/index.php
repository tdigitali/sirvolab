<?php
/*
 * Simulador del servicio de CURP (RENAPO) para el servidor de pruebas.
 * Responde con el mismo formato JSON que el servicio real, con datos
 * inventados a partir de la CURP. CURPs especiales para probar errores:
 *   BAJA...  -> CURP dada de baja (statusCurp BSU)
 *   DEFU...  -> baja por defunción (statusCurp BD)
 *   NOEX...  -> operación no exitosa
 *   VACI...  -> respuesta vacía (servicio caído)
 */
header('Content-Type: application/json');
$entrada = json_decode(file_get_contents('php://input'), true);
$curp = strtoupper(trim(isset($entrada['curp']) ? $entrada['curp'] : ''));

switch (substr($curp, 0, 4)) {
    case 'VACI':
        exit;
    case 'BAJA':
        $status = array('statusCurp' => 'BSU', 'statusOper' => 'EXITOSO');
        break;
    case 'DEFU':
        $status = array('statusCurp' => 'BD', 'statusOper' => 'EXITOSO');
        break;
    case 'NOEX':
        $status = array('statusCurp' => '', 'statusOper' => 'NO EXITOSO');
        break;
    default:
        $status = array('statusCurp' => 'RCN', 'statusOper' => 'EXITOSO');
}

$nombres = array('ANA', 'LUIS', 'MARÍA', 'JOSÉ', 'SOFÍA', 'CARLOS', 'LUCÍA', 'JORGE', 'ELENA', 'RAÚL');
$apellidos = array('PRUEBA', 'FICTICIO', 'EJEMPLO', 'DEMOSTRACIÓN', 'SIMULADO', 'INVENTADO');
$n = abs(crc32($curp));

echo json_encode(array_merge(array(
    'CURP' => $curp,
    'nombres' => $nombres[$n % count($nombres)],
    'apellido1' => $apellidos[$n % count($apellidos)],
    'apellido2' => $apellidos[($n >> 3) % count($apellidos)],
    'sexo' => 'X',
    'message' => 'SIMULADOR DE PRUEBAS',
), $status));
