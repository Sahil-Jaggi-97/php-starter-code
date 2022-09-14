<?php

namespace mvc\Config;

use Exception;
use mvc\Middleware\Middleware1;
use mvc\Config\Kernal;

class router
{
    public static $routes = [];//store routes
    public static $middle= [];//store middleware
    public static $tempRoute= "";//to stote route for middleware

    public static function post($action,$callback)
    {
        $action = trim($action, '/');
        self::$routes["POST/mvc/".$action] = $callback;
        self::$routes["POST/mvc/".$action] = $callback;
        self::$tempRoute= "POST/mvc/".$action;
        return new static;
    }

    public static function get($action,$callback)
    {
        $action = trim($action, '/');
        self::$routes["GET/mvc/".$action] = $callback;
        self::$tempRoute= "GET/mvc/".$action;
        return new static;
    }

    public static function middleware($middleware)
    {
        self::$middle[self::$tempRoute] = $middleware;
        self::$tempRoute="";
    }

    public static function dispatch($action)
    {
        $request = trim($action, '/');
        $action=$_SERVER['REQUEST_METHOD']."/".current(explode('?',$request));
       
        //checks if any middleware exists for current route(in self::middle array)
        if(array_key_exists($action,self::$middle))
        {
            $middleware=self::$middle[$action];//middleware name from array
            $array=Kernal::$routemiddleware;  //checks middleware array from kernal.php
            if (array_key_exists($middleware,$array))  //if middleware exist in kernal.php
            {
                $class=$array[$middleware];     //class of that middleware
                if($class::handle()=="next")      //executes the middleware handle function 
                {
                    //excutes the route if middleware return true
                   self::executeRoute($action);
                }
            }
        }
        
        //execute route without middleware
        else
        {
            self::executeRoute($action);
        }
    }

    public static function executeRoute($action)
    {
        if(array_key_exists($action,self::$routes))
        {
            $callback = self::$routes[$action];  
            echo call_user_func($callback);
        }
        else
        {
            throw new Exception("Route not found");
        }
    }
}
?>