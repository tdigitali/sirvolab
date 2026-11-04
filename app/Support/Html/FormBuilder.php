<?php

namespace App\Support\Html;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Reemplazo mínimo del FormBuilder de Laravel 4.2 (Form::...).
 *
 * El paquete original (illuminate/html, luego laravelcollective/html) ya no
 * es compatible con Laravel 13, así que aquí se replica únicamente la parte
 * que usan las vistas de SIRVOLAB, con el mismo comportamiento:
 *  - open() agrega el campo oculto _token (CSRF) en formularios no-GET.
 *  - Los campos se repueblan con el "old input" de la sesión después de un
 *    Redirect::back()->withInput().
 *  - 'action' => 'Controlador@metodo' se resuelve contra App\Http\Controllers.
 */
class FormBuilder
{
    /** Opciones de open() que no se imprimen como atributos. */
    protected array $reserved = ['method', 'url', 'route', 'action', 'files'];

    /** Métodos HTTP que se simulan con el campo _method. */
    protected array $spoofedMethods = ['DELETE', 'PATCH', 'PUT'];

    /** Tipos de input que nunca toman su valor del old input. */
    protected array $skipValueTypes = ['file', 'password', 'checkbox', 'radio'];

    public function open(array $options = []): string
    {
        $method = Arr::get($options, 'method', 'post');

        $attributes = [
            'method' => $this->getMethod($method),
            'action' => $this->getAction($options),
            'accept-charset' => 'UTF-8',
        ];

        $append = $this->getAppendage($method);

        if (! empty($options['files'])) {
            $options['enctype'] = 'multipart/form-data';
        }

        $attributes = array_merge($attributes, Arr::except($options, $this->reserved));

        return '<form'.$this->attributes($attributes).'>'.$append;
    }

    public function close(): string
    {
        return '</form>';
    }

    public function token(): string
    {
        $session = $this->session();

        return $this->hidden('_token', $session ? $session->token() : '');
    }

    public function input(string $type, ?string $name, mixed $value = null, ?array $options = []): string
    {
        $options = (array) $options;

        if (! isset($options['name'])) {
            $options['name'] = $name;
        }

        $id = $this->getIdAttribute($options);

        if (! in_array($type, $this->skipValueTypes, true)) {
            $value = $this->getValueAttribute($name, $value);
        }

        $options = array_merge($options, compact('type', 'value', 'id'));

        return '<input'.$this->attributes($options).'>';
    }

    public function text(string $name, mixed $value = null, ?array $options = []): string
    {
        return $this->input('text', $name, $value, $options);
    }

    public function password(string $name, ?array $options = []): string
    {
        return $this->input('password', $name, '', $options);
    }

    public function hidden(string $name, mixed $value = null, ?array $options = []): string
    {
        return $this->input('hidden', $name, $value, $options);
    }

    public function email(string $name, mixed $value = null, ?array $options = []): string
    {
        return $this->input('email', $name, $value, $options);
    }

    public function number(string $name, mixed $value = null, ?array $options = []): string
    {
        return $this->input('number', $name, $value, $options);
    }

    public function file(string $name, ?array $options = []): string
    {
        return $this->input('file', $name, null, $options);
    }

    /** Las vistas usan Form::textArea(); PHP no distingue mayúsculas en métodos. */
    public function textarea(string $name, mixed $value = null, ?array $options = []): string
    {
        $options = (array) $options;

        if (! isset($options['name'])) {
            $options['name'] = $name;
        }

        if (isset($options['size'])) {
            [$cols, $rows] = explode('x', $options['size']);
            unset($options['size']);
        } else {
            $cols = $options['cols'] ?? 50;
            $rows = $options['rows'] ?? 10;
        }
        $options = array_merge($options, compact('cols', 'rows'));
        $options['id'] = $this->getIdAttribute($options);

        $value = (string) $this->getValueAttribute($name, $value);

        return '<textarea'.$this->attributes($options).'>'.e($value).'</textarea>';
    }

    public function select(string $name, array $list = [], mixed $selected = null, ?array $options = []): string
    {
        $options = (array) $options;
        $selected = $this->getValueAttribute($name, $selected);
        $options['id'] = $this->getIdAttribute($options);

        if (! isset($options['name'])) {
            $options['name'] = $name;
        }

        $html = [];
        foreach ($list as $value => $display) {
            $html[] = is_array($display)
                ? $this->optionGroup($display, $value, $selected)
                : $this->option($display, $value, $selected);
        }

        return '<select'.$this->attributes($options).'>'.implode('', $html).'</select>';
    }

