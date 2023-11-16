<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $mysqli = new mysqli("localhost", "apptec", "47743966", "bd_apptec");

    if ($mysqli->connect_error) {
        die("La conexión a la base de datos falló: " . $mysqli->connect_error);
    }

    $estadoCompetencia = mysqli_real_escape_string($mysqli, $_POST['estadoCompetencia']);
    $competencia = mysqli_real_escape_string($mysqli, $_POST['competencia']);
    $categoria = mysqli_real_escape_string($mysqli, $_POST['categoria']);

    $updateQuery = "UPDATE ejecucion SET estadocompetencia = ? WHERE id_competencia = ? AND categoria = ?";
    $stmt = $mysqli->prepare($updateQuery);
    $stmt->bind_param("sss", $estadoCompetencia, $competencia, $categoria);
    $stmt->execute();

  
    $mysqli->close();

    echo "Estado actualizado con éxito.";
}
?>
