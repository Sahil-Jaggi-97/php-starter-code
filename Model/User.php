<?php

namespace mvc\Model;

use mvc\Model\MainModel;

class User extends MainModel
{
    public static $table="users";
    
    public static function Student()
    {
        return self::hasMany(Student::class);
    }
}
?>

