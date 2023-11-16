<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../CSS/publico.css">
</head>
<body>
<div class="sorteopublico">
    <br>
    <img src="../IMG/LogoCuk.png" alt="" class="logo4">
<?php
 $mysqli = new mysqli("localhost", "apptec", "47743966", "bd_apptec");

if ($mysqli->connect_error) {
    die("Conexión a la base de datos fallida: " . $mysqli->connect_error);
}

$idcompetencia = $_GET['id_competencia'];
$idcategoria = $_GET['categoria'];



$sql_resultados = "SELECT id_competidor, resultado FROM resultadocompetencia
                  WHERE id_competencia = $idcompetencia AND categoria = '$idcategoria'
                  ORDER BY CASE resultado
                      WHEN 'primero' THEN 1
                      WHEN 'segundo' THEN 2
                      WHEN 'tercero' THEN 3
                      WHEN 'quinto' THEN 4
                      ELSE 5
                  END";


$result_resultados = $mysqli->query($sql_resultados);

if ($result_resultados) {
    echo "<h3 class=resulcomp>Resultados para la competencia: $idcompetencia <br>Categoría: $idcategoria </h3><br>";
    echo "<ul>";

    while ($row = $result_resultados->fetch_assoc()) {
        echo "<li>Competidor ID: " . $row['id_competidor'] . " - Resultado: " . $row['resultado'] . "</li>";
    }

    echo "</ul>";
} else {
    echo "Error en la consulta: " . $mysqli->error;
}
$mysqli->close();
?>
<br>

<br>
<form action="mostrar_competidores_puntaje.php">
    <input type="submit" value="Volver" class="boton-competidor4">
</form>
<br>
</div>
</body>
</html>
