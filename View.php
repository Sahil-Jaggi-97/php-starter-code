<?php
namespace mvc;

use Exception;
use Config\config;

$Data = [];

class View
{
    public static function view($file,$userData="",$header="header.php",$footer="footer.php")
    {
        $Data=[];
        $Data = $userData;
        if($header!="")
        {
            include("View/layout/".$header);
        }
            
        include("View/{$file}.php");

        if($footer!="")
        {
            include("View/layout/".$footer);
        }
    }
}

?>