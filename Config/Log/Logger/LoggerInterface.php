<?php
namespace mvc\Config\Log\Logger;

interface LoggerInterface
{
    
    public static function emergency($message, array $context = array());

    public static function alert($message, array $context = array());

    public static function critical($message, array $context = array());

    public static function error($message, array $context = array());

    public static function warning($message, array $context = array());

    public static function notice($message, array $context = array());

    public static function info($message, array $context = array());

    public static function debug($message, array $context = array());

    public static function log($level, $message, array $context = array());
}

?>