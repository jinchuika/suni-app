<?php 
    function Conn() 
    {   
        define('DB_SERVER','suni.db.4541636.hostedresource.com');
        define('DB_NAME','suni');
        define('DB_USER','suni');
        define('DB_PASS','Fun53P@!2');
        if (!($link=mysql_connect(DB_SERVER,DB_USER,DB_PASS))) 
        { 
            echo "Error conectando a la base de datos."; 
            exit(); 
        }else{
            //echo "Conexion Exitosa.";             
        } 
        
        if (!mysql_select_db(DB_NAME,$link)) 
        { 
                echo "Error MySQL: ".mysql_error();
                echo "Error seleccionando la base de datos."; 
                exit(); 
        } 
        return $link; 
    } 
?>