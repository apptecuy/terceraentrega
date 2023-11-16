<?php
include("conectar2.php");
$con = conectar();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/Nuevaspestañasadmin2.css">
   
    <title>Competencias anteriormente creadas</title>
</head>
<body>
    <div class="Competenciascreadas">
        <h1> Competencias anteriormente creadas:</h1>
        <br>
        <div class="listadecompetencias">
            <?php
            $sql = "SELECT * FROM competencia";
            $resultado = mysqli_query($con, $sql);

            if (!$resultado) {
                die("Error en la consulta: " . mysqli_error($con));
            }
            ?>
            <table cellspacing="2" cellpadding="10">
                <tr>
                    <th>Id competencia</th>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Lugar</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
                <?php
                while ($fila = mysqli_fetch_array($resultado)) :?>
                    <tr class="listacomp">
                        <td class="idcomp"><?= $fila['id_competencia'] ?></td>
                        <td class="nombrecomp"><?= $fila['nombre'] ?></td>
                        <td class="fechacomp"><?= $fila['fecha'] ?></td>
                        <td class="horacomp"><?= $fila['hora'] ?></td>
                        <td class="lugarcomp"><?= $fila['lugar'] ?></td>
                        <td class="editcomp"><a href="editarcompetencia.php?id_competencia=<?= $fila['id_competencia'] ?>"><button>Editar</button></a></td>
                        <td class="elicomp"><a href="eliminarcompetencia.php?id_competencia=<?= $fila['id_competencia'] ?>"><button>Eliminar</button></a></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <br>
        <form action="../HTML/competencias.html">
            <input type="submit" name="regresar" id="" value="Volver" class="regresar">
        </form>
        <br>
    </div>
</body>
</html>
