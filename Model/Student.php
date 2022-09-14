<?php
namespace mvc\Model;

use mvc\Model\MainModel;

class Student extends MainModel
{
    public static function User()
    {
        return self::belongsToMany(User::class);
    }
}
?>

