<?php
namespace mvc\Model;

use mvc\Config\Connection;
use Exception;

abstract class MainModel
{
    public static $select="";
    public static $json="";
    public static $join="";
    public static $combined="";

    public static function connection()
    {
        return Connection::MysqlConnection();
    }

    public static function table()
    {
        $class=get_called_class();
        if (!property_exists($class,'table'))
        {
            $array=explode('\\',$class);
            $model=end($array);
            return strtolower($model."s");
        }
        return $class::$table;
    }
    
    public static function get()
    {
        $table=self::table();
        $sql="select * from $table";
        $query=mysqli_query(self::connection(), $sql);
        
        if(!$query)
        {
            throw new Exception("Check your sql query");
        }
        else
        {
            $data = mysqli_fetch_all($query, MYSQLI_ASSOC);
            if($data==NULL)
            {
                $data["message"] = "Cannot find any data in Table";
                $data["status"] = "500"; 
            }
        }

        return json_encode($data);
    }

    public static function insert($array)
    {
        $keyarray=array_keys($array);
        $valuearray=array_values($array); 
        $table=self::table();
        $key=implode(",",$keyarray);
        $values="'" . implode ( "', '", $valuearray ) . "'";

        $sql="INSERT INTO $table ($key) VALUES ($values)";
        $query=mysqli_query(self::connection(), $sql);

        if(!$query)
        {
            throw new Exception("Check your sql query");
        }
        else
        {
            if($query==1)
            {
                $data["message"] = "Data saved successfully";
                $data["status"] = "200";
            }
            else
            {
                $data["message"] = "Some Error Occured";
                $data["status"] = "500";   
            }
        }

        return json_encode($data);
    }

    public static function delete($id)
    {
        $table=self::table();
        $sql="DELETE FROM $table WHERE id = $id";
        $query=mysqli_query(self::connection(), $sql);

        if(!$query)
        {
            throw new Exception("Check your sql query");
        }
        else
        {
            if($query==true)
            {
                $data["message"] = "Data Deleted Successfully";
                $data["status"] = "200";
            }
            else
            {
                $data["message"] = "Cannot Delete Data";
                $data["status"] = "500";   
            }
        }

        return json_encode($data);
    }

    public static function update($array,$id)
    {
        $setarray=[];
        foreach($array as $key => $value)
        {
            array_push($setarray,"$key='$value'");
        };
        $set=implode(",",$setarray);

        $table=self::table();
        $sql="UPDATE $table SET $set WHERE id = $id";
        $query=mysqli_query(self::connection(), $sql);
        
        if(!$query)
        {
            throw new Exception("Check your sql query");
        }
        else
        {
            if($query==true)
            {
                $data["message"] = "Data Updated Successfully";
                $data["status"] = "200";
            }
            else
            {
                $data["message"] = "Cannot Update Data";
                $data["status"] = "500";   
            }
        }

        return json_encode($data);
    }

    public static function find($id)
    {
        $table=self::table();
        $sql="select * from $table where id=$id";
        $query=mysqli_query(self::connection(), $sql);

        if(!$query)
        {
            throw new Exception("Check your sql query");
        }
        else
        {
            $data=mysqli_fetch_assoc($query);
            if($data==NULL)
            {
                $data["message"] = "Cannot Find Data";
                $data["status"] = "500"; 
            }
        }

        return json_encode($data);
    }

    public static function hasOne($p1,$foreign="default",$primary="id")
    {
        //get both tables name
        $table1=self::table();
        $a=explode('Model\\',$p1);
        $table2=strtolower(end($a))."s";
        if($foreign=="default")
        {
            $foreign=$table1."_id";
        }
        
        // get columns of table2 which are to be taken in json format
        $columns=self::getColumns($table2);

        //query to get data using joins
        $sql1="SELECT $table1.*,JSON_OBJECT($columns) as $table2 FROM $table1 INNER JOIN $table2 ON $table1.$primary = $table2.$foreign ORDER BY $table1.id";
        $query1=mysqli_query(self::connection(), $sql1);
        return json_encode(['json'=>"JSON_OBJECT($columns) as $table2",'join'=>"INNER JOIN $table2 ON $table1.$primary = $table2.$foreign"]);
    }
   
