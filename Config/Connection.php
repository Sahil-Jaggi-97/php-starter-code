<?php
namespace mvc\Config;
use mvc\Config\config;
use Exception;

class Connection
{
    public static function init()
    {
        if(config::$dbProvider=="mysql")
        {
            return Connection::MysqlConnection();
        }
    }

    public static function MysqlConnection()
    {
        $conn = mysqli_connect(config::$servername,config::$username,config::$password,config::$database);
        if (!$conn) 
        {
            throw new Exception("DataBase Not Connected");
        }
        else
        {
            return $conn;
        }
    }
}
?>