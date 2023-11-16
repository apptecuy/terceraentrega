<?php
include("../PHP/conectar2.php");
$mysqli = new mysqli("localhost", "apptec", "47743966", "bd_apptec");

if ($mysqli->connect_error) {
    die("Conexión a la base de datos fallida: " . $mysqli->connect_error);
}

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<link rel='stylesheet' type='text/css' href='../CSS/calificacionjuez.css' media='all'>";
echo "<title>Competidor</title>";
echo "</head>";
echo "<body>";
echo "<div class='calificacionjuez'>";
echo "<br>";
echo "<img src='../IMG/LogoCuk.png' alt='' class='Logocuk'>";

$host = 'localhost';
$db = 'bdkatascore';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if (isset($_POST['id_juez'])) {
    $id_juez = $_POST['id_juez'];
} else {
    echo "Error: id_juez no se ha recibido correctamente.";
}

echo "<h1>Interfaz de calificación</h1> <br>";

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT * FROM ejecucion WHERE estado = 'habilitado' AND estadocompetencia='activa'";
$resultado = $conn->query($sql);

echo "<div class='datacompetidor'>";

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $sql_competidor = "SELECT nombre, apellido, sexo, escuela FROM competidor WHERE id_competidor = ?";
        $stmt_competidor = $conn->prepare($sql_competidor);
        $stmt_competidor->bind_param("i", $fila['id_competidor']);
        $stmt_competidor->execute();
        $resultado_competidor = $stmt_competidor->get_result();

        if ($fila_competidor = $resultado_competidor->fetch_assoc()) {
            echo "<br>";
            echo "<p>Nombre del competidor: </p>";
            echo $fila_competidor["nombre"];
            echo "<br>";
            echo "<p>Apellido del competidor:</p>";
            echo  $fila_competidor["apellido"];
            echo "<br>";
            echo "<br>";
        }

        $sql_categoria = "SELECT categoria FROM ejecucion WHERE id_ejecucion = ?";
        $stmt_categoria = $conn->prepare($sql_categoria);
        $stmt_categoria->bind_param("i", $fila['id_ejecucion']);
        $stmt_categoria->execute();
        $resultado_categoria = $stmt_categoria->get_result();

        if ($fila_categoria = $resultado_categoria->fetch_assoc()) {
            echo "Categoría: " . $fila_categoria['categoria'] . "<br>";
        }

        echo "<br>";
        echo "<p>Kata realizados anteriormente:  </p>";

        $sql_competencia = "SELECT nombre FROM competencia WHERE id_competencia = ?";
        $stmt_competencia = $conn->prepare($sql_competencia);
        $stmt_competencia->bind_param("i", $fila['id_competencia']);
        $stmt_competencia->execute();
        $resultado_competencia = $stmt_competencia->get_result();
        $sql_realiza = "SELECT id_kata FROM realiza WHERE id_ejecucion = ?";
        $stmt_realiza = $conn->prepare($sql_realiza);
        $stmt_realiza->bind_param("i", $fila['id_ejecucion']);
        $stmt_realiza->execute();
        $resultado_realiza = $stmt_realiza->get_result();

        if ($fila_realiza = $resultado_realiza->fetch_assoc()) {
            $id_kata = $fila_realiza['id_kata'];
            $sql_kata = "SELECT nombre FROM kata WHERE id_kata = ?";
            $stmt_kata = $conn->prepare($sql_kata);
            $stmt_kata->bind_param("i", $id_kata);
            $stmt_kata->execute();
            $resultado_kata = $stmt_kata->get_result();

            if ($fila_kata = $resultado_kata->fetch_assoc()) {
                $nombre_kata = $fila_kata['nombre'];
                echo '<p class="kataarealizar"><br>Kata a realizar: <br><br>' . $nombre_kata. '</p>';
            }
        }
        echo "</div>";
        echo "<br>";
        echo "<div class='calificacioncaja'>";

        if (isset($fila["id_ejecucion"])) {
            echo '
                <p class="numerocalificacion"><br>Calificación:<br> <br> <span id="calificacion">5.0</span> <br></p>
                <div class="botonentero">
                <button class="botoncali" onclick="incrementarEntero()">▲</button>
                <br>
                <button class="botoncali" onclick="disminuirEntero()">▼</button>
                </div>
                <div class="botondecimal">
                <button class="botoncali" onclick="incrementarDecimal()">▲ </button>
                <br>
                <button class="botoncali" onclick="disminuirDecimal()">▼</button>
                </div>
                <form method="post" action="">
                    <input type="hidden" id="id_juez" name="id_juez" value="'.$_POST["id_juez"].'">
                    <input type="hidden" id="id_ejecucion" name="id_ejecucion" value="'.$fila["id_ejecucion"].'">
                    <input type="hidden" id="calificacion_input" name="calificacion">
                    <input type="submit" class="calificarboton" value="Calificar">
                    <button onclick="descalificar()" class="descalificarboton" >Descalificar</button>
                </form>
                <script>
                    var calificacionEntero = 5;
                    var calificacionDecimal = 0;

                    function incrementarEntero() {
                        if (calificacionEntero < 10) {
                            calificacionEntero++;
                            actualizarCalificacion();
                        }
                    }

                    function disminuirEntero() {
                        if (calificacionEntero > 5) {
                            calificacionEntero--;
                            actualizarCalificacion();
                        }
                    }

                    function incrementarDecimal() {
                        if (calificacionDecimal < 9) {
                            calificacionDecimal++;
                            actualizarCalificacion();
                        }
                    }

                    function disminuirDecimal() {
                        if (calificacionDecimal > 0) {
                            calificacionDecimal--;
                            actualizarCalificacion();
                        }
                    }

                    function actualizarCalificacion() {
                        var calificacion = calificacionEntero + "." + calificacionDecimal;
                        document.getElementById("calificacion").innerHTML = calificacion;
                        document.getElementById("calificacion_input").value = calificacion;
                    }

                    function descalificar() {
                        calificacionEntero = 0;
                        calificacionDecimal = 0;
                        actualizarCalificacion();
                    }
                </script>
            ';
        }
        echo "<br>";
        echo "</div>";
    }
} else {
    echo "Esperando competidor...";
    echo "<script>setTimeout(function(){location.reload();}, 1000);</script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id_juez"]) && isset($_POST["id_ejecucion"]) && isset($_POST["calificacion"])) {
        $id_juez = $_POST["id_juez"];
        $id_ejecucion = $_POST["id_ejecucion"];
        $calificacion = $_POST["calificacion"];
        if (isset($_POST["descalificar"])) {
            $calificacion = 0.0;
        }

        
        $verificarExistencia = "SELECT COUNT(*) as count_existencia FROM califica WHERE id_ejecucion = ? AND id_juez = ?";
        $stmtExistencia = $mysqli->prepare($verificarExistencia);
        $stmtExistencia->bind_param("is", $id_ejecucion, $id_juez);
        $stmtExistencia->execute();
        $resultadoExistencia = $stmtExistencia->get_result();
        $rowExistencia = $resultadoExistencia->fetch_assoc();

    
    if ($rowExistencia['count_existencia'] > 0) {
        echo '<script>
                alert("Ya has calificado al competidor en esta ejecución.");
                window.location.href = "logueo_juez.php";
              </script>';
        exit(); 
    }
         else {
        
            $sql_calificar = "INSERT INTO califica (id_ejecucion, id_juez, calificacion) VALUES (?, ?, ?)";
            $stmt_calificar = $mysqli->prepare($sql_calificar);
            $stmt_calificar->bind_param("iss", $id_ejecucion, $_POST["id_juez"], $_POST["calificacion"]);
            echo '<div class="mensajespam">';
            if ($stmt_calificar->execute()) {
                echo "<p class='calificacionexito'>Calificación enviada con éxito.</p>";
            } else {
                echo "<p class='calificacionerror'>Error al enviar la calificación: </p> " . $stmt_calificar->error;
                echo "Valor de id_ejecucion: " . $id_ejecucion;
            }
            echo '<script>window.location.href = "logueo_juez.php";</script>';
        }
        echo "</div>";
    }
}

$mysqli->close();
?>

</body>
</html>
