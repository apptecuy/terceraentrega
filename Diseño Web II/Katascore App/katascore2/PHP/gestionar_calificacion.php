<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/gestionarcalificacion.css">
    <title>Competidor</title>
</head>
<body>
    <div class="gestionarcal">
        <br>
        <img src="../IMG/LogoCuk.png" alt="" class="logo">
        <h1>Gestion del competidor</h1>
<?php
$mysqli = new mysqli("localhost", "root", "", "bdkatascore");

if ($mysqli->connect_error) {
    die("Conexión a la base de datos fallida: " . $mysqli->connect_error);
}

$id_ejecucion = isset($_GET['id_ejecucion']) ? $_GET['id_ejecucion'] : null;
$id_competidor = isset($_GET['id_competidor']) ? $_GET['id_competidor'] : null;

if (isset($id_ejecucion) && is_numeric($id_ejecucion) && isset($id_competidor) && is_numeric($id_competidor)) {
    $consultaEjecucion = "SELECT tatami, cinturon FROM ejecucion WHERE id_ejecucion = ?";
    $stmt = $mysqli->prepare($consultaEjecucion);
    $stmt->bind_param("i", $id_ejecucion);
    $stmt->execute();
    $resultadoEjecucion = $stmt->get_result();

    if ($resultadoEjecucion) {
        $filaEjecucion = $resultadoEjecucion->fetch_assoc();
        $tatami = $filaEjecucion['tatami'];
        $cinturon = $filaEjecucion['cinturon'];
        echo "<p>Id ejecución: $id_ejecucion</p>";
        echo "<p>Tatami: $tatami</p>";
        echo "<p>Cinturón: $cinturon</p>";

        $consultaCompetidor = "SELECT nombre, apellido FROM competidor WHERE id_competidor = ?";
        $stmt = $mysqli->prepare($consultaCompetidor);
        $stmt->bind_param("i", $id_competidor);
        $stmt->execute();
        $resultadoCompetidor = $stmt->get_result();

        if ($resultadoCompetidor) {
            $filaCompetidor = $resultadoCompetidor->fetch_assoc();
            $nombreCompetidor = $filaCompetidor['nombre'];
            $apellidoCompetidor = $filaCompetidor['apellido'];

            echo "<p>Nombre del Competidor: $nombreCompetidor $apellidoCompetidor</p>";
        } else {
            echo "<p>No se encontró información del Competidor.</p>";
        }
    } else {
        echo "No se encontró información de Tatami y Cinturón.";
    }

    $consultaRealiza = "SELECT id_kata FROM realiza WHERE id_ejecucion = ?";
    $stmt = $mysqli->prepare($consultaRealiza);
    $stmt->bind_param("i", $id_ejecucion);
    $stmt->execute();
    $resultadoRealiza = $stmt->get_result();

    if ($resultadoRealiza) {
        $filaRealiza = $resultadoRealiza->fetch_assoc();
        $id_kata = $filaRealiza['id_kata'];

        $consultaKata = "SELECT nombre FROM kata WHERE id_kata = ?";
        $stmt = $mysqli->prepare($consultaKata);
        $stmt->bind_param("i", $id_kata);
        $stmt->execute();
        $resultadoKata = $stmt->get_result();

        if ($resultadoKata) {
            $filaKata = $resultadoKata->fetch_assoc();
            $nombreKata = $filaKata['nombre'];

            echo "<p>Nombre del Kata: $nombreKata</p>";
        } else {
            echo "<p>No se encontró información del Kata.</p>";
        }
    } else {
        echo "<p>No se encontró información del Kata.</p>";
    }
}


?>


<form method="post" action="">
    <input type="hidden" name="id_ejecucion" value="<?php echo $id_ejecucion; ?>">
    <input type="submit" value="Consultar Calificaciones" name="consultar_calificaciones">
</form>
<br>

<form method="post" action="">
    <input type="hidden" name="id_ejecucion" value="<?php echo $id_ejecucion; ?>">
    <input type="submit" value="Calcular Resultado Final" name="calcular_resultado_final">
</form>


