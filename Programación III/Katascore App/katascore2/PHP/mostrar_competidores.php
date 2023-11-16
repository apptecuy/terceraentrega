<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/publico.css">
    <title>Sorteo</title>
</head>
<body>
    
<div class="mostrarcompetidor">
<?php
$mysqli = new mysqli("localhost", "root", "", "bdkatascore");

if ($mysqli->connect_error) {
    die("Conexión a la base de datos fallida: " . $mysqli->connect_error);
}

$sql = "SELECT id_competidor, cinturon, categoria, id_competencia
        FROM ejecucion
        WHERE estado = 'habilitado' AND estadocompetencia = 'activa'";

$result = $mysqli->query($sql);
$encontradoCompetidor = false;

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $idCompetidor = $row['id_competidor'];
        $cinturon = $row['cinturon'];
        $categoria = $row['categoria'];
        $competencia = $row['id_competencia'];

        $estiloKimono = ($cinturon === 'AKA') ? 'aka' : 'ao';
        $imagenKimono = ($cinturon === 'AKA') ? '../IMG/katarojo1.png' : '../IMG/kataazul1.png';

        echo "<div class='$estiloKimono'>";
        echo "<h2 class='categoria'>Categoría: $categoria</h2>";
        echo "<img src='$imagenKimono' class='kimono' alt='Kimono'>";

    
        $sqlDetalle = "SELECT 
            c.nombre AS nombre_competidor,
            c.apellido AS apellido_competidor,
            e.id_ejecucion,
            k.nombre AS nombre_kata
        FROM ejecucion AS e
        JOIN competidor AS c ON e.id_competidor = c.id_competidor
        JOIN realiza AS r ON e.id_ejecucion = r.id_ejecucion
        JOIN kata AS k ON r.id_kata = k.id_kata
        WHERE e.id_competencia = $competencia AND e.estado = 'habilitado'";

        $resultDetalle = $mysqli->query($sqlDetalle);

        if ($resultDetalle) {
            while ($rowDetalle = $resultDetalle->fetch_assoc()) {
                $nombreCompetidor = $rowDetalle['nombre_competidor'];
                $apellidoCompetidor = $rowDetalle['apellido_competidor'];
                $nombreKata = $rowDetalle['nombre_kata'];
                echo "<div class='infocompe'>";
                echo "<h4 class='titulonombre'> Nombre y apellido </h4>";
                echo "<p class='nombreyape'>$nombreCompetidor $apellidoCompetidor</p>";
                echo "<h4 class 'titulokata'> Kata a realizar </h4>";
                echo "<p class='kataarealizar'>$nombreKata</p>";
                echo "</div>";
            }
        } else {
            echo "Error en la consulta: " . $mysqli->error;
        }

        echo "</div>";
        $encontradoCompetidor = true;
    }

    if (!$encontradoCompetidor) {
        echo "<p>No se encontraron competidores para mostrar.</p>";
    }
} else {
    echo "Error en la consulta: " . $mysqli->error;
}

$mysqli->close();
?>

   <a href="sorteopublico.php"><img src="../IMG/LogoCuk.png" class="Logocuk" alt=""></a>
</div>
</body>
</html>
