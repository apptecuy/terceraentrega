<?php

function conectar()
{
    $host = "localhost";
    $usr = "apptec";
    $pwd = "47743966";

    $bd = "bd_apptec";

    $con = mysqli_connect($host,$usr,$pwd,$bd) 
        or die("error de conexión".mysqli_connect_error());

    return $con;
}

?>