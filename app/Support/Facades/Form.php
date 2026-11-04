<?php

namespace App\Support\Facades;

use App\Support\Html\FormBuilder;
use Illuminate\Support\Facades\Facade;

/**
 * Fachada Form:: usada por las vistas (compatibilidad con Laravel 4.2).
 *
 * @see FormBuilder
 */
class Form extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return FormBuilder::class;
    }
}
