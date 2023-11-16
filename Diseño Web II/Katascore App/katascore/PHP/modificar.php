<?php
require("conectar2.php");
$conn = conectar();
$filtro_id = '';
if (isset($_GET['id_competidor']) && !empty($_GET['id_competidor'])) {
    $filtro_id = $_GET['id_competidor'];
    $sql = "SELECT * FROM competidor WHERE id_competidor = $filtro_id";
} else {
    $sql = "SELECT * FROM competidor";
}

$resultado = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/modificar.css">
    <title>Document</title>
</head>
<body>
<div class="modificar">
    <div class="cabecera">
        <h1 class="compinsc">Competidores inscriptos:</h1>
        <div class="filtrarid">
        <form method="GET" action="">
            <label for="filtro_id">Filtrar por ID de Competidor:</label>
            <input type="text" name="id_competidor" id="filtro_id" value="<?= $filtro_id ?>">
            <input type="submit" value="Filtrar">
        </form>
        </div>
        <img src="../IMG/LogoCuk.png" alt="Logo" class="logo" height="120px" width="120px">
        <form action="../HTML/menu_admin2.html">
            <input type="submit" class="volvermod" name="volver" value="Volver" >
        </form>
    </div>
    <br><br>

    <div class="modificarinfo">
       
        <table width="100%" cellpadding="6" cellspacing="7">
            <tr>
                <th class="id">Cédula de Identidad</th>
                <th class="nombre">Nombre</th>
                <th class="apellido">Apellido</th>
                <th class="sexo">Sexo</th>
                <th class="fecha_nac">Fecha de nacimiento</th>
                <th class="escuela">Escuela</th>
                <th class="ranking">Ranking</th>
                <th>Acción</th>
            </tr>
            <br>

            <?php while($fila = mysqli_fetch_array($resultado)) :?>
                <tr>
                    <td><?= $fila['id_competidor']?></td>
                    <td><?= $fila['nombre']?></td>
                    <td><?= $fila['apellido']?></td>
                    <td><?= $fila['sexo']?></td>
                    <td><?= $fila['fecha_nac']?></td>
                    <td><?= $fila['escuela']?></td>
                    <td><?= $fila['ranking']?></td>
                    <td><a href="editar.php?id= <?= $fila['id_competidor'] ?>"><button>Editar</button></a></td>
                    <td><a href="javascript:void(0);" onclick="confirmarEliminar(<?= $fila['id_competidor'] ?>)"><button>Eliminar</button></a></td>
                </tr>
            <?php endwhile;?>
      
      </table>
    
    </div>
    <br>
    <br>
</div>

<script>
function confirmarEliminar(idCompetidor) {
    var confirmacion = confirm("¿Estás seguro de eliminar los datos?");
    if (confirmacion) {
        window.location.href = "eliminar.php?id=" + idCompetidor;
    } else {
       
    }
}
</script>

</body>
</html>
