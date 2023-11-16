<?php

include("conectar2.php");
$id = $_GET['id_competencia'];
$con = conectar();
$sql = "SELECT * FROM competencia WHERE id_competencia = '$id'";
$resultado = mysqli_query($con, $sql);
$fila = mysqli_fetch_array($resultado);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/Nuevaspestañasadmin2.css">
    <title>Document</title>
</head>
<body>
    <div class="Nuevacompetencia">
      <h1> Actualizar competencia</h1>
      <br>
      <form action="actualizarcompetencia.php" method="POST" >
      
      <label for="id_competencia">Id competencia:</label><br>
<p>
    <input type="text" name="id_competencia" value="<?= htmlspecialchars($fila['id_competencia']) ?>" readonly>
    <p>
            <label for="nombre">Nombre:</label><br>
            <p><input type="text" name="nombre" value="<?= $fila['nombre']?>"></p>
     <br>
     <label for="fecha">Fecha:</label><br>
            <p><input type="date" name="fecha" value="<?= $fila['fecha']?>"></p>
     <br>
     <label for="n">Hora:</label><br>
            <p><input type="time" name="hora" value="<?= $fila['hora']?>"></p>
            
            <label for="lugar">Lugar:</label><br>
            <p><input type="text" name="lugar" value="<?= $fila['lugar']?>"></p>
            
</p>
<br>
<br>
<br>
     <input type="submit" class="actualizarcompetencia" value="Actualizar competencia">
    </form>
     <br>
     <br>
     <form action="../HTML/competencias.html">
     <input type="submit" class="regresar" value="Regresar">
    </form>