<?php
if (isset($_POST['consultar_calificaciones'])) {

    $consultaCalificaciones = "SELECT id_juez, calificacion FROM califica WHERE id_ejecucion = ?";
    $stmt = $mysqli->prepare($consultaCalificaciones);
    $stmt->bind_param("i", $id_ejecucion);
    $stmt->execute();
    $resultadoCalificaciones = $stmt->get_result();

    if ($resultadoCalificaciones && $resultadoCalificaciones->num_rows > 0) {
        echo "<h2>Calificaciones</h2>";
       
        echo "<table>";
        echo "<tr><th>ID Juez</th><th>Calificación</th><th>Acción</th></tr>";

        while ($filaCalificacion = $resultadoCalificaciones->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $filaCalificacion['id_juez'] . "</td>";
            echo "<td>" . $filaCalificacion['calificacion'] . "</td>";
            echo "<td>";
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='id_ejecucion' value='" . $id_ejecucion . "'>";
            echo "<input type='hidden' name='id_juez' value='" . $filaCalificacion['id_juez'] . "'>";
            
            if ($filaCalificacion['calificacion'] == 0) {
             
                echo "<input type='submit' value='Confirmar Descalificación' name='confirmar_descalificacion'>";
            } else {
              
                echo "<input type='submit' value='Descalificar' name='descalificar_calificacion'>";
            }
            
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
       
    } else {
        echo "<p>No se encontraron calificaciones para esta ejecución.</p>";
    }
}

if (isset($_POST['calcular_resultado_final'])) {
   
    $consultaCalificaciones = "SELECT calificacion FROM califica WHERE id_ejecucion = ? ORDER BY calificacion DESC";
    $stmt = $mysqli->prepare($consultaCalificaciones);
    $stmt->bind_param("i", $id_ejecucion);
    $stmt->execute();
    $resultadoCalificaciones = $stmt->get_result();

    if ($resultadoCalificaciones && $resultadoCalificaciones->num_rows > 0) {
      
        $calificaciones = [];
        while ($filaCalificacion = $resultadoCalificaciones->fetch_assoc()) {
            $calificaciones[] = $filaCalificacion['calificacion'];
        }

        
        if (count($calificaciones) >= 3) {
            array_pop($calificaciones); 
            array_shift($calificaciones); 
            $resultado_final = array_sum($calificaciones);

            $actualizarPuntaje = "UPDATE ejecucion SET puntaje_total = ? WHERE id_ejecucion = ?";
            $stmt = $mysqli->prepare($actualizarPuntaje);
            $stmt->bind_param("di", $resultado_final, $id_ejecucion);
            $stmt->execute(); 
            $actualizarEstado = "UPDATE ejecucion SET estado = 'calificado' WHERE id_ejecucion = ?";
            $stmt = $mysqli->prepare($actualizarEstado);
            $stmt->bind_param("i", $id_ejecucion);
            $stmt->execute();
            
            
            $tiene_calificacion_cero = in_array(0, $calificaciones);
            
            if ($tiene_calificacion_cero) {
                $resultado_final = 0;
                $actualizarPuntaje = "UPDATE ejecucion SET puntaje_total = 0 WHERE id_ejecucion = ?";
                $stmt = $mysqli->prepare($actualizarPuntaje);
                $stmt->bind_param("i", $id_ejecucion);
                $stmt->execute(); 
            }
            
            echo "<h2>Resultado Final: $resultado_final</h2>";
        } else {
            echo "<p>No hay suficientes calificaciones para calcular el resultado final.</p>";
        }
    } else {
        echo "<p>No se encontraron calificaciones para esta ejecución.</p>";
    }
}

if (isset($_POST['descalificar_calificacion'])) {
    $id_juez = $_POST['id_juez'];
    $actualizarCalificacion = "UPDATE califica SET calificacion = 0 WHERE id_ejecucion = ? AND id_juez = ?";
    $stmt = $mysqli->prepare($actualizarCalificacion);
    $stmt->bind_param("ii", $id_ejecucion, $id_juez);
    $stmt->execute();

    echo "<p>Calificación descalificada con éxito.</p>";
}

if (isset($_POST['confirmar_descalificacion'])) {
    $actualizarPuntaje = "UPDATE ejecucion SET puntaje_total = 0 WHERE id_ejecucion = ?";
    $stmt = $mysqli->prepare($actualizarPuntaje);
    $stmt->bind_param("i", $id_ejecucion);
    $stmt->execute(); 
    $actualizarEstado = "UPDATE ejecucion SET estado = 'calificado' WHERE id_ejecucion = ?";
    $stmt = $mysqli->prepare($actualizarEstado);
    $stmt->bind_param("i", $id_ejecucion);
    $stmt->execute();

    echo "<h2>Resultado Final: 0</h2>";
    echo "<p>Descalificación confirmada con éxito.</p>";
}

$mysqli->close();
?>
<br>
<button onclick="goBack()">Volver</button>
<script>
function goBack() {
    window.history.back();
}
</script>
<br>
</div>
</body>
</html>
