<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/resultadofinal.css">
    <title>Resultado Final</title>
</head>
<body>


<div class="resultadofinal" >
    <br>
    <img src="../IMG/LogoCuk.png" class="imagen" alt="">
<?php
 $mysqli = new mysqli("localhost", "apptec", "47743966", "bd_apptec");

if ($mysqli->connect_error) {
    die("Error en la conexión a la base de datos: " . $mysqli->connect_error);
}
echo  "<br>";
$competencia = $_POST["competencia"];
echo "<h4> Competencia: $competencia</h4>";
echo  "<br>";
$categoria = $_POST["categoria"];
echo  "<h4> Categoria: $categoria</h4>";
echo  "<br>";
echo  "<br>";
$ganadorAKA = $_POST["competidor1"];

$ganadorAO = $_POST["competidor2"];





function obtenerPuntajeTotal($mysqli, $competencia, $categoria, $idCompetidor) {
    $query = "SELECT puntaje_total
              FROM ejecucion
              WHERE id_competencia = ? AND categoria = ? AND id_competidor = ? AND ronda = 'final'";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("iss", $competencia, $categoria, $idCompetidor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['puntaje_total'];
    } else {
        return 0; 
    }
}


$puntajeCompetidorGanadorAKA = obtenerPuntajeTotal($mysqli, $competencia, $categoria, $ganadorAKA);
$puntajeCompetidorGanadorAO = obtenerPuntajeTotal($mysqli, $competencia, $categoria, $ganadorAO);


$resultadoCompetidorGanadorAKA = "segundo";
$resultadoCompetidorGanadorAO = "primero";

if ($puntajeCompetidorGanadorAKA > $puntajeCompetidorGanadorAO) {
    $resultadoCompetidorGanadorAKA = "primero";
    $resultadoCompetidorGanadorAO = "segundo";
}



function almacenarResultados($mysqli, $competencia, $categoria, $idCompetidor, $resultado) {
    $query = "INSERT INTO resultadocompetencia (id_competidor, id_competencia, categoria, resultado) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("iiss", $idCompetidor, $competencia, $categoria, $resultado);
    $stmt->execute();
}

$resultadoExistenteAKA = obtenerResultadoExistente($mysqli, $competencia, $categoria, $ganadorAKA);


$resultadoExistenteAO = obtenerResultadoExistente($mysqli, $competencia, $categoria, $ganadorAO);

if ($resultadoExistenteAKA !== null) {
    echo "Resultado existente para ganadorAKA: " . $resultadoExistenteAKA;
} else {
    almacenarResultados($mysqli, $competencia, $categoria, $ganadorAKA, $resultadoCompetidorGanadorAKA);
}

if ($resultadoExistenteAO !== null) {
    echo "Resultado existente para ganadorAO: " . $resultadoExistenteAO;
} else {
    almacenarResultados($mysqli, $competencia, $categoria, $ganadorAO, $resultadoCompetidorGanadorAO);
}

function obtenerResultadoExistente($mysqli, $competencia, $categoria, $idCompetidor) {
    $query = "SELECT resultado
              FROM resultadocompetencia
              WHERE id_competencia = ? AND categoria = ? AND id_competidor = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("iss", $competencia, $categoria, $idCompetidor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['resultado'];
    } else {
        return null; 
    }
}

echo "Resultados almacenados exitosamente.";

$query = "SELECT id_competidor, resultado
          FROM resultadocompetencia
          WHERE id_competencia = ? AND categoria = ?
          ORDER BY FIELD(resultado, 'primero', 'segundo', 'tercero', 'quinto')";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("is", $competencia, $categoria);
$stmt->execute();
$result = $stmt->get_result();

echo "<h4>Resultados de la competencia $competencia en la categoría $categoria:</h4>";

while ($row = $result->fetch_assoc()) {
    echo "ID Competidor: " . $row['id_competidor'] . ", Resultado: " . $row['resultado'] . "<br>";
}
echo  "<br>";
$stmt->close();

?>
    </div>
    </body>
</html>
