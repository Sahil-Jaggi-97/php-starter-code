<?php
namespace mvc\Config;

use mvc\Config\Response;
use Exception;

class Api
{
   public static function call($method, $url,$headerArray,$data)
   {
        $curl = curl_init();
        switch ($method){
           case "POST":
              curl_setopt($curl, CURLOPT_POST, 1);
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
              break;
           case "PUT":
              curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
              break;
           default:
              if ($data)
                 $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        if($headerArray)
        {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
        }
        
        // EXECUTE:
        $result = curl_exec($curl);

        //Throw errors if any
        if (curl_errno($curl)) 
        {
            throw new Exception(curl_error($curl));
            return curl_error($curl);
        }

        curl_close($curl);
        return Response::response($result);
    }
}
?>