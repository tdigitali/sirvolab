<?php
/*
return array(
	'driver' => 'smtp',
	'host' => '172.18.206.39',
	'port' => 25,
	'from' => array('address' => "sigcactc@sre.gob.mx", 'name' => "Sistema de Gestión de Cooperación Técnica y Científica"),
	'encryption' => '',
	'username' => null,
	'password' => null,
	'sendmail' => '/usr/sbin/sendmail -bs',
	'pretend' => false,

);
*/
/*
return array(
    'driver' => 'smtp',
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'from' => array('address' => 'esdu19@gmail.com', 'name' => 'Notificaciones SIRVOLAB'),
    'encryption' => 'tls',
    'username' => 'esdu19@gmail.com',
    'password' => '***REDACTADO***',
    'sendmail' => '/usr/sbin/sendmail -bs',
    'pretend' => false,
);
*/
return array(
    'driver' => 'smtp',
    'host' => 'smtp.sendgrid.net',
    'port' => 587,
    'from' => array('address' => 'sirvolab@stps.gob.mx', 'name' => 'Notificaciones SIRVOLAB'),
    //'encryption' => 'tls',
    'username' => 'apikey',
    'password' => '***REDACTADO***',
    //'password' => '***REDACTADO***',
    //'sendmail' => '/usr/sbin/sendmail -bs',
    //'pretend' => false,
);