    public static function belongsTo($p1,$foreign="default",$primary="id")
    {
        //get both tables name
        $table1=self::table();
        $a=explode('Model\\',$p1);
        $b=strtolower(end($a));
        $table2=$b."s";
        if($foreign=="default")
        {
            $foreign=$b."s_id";
        }

        // get columns of table2 which are to be taken in json format
        $columns=self::getColumns($table2);

        //query to get data using joins
        $sql1="SELECT $table1.*,JSON_OBJECT($columns) AS $table2 FROM $table1 Left JOIN $table2 ON $table2.$primary = $table1.$foreign ORDER BY $table1.id";

        $query1=mysqli_query(self::connection(), $sql1);
        return json_encode(['json'=>"JSON_OBJECT($columns) AS $table2",'join'=>"Left JOIN $table2 ON $table2.$primary = $table1.$foreign"]);
    }

    public static function hasMany($p1,$foreign="default",$primary="id")
    {
        //get both tables name
        $table1=self::table();
        $a=explode('Model\\',$p1);
        $table2=strtolower(end($a))."s";
        if($foreign=="default")
        {
            $foreign=$table1."_id";
        }
        
        // get columns of table2 which are to be taken in json format
        $columns=self::getColumns($table2);
        //query to get data using joins
        $sql1="SELECT $table1.id, $table1.*, JSON_ARRAYAGG(JSON_OBJECT($columns)) AS $table2 FROM $table1 LEFT JOIN $table2 ON $table1.$primary = $table2.$foreign GROUP BY $table1.id";
       
        $query1=mysqli_query(self::connection(), $sql1);

        return json_encode(["json"=>"JSON_ARRAYAGG(JSON_OBJECT($columns)) as $table2","join"=>"LEFT JOIN $table2 ON $table1.$primary = $table2.$foreign GROUP BY $table1.id"]);
    }

    public static function belongsToMany($p1,$foreign="default",$primary="id")
    {
        //table name , primary and foreign keys
        $table1=self::table();
        $a=explode('Model\\',$p1);
        $b=strtolower(end($a));
        $table2=$b."s";
        if($foreign=="default")
        {
            $foreign=$b."s_id";
        }

        //table2 columns to wrap into object
        $columns=self::getColumns($table2);

        //details of junction table to be created
        $table=substr($table1, 0, -1)."_".$b;
        $col1=substr($table1, 0, -1);
        $col2=$b;

        //create pivot/junction table
        $created=self::createTable($table,$table1,$table2,$col1,$col2);

        //fetch many-many relational data
        $sql4="SELECT $table1.*,JSON_ARRAYAGG(JSON_OBJECT($columns)) AS $table2 FROM $table1 JOIN $table ON $table.$col1 =$table1.id JOIN $table2 ON $table2.id= $table.$col2 GROUP BY $table1.id";
        $query4=mysqli_query(self::connection(), $sql4);
        // $data4 = mysqli_fetch_all($query4, MYSQLI_ASSOC);

        return json_encode(['json'=>"JSON_ARRAYAGG(JSON_OBJECT($columns)) AS $table2",'join'=>"JOIN $table ON $table.$col1 =$table1.id JOIN $table2 ON $table2.id= $table.$col2 GROUP BY $table1.id"]);   
    }

    // public static function with($function)
    // {
    //     $table1=get_called_class();
    //     return $table1::$function();
    // }

    public static function getColumns($table2)
    {
        $sql="SHOW COLUMNS FROM $table2";
        $query=mysqli_query(self::connection(), $sql);
        while($record = mysqli_fetch_array($query))
        {  
            $fields[] = "'".$record['0']."',".$table2.".".$record['0'];  
        }
        return implode(",",$fields); 
    }

    public static function connect($model2,$array)
    {
        $table1=self::table();
        $b=strtolower($model2);
        $table2=$b."s";
        $table=substr($table1, 0, -1)."_".$b;
        $col1=substr($table1, 0, -1);
        $col2=$b;

        //create pivot/junction table if not created
        $created=self::createTable($table,$table1,$table2,$col1,$col2);

        foreach($array as $value)
        {  
            $sql3="insert into  $table ( $col1, $col2 )values ( $value[0],$value[1])"; 
            $query3=mysqli_query(self::connection(), $sql3);
        }
        return $query3;
    }

