<?php
include('conectar2.php');
$con = conectar(); 

if (isset($_POST['id_competidor']) && isset($_POST['id_competencia'])) {
    $id_competidor = $_POST['id_competidor'];
    $id_competencia = $_POST['id_competencia'];
    $categoria = $_POST['categoria'];


    
    $sql = "INSERT INTO registra (id_competidor, id_competencia, categoria) VALUES ('$id_competidor', '$id_competencia', '$categoria')";


    if (mysqli_query($con, $sql)) {
       
        echo '<script>alert("Competidor vinculado exitosamente a la competencia.");</script>';
        echo '<script>window.location.href = "vincular_competencia.php";</script>';
    } else {
      
        echo '<script>alert("Error al vincular competidor a la competencia: ' . mysqli_error($con) . '");</script>';
        echo '<script>window.location.href = "vincular_competencia.php";</script>';
    }
} else {
    echo "Error: Datos de vinculación incompletos.";
}
?>