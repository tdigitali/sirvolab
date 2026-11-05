<?php
// Todos los correos van a Mailpit: no le llegan a nadie real.
return array(
    'driver'     => 'smtp',
    'host'       => 'mailpit',
    'port'       => 1025,
    'from'       => array('address' => 'sirvolab@pruebas.test', 'name' => 'SIRVOLAB (original, pruebas)'),
    'encryption' => '',
    'username'   => null,
    'password'   => null,
    'pretend'    => false,
);
