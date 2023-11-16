<?php
include("conectar2.php");

$id = $_GET['id_competencia'];
$con = conectar();


$sqlCheck = "SELECT COUNT(*) as count_ejecucion FROM ejecucion WHERE id_competencia = '$id'";
$resultCheck = mysqli_query($con, $sqlCheck);
$rowCheck = mysqli_fetch_assoc($resultCheck);

if ($rowCheck['count_ejecucion'] > 0) {
    
    echo '<script>alert("No se puede eliminar una competencia con categorías y sorteos ya realizados.");</script>';
    echo '<script>window.location.href = "modificar_competencia.php";</script>';
    exit(); 
}

$sqlDelete = "DELETE FROM competencia WHERE id_competencia = '$id'";
$res = mysqli_query($con, $sqlDelete);


if ($res) {
    Header("Location:modificar_competencia.php");
} else {
    
    echo '<script>alert("Error al intentar eliminar la competencia.");</script>';
    echo '<script>window.location.href = "modificar_competencia.php";</script>';
    exit(); 
}
?>
