<?php session_start();
 
/*Comprobamos si existe una sesión con usuario activa, si no lo hay les mandamos a la página principal*/

    if (empty($_SESSION["usuario"])) {
        # Lo redireccionamos al formulario de inicio de sesión
        header("Location:../index.php");
        # Y salimos del script
        exit();
    
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONFIRMACION CITA</title>

    <link rel="stylesheet" type="text/css" href="../css/confirmacion_cita.css">
</head>
<body>

            <div id="contenedor">
                <div id = "cita_confirmada">
                    <h1>RESERVA DE CITA REALIZA CORRECTAMENTE</h1>
                    <h4><span style='font-size:30px;'>&#8630;</span> <a href="acceso_principal.php">Volver a la página principal de clientes</a></h4>
                </div>
            </div>

</body>
</html>