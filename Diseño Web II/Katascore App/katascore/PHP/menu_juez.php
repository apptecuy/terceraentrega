<?php
 $mysqli = new mysqli("localhost", "apptec", "47743966", "bd_apptec");

if ($mysqli->connect_error) {
    die("Conexión a la base de datos fallida: " . $mysqli->connect_error);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/sorteo.css">
    <title>Entorno de espera</title>
</head>
<body>
    <div class="espera">
        <h1 class="h2espera" >Bienvenidos a la competencia</h1>
     <img src="../IMG/katarojo1.png" class="kimonorojo" alt="" height="500px">
     <img src="../IMG/LogoCuk.png" class="logoespera" alt="" height="150px" width="150px">
     <img src="../img/kataazul3.png" class="kimonoazul" alt="" height="500px">
     <form method="get" action="logueo_juez.php">
  
 <input type="submit" class="boton-calificar" value ="Confirme su número de Juez">

    </form>
</div>
</body>
</html>