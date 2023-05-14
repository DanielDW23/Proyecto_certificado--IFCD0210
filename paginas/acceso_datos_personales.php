<?php session_start();

 /*Incluimos el archivo de conexión a la base de datos*/
	
	include("conexion.php");

/*Iniciamos la sesión guardada*/

    
    $correo = $_SESSION['usuario'];

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
    <title>AREA CLIENTES CENTRO ESTETICO</title>

    <link rel="stylesheet" type="text/css" href="../css/acceso_datos_personales.css">

</head>
<body>
    
    <div id="contenedor">

            <div id="cabecera">
                <h4><?php  $consulta_nombre = "SELECT * FROM clientes WHERE email = '$correo' "; 
				$resultado_nombre = mysqli_query($conexion,$consulta_nombre); 
				$fila = mysqli_fetch_array($resultado_nombre);
				echo $fila["Nombre"]; echo " "; echo $fila["Apellidos"] ?>, estos son los datos de contacto que tenemos guardados en tu historial, si ves cualquier dato erróneo o quieres actualizar alguno no dudes en contactar con nosotros.  <span style='font-size:18px;'>&#128231;</span><a href="mailto:atencioncliente@centrolgmadrid.com?Subject=Actualizar%20datos%20personales">Atención al cliente</a> </h4>
            </div>

        

        <div id="central">
	
            <div id="contenido_datos">
			
				<div class = "titulo">

				DATOS PERSONALES DEL CLIENTE REGISTRADOS

				</div>
				
                    <div id="tabla">
                
                    <?php
                            /* Hacemos una consulta en la tabla clientes, con lo que conseguiremos los datos almacenados en el historial del usuario logueado, algunos de los campos de la tabla no se muestran al cliente al ser información interna del centro estético*/
                                    
                            /*He ejecutado en phpMyadmin  la orden "SET GLOBAL lc_time_names = 'es_ES';" para que en el cumpleaños salga los meses en castellano */
                            
                            $consulta_datos = "SELECT Nombre, Apellidos, Direccion, Telefono, email, nacimiento FROM clientes WHERE email = '$correo'";
                    ?>
                                <!--Creamos una tabla para que figuren las citas pendientes-->
                                <table border="1" cellspacing="0" >

                                    <thead>
                                        <tr>
                                        <td class = "cabecera_tabla">NOMBRE</td>
                                        <td class = "cabecera_tabla">APELLIDOS</td>
                                        <td class = "cabecera_tabla">DIRECCION</td>
                                        <td class = "cabecera_tabla">TELEFONO</td>
                                        <td class = "cabecera_tabla">CORREO ELECTRONICO</td>
                                        <td class = "cabecera_tabla">CUMPLEAÑOS</td>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                            $resultado_consulta_datos = mysqli_query($conexion,$consulta_datos);
                                            while ($registro = mysqli_fetch_array($resultado_consulta_datos))
                                            { //Empieza código del bucle while.

                                                /*<Ponemos  la fecha del cumpleaños con día y mes, el mes del cumpleaños en texto en castellano*/
                                                setlocale(LC_TIME, "es_ES.UTF-8");
                                                $valor_cumple = strtotime($registro["nacimiento"]);
                                                $cumple_esp = strftime("%e de %B", $valor_cumple);
                                              
       
                                        ?>

                                        <tr>
                                        <td class = "datos_formato"><?php echo $registro["Nombre"]; ?></td>
                                        <td class = "datos_formato"><?php echo $registro["Apellidos"]; ?></td>
                                        <td class = "datos_formato"><?php echo $registro["Direccion"]; ?></td>
                                        <td class = "datos_formato"><?php echo $registro["Telefono"]; ?></td>
                                        <td class = "datos_formato"><?php echo $registro["email"]; ?></td>
                                        <td class = "datos_formato"><?php echo $cumple_esp; ?></td>
                                        </tr>
                                        <?php }  //Termina código del bucle while.
                                        ?>
                                    </tbody>
                
                                    </table>
                
                        </div>

                        <h4><span style='font-size:30px;'>&#8630;</span> <a href="acceso_principal.php">Volver a la página principal de clientes</a></h4>
                        
                        
            </div>
						<div id="enlace_cerrar_sesion">
                            <a href="cerrar_sesion.php">(Cerrar sesión)</a>
                        </div>

        </div>


    </div>
</body>
</html>