<?php
include("conectar2.php");

$id = $_GET['id'];
$con = conectar();


$sql_check_referencias = "SELECT COUNT(*) as count_referencias FROM ejecucion WHERE id_competidor = '$id'";
$result_check_referencias = mysqli_query($con, $sql_check_referencias);

if ($result_check_referencias) {
    $row = mysqli_fetch_assoc($result_check_referencias);
    $count_referencias = $row['count_referencias'];

    if ($count_referencias > 0) {
        echo '<script>alert("No se puede eliminar un competidor que ya está inscripto en una competencia."); window.location.href = "modificar.php";</script>';
    } else {
     
        $sql_eliminar = "DELETE registra, competidor 
                        FROM registra 
                        JOIN competidor ON registra.id_competidor = competidor.id_competidor 
                        WHERE registra.id_competidor = '$id'";

        $res = mysqli_query($con, $sql_eliminar);

        if ($res) {
            echo '<script>alert("Registro eliminado exitosamente."); window.location.href = "modificar.php";</script>';
        } else {
            echo '<script>alert("Error al eliminar el registro: ' . mysqli_error($con) . '"); window.location.href = "modificar.php";</script>';
        }
    }
} else {
    echo '<script>alert("Error al verificar referencias: ' . mysqli_error($con) . '"); window.location.href = "modificar.php";</script>';
}

mysqli_close($con);
?>

