<?php
namespace mvc\Config;

use mvc\Config\config;
use mvc\Config\Response;
use Exception;

class Request
{
    public static function get($p1)
    {
        $method=$_SERVER['REQUEST_METHOD'];

        switch($method)
        {
            case "GET":
                if(isset($_GET[$p1]))
                {
                   return $_GET[$p1];
                    break;
                }
            case "POST":
                if(isset($_GET[$p1]))
                {
                    return $_POST[$p1];
                    break;
                }
            default:
                throw new Exception("Variable not found");
                return "Variable not found";
        }
    }

    public static function all()
    {
        $method=$_SERVER['REQUEST_METHOD'];
        switch($method)
        {
            case "GET":
                return $_GET;
                break;
            case "POST":
                return $_POST;
                break;
            default:
                throw new Exception("Not Found");
                return false;
        }
    }

    public static function getheader()
    {
        $url = Config::$projectUrl;
        return get_headers($url);
    }

    public static function input($p1)
    {
        $method=$_SERVER['REQUEST_METHOD'];
        switch($method)
        {
            case "GET":
                if(isset($_GET[$p1]))
                {
                   return $_GET[$p1];
                    break;
                }
            case "POST":
                if(isset($_GET[$p1]))
                {
                    return $_POST[$p1];
                    break;
                }
            default:
                throw new Exception("Variable not found");
                return "Variable not found";
        }
    }
    
    public static function getFile($p1)
    {
        $array=[];
      
        if (empty($_FILES[$p1]["name"]))
        {
            throw new Exception("File Not Found");
        }
        else
        {
            for($i=0; $i<count($_FILES[$p1]['name']); $i++)
            {
                //fetches details for i=0,1,2,3.....files
                $name=$_FILES[$p1]['name'][$i];
                $type=$_FILES[$p1]['type'][$i];
                $size=$_FILES[$p1]['size'][$i];
                $temp_name=$_FILES[$p1]['tmp_name'][$i];
                $error=$_FILES[$p1]['error'][$i];
                
                // push details in array
                array_push($array,['name'=>$name,'type'=>$type,'size'=>$size,'temp_name'=>$temp_name,'error'=>$error]);
            }
        }
        return $array;
    }  

    public static function setHeaders($headers)
    {
        foreach($headers as $header)
        {
            header($header);
        }
    }

    public static function getHeaders()
    {
        return headers_list();
    }
}

?>