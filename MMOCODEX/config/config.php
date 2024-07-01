<?php 

try{
   //host
   define("HOST", "localhost");

   //database name
   define("DBNAME","forum");

   //user
    define("USER","root");
    
    // password
    define("PASS","");

    $conn = new PDO("mysql:host=".HOST."; dbname=".DBNAME."", USER, PASS); 
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // if($conn == true){
    //     echo " database connection succcess";
         
    // }else{
    //     echo"theres something wrong with connection";
    // }
} catch( PDOException $Exception){
    echo $Exception->getMessage();
}