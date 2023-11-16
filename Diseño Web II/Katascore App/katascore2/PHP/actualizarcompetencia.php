<?php

require ("conectar2.php");
$con = conectar();
$id = $_POST["id_competencia"];
$nombre = $_POST["nombre"];
$fecha = $_POST["fecha"];
$hora = $_POST["hora"];
$lugar = $_POST["lugar"];

$sql = "UPDATE competencia SET 
          id_competencia='$id',
          nombre='$nombre',
          fecha='$fecha',
          hora='$hora',
          lugar='$lugar'
          WHERE `id_competencia`='$id'";
    $resultado = mysqli_query($con,$sql);
    Header("location:modificar_competencia.php");




?>