    public static function createConnected($model2,$array)
    {
        //all table names
        $table1=self::table();
        $b=strtolower($model2);
        $table2=$b."s";
        $table_concat=substr($table1, 0, -1)."_".$b;
        
        //sorts array in alphabatical order as table is created in alphatical order
        $table_array=explode('_',$table_concat);
        sort($table_array);
        $table=implode("_",$table_array);
        
        $col1=substr($table1, 0, -1);
        $col2=$b;
        $conn=self::connection();

        //format array values in insert format
        $key1=implode(",",array_keys($array[0]));
        $values1="'" . implode ( "', '", array_values($array[0]) ) . "'";
        $key2=implode(",",array_keys($array[1]));
        $values2="'" . implode ( "', '", array_values($array[1]) ) . "'";

        // insert values in both tables
        $sql1="Insert into  $table1 ($key1 )values ($values1)"; 
        $query1=mysqli_query($conn, $sql1);
        $id1=mysqli_insert_id($conn);

        $sql2="Insert into  $table2 ($key2 )values ($values2)"; 
        $query2=mysqli_query($conn, $sql2);
        $id2=mysqli_insert_id($conn);

        //create pivot/junction table if not created
        $created=self::createTable($table,$table1,$table2,$col1,$col2);

        //insert values in pivot table for many many connection
        $sql3="insert into  $table ( $col1, $col2 )values ( $id1,$id2)"; 
        $query3=mysqli_query($conn, $sql3);

        if(!$query3)
        {
            throw new Exception("There's some error");
        }
        else
        {
            $data1["message"] = "Insertion Successful";
            $data1["status"] = "200"; 
        }
        return json_encode($data1);
    }

    public static function createTable($table,$table1,$table2,$col1,$col2)
    {
        if ($result = mysqli_query(self::connection(),"SHOW TABLES LIKE '".$table."'")) 
        {
            if($result->num_rows == 1) 
            {
                return $tablecheck=1;
            }
            else 
            {
                $sql1="CREATE TABLE $table(`$col1` INT,`$col2` INT ,PRIMARY KEY (`$col1`, `$col2`),CONSTRAINT `$table.$col1` FOREIGN KEY `$col1` (`$col1`) REFERENCES `$table1` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,CONSTRAINT `$table.$col2` FOREIGN KEY `$col2` (`$col2`) REFERENCES `$table2` (`id`) ON DELETE CASCADE ON UPDATE CASCADE)";
    
                $query1=mysqli_query(self::connection(), $sql1);
                if(!$query1)
                {
                    throw new Exception("Check your sql query");
                }
                else
                {
                    return $tablecheck=1;
                }
            }
        }
    }

    public static function select($p1)
    {
        $table=self::table();
        if(gettype($p1)=='array')
        {
            $var=implode(",$table.",$p1);//convert array to string with prefix table like users.id(except first which will get its prefix in )
            $p1=$var;
        }
    
        self::$select.="SELECT $table.$p1";
        return new static;
    }

    public static function where($array)
    {
        $setarray=[];
        foreach($array as $key => $value)
        {
            array_push($setarray,"$key='$value'");
        }
        $set=implode(" AND ",$setarray);

        $table=self::table();
        if(self::$join=="")
        {
            self::$combined.=" where $set";
        }
        else
        {
            self::$combined.=" having $set";
        }
        return new static;    
    }

    public static function orWhere($array)
    {
        $setarray=[];
        foreach($array as $key => $value)
        {
            array_push($setarray,"$key='$value'");
        }
        $set=implode(" AND ",$setarray);

        $table=self::table(); 
        self::$combined.=" OR $set";
        return new static;   
    }

    public static function with($function)
    {
        $table1=get_called_class();
        $data=$table1::$function();
        self::$json=",".json_decode($data)->json;
        self::$join=" ".json_decode($data)->join;
        return new static;
    }

    public static function groupBy($p1)
    {
        $table=self::table();
        self::$combined.=" Group BY $p1";
        return new static;
    }


    public static function orderBy($p1,$p2="")
    {
        $table=self::table();
        self::$combined.=" Order BY $p1 $p2";
        return new static;
    }

    public static function execute()
    {
        $table=self::table();
        if(self::$select=="")
        {
            self::$select.="Select $table.* ";
        }

        $sql1=self::$select. self::$json.' from '.$table.self::$join.self::$combined.';';
        $query1=mysqli_query(self::connection(), $sql1);
        // return $sql1;
        if(!$query1)
        {
            throw new Exception("Check your sql query");
        }
        else
        {
            $data = mysqli_fetch_all($query1, MYSQLI_ASSOC);
            if($data==NULL)
            {
                $data["message"] = "Cannot find any data in Table";
                $data["status"] = "500"; 
            }
        }
   
        return json_encode($data);
    }
}

?>














<!-- //populate pivot table
        // $sql2="SELECT $table1.id as $table1,$table2.id FROM $table1 CROSS JOIN $table2";
        // $query2=mysqli_query(self::connection(), $sql2);
        // while($record = mysqli_fetch_assoc($query2))
        // {  
        //     $sql3="insert into  $table ( $col1, $col2 )values ( $record[$table1],$record[id])"; 
        //     $query3=mysqli_query(self::connection(), $sql3);
        // } 

        // public static function where($p1,$p3)
    // {
    //     $table=self::table();
    //     self::$combined.=" where $p1='$p3'";
    //     return new static;
    // }-->
           