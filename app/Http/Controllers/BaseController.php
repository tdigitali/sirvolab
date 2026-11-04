<?php

namespace App\Http\Controllers;

class BaseController extends Controller
{
    /**
     * Palabras del User-Agent con las que el sistema original decidía mostrar
     * las vistas "Movil". Antes este bloque estaba copiado en cada método.
     */
    protected const AGENTES_MOVILES = ['Mobile', 'iPhone', 'iPod', 'BlackBerry', 'Opera Mini', 'Sony', 'MOT', 'Nokia', 'samsung'];

    /**
     * Regresa 1 si el navegador es móvil y 0 si no lo es (mismos valores que
     * la variable $detector del código original).
     *
     * Nota: el original usaba `strpos(...) != ""`; en PHP 8 esa comparación
     * cambió de significado, por lo que aquí se usa str_contains().
     */
    protected function detectarMovil(): int
    {
        $agente = (string) request()->userAgent();

        foreach (self::AGENTES_MOVILES as $movil) {
            if (str_contains($agente, $movil)) {
                return 1;
            }
        }

        return 0;
    }
}
