<?php

$con = new mysqli("localhost", "root", "", "bdkatascore");
if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}


$competencia = $_POST['competencia'];
$categoria = $_POST['categoria'];
$sexo = $_POST['sexo'];



$sql = "SELECT c.id_competidor, c.nombre, c.apellido
        FROM competidor c
        INNER JOIN registra r ON c.id_competidor = r.id_competidor
        WHERE r.id_competencia = $competencia
        AND YEAR(CURDATE()) - YEAR(c.fecha_nac) BETWEEN 12 AND 100
        AND c.sexo = '$sexo'";

$result = $con->query($sql);


if ($result->num_rows > 0) {
    echo "<h2>Gestión de ronda:</h2>";
    echo "<form>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Ganador</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row['id_competidor'] . "</td><td><a href='gestion_competidor.php?id=" . $row['id_competidor'] . "'>" . $row['nombre'] . "</a></td><td>" . $row['apellido'] . "</td><td><input type='checkbox' name='ganador[]' value='" . $row['id_competidor'] . "'></td></tr>";
    }
    echo "</table>";
    echo "<input type='submit' value='Siguiente ronda'>";
    echo "</form>";
} else {
    echo "No se encontraron competidores para los filtros seleccionados.";
}

$con->close();
?>


