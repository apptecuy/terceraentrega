<?php

$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "bdkatascore";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST["nombre_usuario"];
    $contraseña = $_POST["contraseña"];

    $sql = "SELECT * FROM usuario WHERE nombre_usuario = '$nombre_usuario' AND contraseña = '$contraseña'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $tipo = $row["tipo"];

        
        session_start();
        $_SESSION["nombre_usuario"] = $nombre_usuario;
        if ($tipo === "administrador") {
            header("location: ../HTML/menu_admin.html"); 
        } elseif ($tipo === "juez") {
            header("location: ../PHP/menu_juez.php"); 
        } elseif ($tipo === "publico") {
            header("location: ../PHP/menu_publico.php"); 
        }
        else {
            echo "Tipo de usuario desconocido.";
        }
    } else {
        echo "Credenciales incorrectas. Por favor, inténtalo de nuevo.";
    }
}

$conn->close();
?>
