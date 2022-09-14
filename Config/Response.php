<?php
namespace mvc\Config;

use Exception;

$Data = [];

class Response
{
    public static function json($array)
    { 
        if(gettype($array)=="array")
        {
            header("Content-type:application.json");
            return json_encode($array);
        }
        else
        {
            throw new Exception("Array required String given");
        }
    }

    public static function response($string,$status=Null)
    {
        if($status==NULL)
        {
            return $string;
        }
        else
        {
            http_response_code($status);
            return json_encode(["message"=>$string,"status"=>$status]); 
        }
    }

    public static function status($p1)
    {
        http_response_code($p1);
        // $valid_statuscodes=[208,226,305,307,308,417,422,424,426,428,429,431,508,510,511,0,100,101,102];
        
        // if (in_array($p1, $valid_statuscodes))
        // {
        //     http_response_code($p1);
        // }
        // else
        // {
        //     throw new Exception("Status code should be one of [208,226,305,307,308,417,422,424,426,428,429,431,508,510,511,0,100,101,102]");
        // }
    }

    public static function view($file,$userData="",$header="header.php",$footer="footer.php")
    {
        $path=dirname(__FILE__,2);
        $Data=[];
        $Data = $userData;
        include($path."/View/layout/".$header);
        include($path."/View/{$file}.php");
        include($path."/View/layout/".$footer);
    }

    public static function redirect($p1)
    {
        header("location:$p1");
    }

    public static function back()
    {
        if(empty($_SERVER['HTTP_REFERER']))
        {
            throw new Exception("Cannot redirect it back as there's no back url posted");
        }
        else
        {
            return header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

    public static function withHeaders($headers)
    {
        foreach($headers as $header)
        {
            header($header);
        }
    }

    public static function getResponseHeaders()
    {
        return headers_list();
    }
}
?>