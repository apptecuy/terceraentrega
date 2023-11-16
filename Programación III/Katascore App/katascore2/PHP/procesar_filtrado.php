<?php

$competencia = $_POST['competencia'];
$categoria = $_POST['categoria'];
$sexo = $_POST['sexo'];

$con = new mysqli("localhost", "root", "emi093908463", "bdkatascore");


if ($con->connect_error) {
    die("Error de conexión: " . $con->connect_error);
}


$sql = "SELECT c.id_competidor, c.nombre, c.apellido
        FROM competidor c
        INNER JOIN registra r ON c.id_competidor = r.id_competidor
        WHERE r.id_competencia = $competencia
        AND YEAR(CURDATE()) - YEAR(c.fecha_nac) BETWEEN 12 AND 100
        AND c.sexo = '$sexo'";

$result = $con->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Competidores inscritos:</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Apellido</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row['id_competidor'] . "</td><td>" . $row['nombre'] . "</td><td>" . $row['apellido'] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron competidores para los filtros seleccionados.";
}


$con->close();
?>

<form action="sorteo.php" method="POST">
    <input type="hidden" name="competencia" value="<?php echo $competencia; ?>">
    <input type="hidden" name="categoria" value="<?php echo $categoria; ?>">
    <input type="hidden" name="sexo" value="<?php echo $sexo; ?>">
    <input type="submit" value="Realizar sorteo">
</form>
