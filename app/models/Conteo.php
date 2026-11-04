<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Conteo extends Eloquent implements UserInterface, RemindableInterface{
    use UserTrait, RemindableTrait, SoftDeletingTrait;
    protected $table = 'conteo';
    protected $fillable = ['boleta_id'];
}
