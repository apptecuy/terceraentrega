<?php
include("../PHP/conectar2.php");
$mysqli = new mysqli("localhost", "apptec", "47743966", "bdapptec");

if ($mysqli->connect_error) {
    die("Conexión a la base de datos fallida: " . $mysqli->connect_error);
}

$id_ejecucion = isset($_GET['id_ejecucion']) ? $_GET['id_ejecucion'] : null;
$id_competidor = isset($_GET['id_competidor']) ? $_GET['id_competidor'] : null;

if (isset($id_ejecucion) && is_numeric($id_ejecucion)) {
    $consultaEjecucion = "SELECT id_ejecucion, id_competidor, cinturon, id_competencia, categoria, tatami FROM ejecucion WHERE id_ejecucion = ?";
    $stmt = $mysqli->prepare($consultaEjecucion);
    $stmt->bind_param("i", $id_ejecucion);
    $stmt->execute();
    $resultadoEjecucion = $stmt->get_result();

    if ($resultadoEjecucion && $resultadoEjecucion->num_rows > 0) {
        $filaEjecucion = $resultadoEjecucion->fetch_assoc();
        $id_competidor = $filaEjecucion['id_competidor'];

        
        $consultaCompetidor = "SELECT nombre, apellido, escuela FROM competidor WHERE id_competidor = ?";
        $stmt = $mysqli->prepare($consultaCompetidor);
        $stmt->bind_param("i", $id_competidor);
        $stmt->execute();
        $resultadoCompetidor = $stmt->get_result();

        if ($resultadoCompetidor && $resultadoCompetidor->num_rows > 0) {
            $filaCompetidor = $resultadoCompetidor->fetch_assoc();
        }

       
        if (isset($_POST['submit_kata'])) {
            
            $id_kata = isset($_POST['id_kata']) ? $_POST['id_kata'] : null;

            if (is_numeric($id_kata)) {
            
                $insertarRealiza = "INSERT INTO realiza (id_ejecucion, id_kata) VALUES (?, ?)";
                $stmt = $mysqli->prepare($insertarRealiza);
                $stmt->bind_param("ii", $id_ejecucion, $id_kata);
                if ($stmt->execute()) {
                    
                    echo '<script>mostrarMensajeKata();</script>';
                } else {
                    echo "Error al registrar la selección del Kata: " . $stmt->error;
                }
            }
        }

    
        if (isset($_POST['habilitar'])) {
          
            $actualizarEstado = "UPDATE ejecucion SET estado = 'habilitado' WHERE id_ejecucion = ?";
            $stmt = $mysqli->prepare($actualizarEstado);
            $stmt->bind_param("i", $id_ejecucion);
            if ($stmt->execute()) {
               
                header("Location: gestionar_calificacion.php?id_ejecucion=$id_ejecucion&id_competidor=$id_competidor");
                exit();
            } else {
                echo "Error al habilitar la ejecución: " . $stmt->error;
            }
        }
    }
}

$consultaKata = "SELECT id_kata, nombre FROM kata";
$resultadoKata = $mysqli->query($consultaKata);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestionar Ejecución</title>
    <link rel="stylesheet" href="../CSS/sorteo.css">
    <script>
        function mostrarMensajeKata() {
            document.getElementById("mensaje-kata").style.display = "block";
        }
    </script>
</head>
<body>
    <div class="infocompetidor">
        <br>
        <img src="../IMG/LogoCuk.png" alt="LogoCuk" class="logo3" width="120" height="120">
        <?php if (isset($filaEjecucion) && isset($filaCompetidor)) : ?>
            <h2>Datos de Ejecución</h2>
            <p>ID de Ejecución: <?php echo $filaEjecucion['id_ejecucion']; ?></p>
            <p>ID de Competidor: <?php echo $id_competidor; ?></p>
            <p>Cinturón: <?php echo $filaEjecucion['cinturon']; ?></p>
            <p>ID de Competencia: <?php echo $filaEjecucion['id_competencia']; ?></p>
            <p>Categoría: <?php echo $filaEjecucion['categoria']; ?></p>
            <p>Tatami: <?php echo $filaEjecucion['tatami']; ?></p>

            <h2>Datos del Competidor</h2>
            <p>Nombre: <?php echo $filaCompetidor['nombre']; ?></p>
            <p>Apellido: <?php echo $filaCompetidor['apellido']; ?></p>
            <p>Escuela: <?php echo $filaCompetidor['escuela']; ?></p>

            <h2>Seleccionar Kata</h2>
            <form method="post" action="">
                <select name="id_kata" class="listakata">
                    <?php
                    while ($filaKata = $resultadoKata->fetch_assoc()) {
                        echo '<option value="' . $filaKata['id_kata'] . '">' . $filaKata['nombre'] . '</option>';
                    }
                    ?>
                </select>
                <input type="submit" value="Seleccionar Kata" name="submit_kata">
            </form>
            <span id="mensaje-kata" style="color: white; display: none;">Kata seleccionado correctamente.</span>
            <br>
            <br>
            <form method="post" action="">
                <input type="hidden" name="id_ejecucion" value="<?php echo $filaEjecucion['id_ejecucion']; ?>">
                <input type="hidden" name="id_competidor" value="<?php echo $id_competidor; ?>">
                <input type="submit" value="Habilitar" name="habilitar" class="habilitar">
            </form>
            <br>
            <br>
        <?php else : ?>
            <p>No se encontraron datos para esta ejecución.</p>
        <?php endif; ?>
        <input type="button" value="Volver" class="volver" onclick="window.history.back();" />
        <br>
        <br>
    </div>
</body>
</html>
