<?php
namespace mvc\Config\Log\Logger;

use mvc\Config\Log\Logger\LoggerInterface;

class FileLogger implements LoggerInterface
{
    public static function log($level, $message, array $context = []): void
    {
        $date = (new \DateTime())->format('Y-m-d');
        $dateFormatted = (new \DateTime())->format('Y-m-d H:i:s');
        $message = sprintf(
            '[%s] %s: %s%s',
            $dateFormatted,
            $level,
            $message,
            PHP_EOL // Line break
        );

        file_put_contents(dirname(__FILE__,2)."/LogFiles"."/".$date.".txt", $message, FILE_APPEND);
    }

    public static function emergency($message, array $context = []): void
    {
        self::log(LogLevel::EMERGENCY, $message, $context);
    }

    public static function alert($message, array $context = []): void
    {
        self::log(LogLevel::ALERT, $message, $context);
    }

    public static function critical($message, array $context = []): void
    {
        self::log(LogLevel::CRITICAL, $message, $context);
    }
    
    public static function error($message, array $context = []): void
    {
        self::log(LogLevel::ERROR, $message, $context);
    }
    public static function warning($message, array $context = []): void
    {
        self::log(LogLevel::WARNING, $message, $context);
    }
    public static function notice($message, array $context = []): void
    {
        self::log(LogLevel::NOTICE, $message, $context);
    }
    public static function info($message, array $context = []): void
    {
        self::log(LogLevel::INFO, $message, $context);
    }
    public static function debug($message, array $context = []): void
    {
        self::log(LogLevel::DEBUG, $message, $context);
    }
}