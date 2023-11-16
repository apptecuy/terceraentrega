<?php
include('conectar2.php');

$con = conectar(); 

if (isset($_GET['id_competidor'])) {
    $id_competidor = $_GET['id_competidor'];
} else {
    die("ID de competidor no válido.");
}

$sql = "SELECT id_competencia FROM competencia";
$resultado = mysqli_query($con, $sql);


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vincular Competidor a Competencia</title>
    <link rel="stylesheet" href="../CSS/competenciacompetidor.css"> 
</head>
<body >
    <div class ="vincularcompetidor">
        <img src="../IMG/LogoCuk.png" alt="" class="logoformulario">
    <h1 class="titulo2">Vincular Competidor a Competencia</h1>
    <br>
    <form method='post' action='procesar_vinculacion.php'>
        <input type='hidden' name='id_competidor' value='<?php echo $id_competidor; ?>'>
        <label>Seleccione una competencia:</label>
        <select name='id_competencia' class="id_competencia" id="competenciaSelect">

            <?php
            while ($fila = mysqli_fetch_assoc($resultado)) {
            ?>
            <option value='<?php echo $fila['id_competencia']; ?>'><?php echo $fila['id_competencia']; ?></option>
            <?php
            }
            ?>
        </select> 
        

        <br><br>

        <label>Seleccione una categoría:</label>
<select name='categoria' class="vincularcategoria">
    <option value='12/13 Años Masculino'>12/13 Años Masculino</option>
    <option value='12/13 Años Femenino'>12/13 Años Femenino</option>
    <option value='14/15 Años Masculino'>14/15 Años Masculino</option>
    <option value='14/15 Años Femenino'>14/15 Años Femenino</option>
    <option value='16/17 Años Masculino'>16/17 Años Masculino</option>
    <option value='16/17 Años Femenino'>16/17 Años Femenino</option>
    <option value='Mayores Masculino'>Mayores Masculino</option>
    <option value='Mayores Femenino'>Mayores Femenino</option>
</select>
       
     <br><br>
            <label>Validación de ficha médica:</label>
            <input  type='checkbox' class ="checkbox" name='validacion_ficha_medica' required>
       
<br><br>
        
            <label>Comprobante de pago:</label>
            <input type='checkbox' class ="checkbox" name='comprobante_pago' required>
  <br><br> 
        <input type='submit' value='Vincular' class="vincular">
    </form>
    <form action="vincular_competencia.php">
        <input type="submit" value="Volver" class="volverformulario">

  
        </form>
   </div>
</body>
</html>
