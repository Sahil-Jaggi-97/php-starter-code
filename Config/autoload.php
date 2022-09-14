<?php

spl_autoload_register(function ($class) 
{
    // dirname(__FILE__,3) gets the root diectory of composer i.e. until www/html....str_replace replaces \\ with / in namespaces 
    include dirname(__FILE__,3)."/".str_replace("\\","/",$class).".php";
});

?>