    public function checkbox(string $name, mixed $value = 1, mixed $checked = null, ?array $options = []): string
    {
        return $this->checkable('checkbox', $name, $value, $checked, $options);
    }

    public function radio(string $name, mixed $value = null, mixed $checked = null, ?array $options = []): string
    {
        $value ??= $name;

        return $this->checkable('radio', $name, $value, $checked, $options);
    }

    protected function checkable(string $type, string $name, mixed $value, mixed $checked, ?array $options): string
    {
        $options = (array) $options;

        if ($this->getCheckedState($type, $name, $value, $checked)) {
            $options['checked'] = 'checked';
        }

        return $this->input($type, $name, $value, $options);
    }

    protected function getCheckedState(string $type, string $name, mixed $value, mixed $checked): bool
    {
        if ($type === 'radio') {
            $old = $this->old($name);

            return $old === null ? (bool) $checked : ((string) $old === (string) $value);
        }

        // Si se reenvió el formulario y el checkbox no venía marcado, queda desmarcado.
        if (! $this->oldInputIsEmpty() && $this->old($name) === null) {
            return false;
        }

        if ($this->old($name) === null) {
            return (bool) $checked;
        }

        $posted = $this->getValueAttribute($name);

        return is_array($posted) ? in_array($value, $posted) : (bool) $posted;
    }

    protected function option(mixed $display, mixed $value, mixed $selected): string
    {
        $attributes = [
            'value' => (string) $value,
            'selected' => $this->getSelectedValue($value, $selected),
        ];

        return '<option'.$this->attributes($attributes).'>'.e((string) $display).'</option>';
    }

    protected function optionGroup(array $list, mixed $label, mixed $selected): string
    {
        $html = [];
        foreach ($list as $value => $display) {
            $html[] = $this->option($display, $value, $selected);
        }

        return '<optgroup label="'.e((string) $label).'">'.implode('', $html).'</optgroup>';
    }

    protected function getSelectedValue(mixed $value, mixed $selected): ?string
    {
        if (is_array($selected)) {
            return in_array($value, $selected) ? 'selected' : null;
        }

        return ((string) $value === (string) $selected) ? 'selected' : null;
    }

    protected function getMethod(string $method): string
    {
        $method = strtoupper($method);

        return $method !== 'GET' ? 'POST' : $method;
    }

    protected function getAction(array $options): string
    {
        if (isset($options['url'])) {
            $url = $options['url'];

            return is_array($url) ? url($url[0], array_slice($url, 1)) : url($url);
        }

        if (isset($options['route'])) {
            $route = $options['route'];

            return is_array($route) ? route($route[0], array_slice($route, 1)) : route($route);
        }

        if (isset($options['action'])) {
            $action = $options['action'];

            return is_array($action)
                ? url()->action($this->qualifyAction($action[0]), array_slice($action, 1))
                : url()->action($this->qualifyAction($action));
        }

        return url()->current();
    }

    /** 'BoletaController@store' -> 'App\Http\Controllers\BoletaController@store' */
    protected function qualifyAction(string $action): string
    {
        $action = ltrim($action, '\\');

        return Str::contains($action, '\\') ? $action : 'App\\Http\\Controllers\\'.$action;
    }

    protected function getAppendage(string $method): string
    {
        $method = strtoupper($method);
        $append = '';

        if (in_array($method, $this->spoofedMethods, true)) {
            $append .= $this->hidden('_method', $method);
        }

        if ($method !== 'GET') {
            $append .= $this->token();
        }

        return $append;
    }

    protected function getIdAttribute(array $attributes): mixed
    {
        return $attributes['id'] ?? null;
    }

    public function getValueAttribute(?string $name, mixed $value = null): mixed
    {
        if ($name === null) {
            return $value;
        }

        $old = $this->old($name);

        return $old !== null ? $old : $value;
    }

    public function old(string $name): mixed
    {
        $session = $this->session();

        if (! $session) {
            return null;
        }

        $key = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $name);

        return $session->getOldInput($key);
    }

    protected function oldInputIsEmpty(): bool
    {
        $session = $this->session();

        return ! $session || count((array) $session->getOldInput()) === 0;
    }

    protected function session(): ?Session
    {
        $request = request();

        return $request->hasSession() ? $request->session() : null;
    }

    /** Equivalente a HtmlBuilder::attributes() de Laravel 4.2. */
    public function attributes(array $attributes): string
    {
        $html = [];

        foreach ($attributes as $key => $value) {
            if (is_numeric($key)) {
                $key = $value;
            }

            if ($value !== null) {
                $html[] = $key.'="'.e((string) $value).'"';
            }
        }

        return count($html) > 0 ? ' '.implode(' ', $html) : '';
    }
}
