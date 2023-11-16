<?php
$mysqli = new mysqli("localhost", "root", "", "bdkatascore");

if ($mysqli->connect_error) {
    die("Conexión a la base de datos fallida: " . $mysqli->connect_error);
}

echo "ID de Ejecución recibido: $id_ejecucion"; 

if (isset($_GET['id_ejecucion']) && is_numeric($_GET['id_ejecucion'])) {
    $id_ejecucion = $_GET['id_ejecucion'];

    $consultaCalificaciones = "SELECT id_juez, calificacion FROM califica WHERE id_ejecucion = $id_ejecucion";
    $resultadoCalificaciones = $mysqli->query($consultaCalificaciones);

echo "Calificaciones obtenidas: " . json_encode($calificaciones); 
    if ($resultadoCalificaciones) {
        echo "<h3>Calificaciones de los Jueces</h3>";
        echo "<table>";
        echo "<tr><th>Juez</th><th>Calificación</th></tr>";

        while ($filaCalificacion = $resultadoCalificaciones->fetch_assoc()) {
            $nombreJuez = $filaCalificacion['nombre_juez'];
            $calificacion = $filaCalificacion['calificacion'];

            echo "<tr><td>$nombreJuez</td><td>$calificacion</td></tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No se encontraron calificaciones de los jueces.</p>";
    }
} else {
    echo "ID de ejecución no válido.";
}

$mysqli->close();
?>
