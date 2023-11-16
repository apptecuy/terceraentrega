<?php

$host = "localhost"; // 
$usuario = "apptec";
$contrasena = "47743966";
$base_de_datos = "bd_apptec";

$con = new mysqli($host, $usuario, $contrasena, $base_de_datos);

if ($con->connect_error) {
    die("Error de conexión a la base de datos: " . $con->connect_error);
}


$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$mail = $_POST["mail"];
$id_competidor = $_POST["id_competidor"];
$ranking = $_POST["ranking"];
$sexo = $_POST["sexo"];
$fecha_nac = $_POST["fecha_nac"];
$escuela = $_POST["escuela"];


$query = "INSERT INTO competidor (nombre, apellido, mail, id_competidor, ranking, sexo, fecha_nac, escuela) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";


$stmt = $con->prepare($query);


$stmt->bind_param("ssssssss", $nombre, $apellido, $mail, $id_competidor, $ranking, $sexo, $fecha_nac, $escuela);


if ($stmt->execute()) {
     echo'<script type="text/javascript">
    alert("Registro exitoso.");
    window.location.href="../HTML/formulario.html";
    </script>';
} else {
    echo '<script type="text/javascript">
    alert("Error al registrar.");
    window.location.href="../HTML/formulario.html";
    </script>' . $stmt->error;
}

$stmt->close();
$con->close();
?>
