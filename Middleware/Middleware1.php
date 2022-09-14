<?php
namespace mvc\Middleware;

use mvc\Config\Request;
use mvc\Config\Response;
use mvc\Config\View;

class Middleware1
{
    public static function handle()
    {
        if($_SERVER['REQUEST_METHOD']=='GET')
        {
            return "next";
        }
        else
        {
            return View::view('StudentForm',"","","");
        }
    }
}
?>