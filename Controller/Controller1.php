<?php

namespace mvc\Controller;

use mvc\Config\View;
use mvc\Model\User;
use mvc\Model\Student;
use mvc\Config\Log\Logger\FileLogger;
use mvc\Config\Request;
use mvc\Config\Response;
use mvc\Config\Api;

class Controller1
{
    private static $logger;

    public static function index()
    { 
        echo "<pre>";
        $data=User::select(['name','id','email'])->with('Student')->where(['name'=>'sim','email'=>'sim@gmail.com'])->orWhere(['name'=>'abc','email'=>'sim@gmail.com'])->orderBy('id')->execute();
        print_r($data);
    
        // echo "<pre>";
        // Student::connect('User',[[3,9],[4,10],[2,9],[2,10]]) ;
        
        // $array=[['name'=>'sim','class'=>'12','marks'=>'90'],['name'=>'sim','email'=>'sim@gmail.com','password'=>'123']];
        // print_r(Student::createConnected('User',$array));

        // $array=Student::with('User');
        // print_r(json_decode($array,true));     
    }

    public static function show()
    {
        $data=["abc"=>'hlo2'];
        return View::view('StudentForm',$data);//for default header footer
        // return View::view('StudentForm',$data,"header2.php","footer.php"); //for custom header,footer
        // return View::view('StudentForm',$data,"",""); //for empty header,footer
    }

    public static function Api()
    {
        $header=[
            'Authorization:Bearer sk_test_51KyTVvSFqPVpcKeoE4TIvK7no1IRfiT6Cue69TPfBiZp7g32N6GHgRzEOj4QXkc9okizp0wGz4MV0SBK40zCkRv500qcv5m9Ef',
            'Content-Type:application/json'
        ];
        
        $body=[
            'name' => 'sim'
        ];
        
        $data = Api::call("GET","https://catfact.ninja/fact","","");
        return $data;
    }
   
}
?>