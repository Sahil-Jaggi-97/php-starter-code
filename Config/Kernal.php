<?php
namespace mvc\Config;

use mvc\Config\Response;
use Exception;

class Kernal
{
    public static $routemiddleware=[
        'test' => \mvc\Middleware\Middleware1::class,
    ];
}