<?php
require_once('autoload.php');

use mvc\Config\Log\Logger\FileLogger; 

try
{
    $init=new mvc\Config\init(); //init class to dispatch action
}

catch(Exception $e)
{
    echo "<b>".get_class($e).":</b>".$e->getMessage() ." in <b>".$e->getFile()."</b> on line no. <b>".__LINE__."</b>";
    FileLogger::warning($e->getMessage() ." in ".$e->getFile()." on line no. ".__LINE__.""); 
}

?>