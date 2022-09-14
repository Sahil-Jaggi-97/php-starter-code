<?php
namespace mvc\Config;

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
            include(dirname(__FILE__,2)."/View/layout/".$header);
        }
            
        include(dirname(__FILE__,2)."/View/{$file}.php");

        if($footer!="")
        {
            include(dirname(__FILE__,2)."/View/layout/".$footer);
        }
    }
}

?>