<?php session_start();

 /*Incluimos el archivo de conexión a la base de datos*/
	
	include("conexion.php");

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

    <link rel="stylesheet" type="text/css" href="../css/acceso_historial_citas.css">

</head>
<body>

    <div id="contenedor">
	<!-- Dentro de la cabecera ponemos el nombre completo de la persona logueada obtenido de la base de datos*/-->
	
            <div id="cabecera">
                <h4><?php  $consulta_nombre = "SELECT * FROM clientes WHERE email = '$correo' "; 
				$resultado_nombre = mysqli_query($conexion,$consulta_nombre); 
				$fila = mysqli_fetch_array($resultado_nombre);
				echo $fila["Nombre"]; echo " "; echo $fila["Apellidos"] ?>, aquí puedes encontrar tu historial de citas, con los servicios ya realizados en nuestro centro.</h4>
            </div>
        <div id="central">
		
			<div id="citas_pasadas">
                <div class="titulo">
                    HISTORIAL DE CITAS
                </div>
				
				<div id="tabla">
                    
                    <?php
                            /* Hacemos una consulta en la tabla citas, unida a las demás tablas de la base de datos por las claves foraneas, para que nos muestre las citas pasadas del usuario logueado, buscando entre la primera fecha de la tabla "Fechas"  y la fecha de hoy "now()"*/
            
                            $consulta_citas = "SELECT date_format(fechas.citaFecha,'%d-%m-%Y') AS citaFecha, horarios.hora_inicio, servicios.servicio, especialistas.Nombre, especialistas.Apellidos FROM citas INNER JOIN (clientes,servicios,especialistas,fechas,horarios) ON (citas.N_Historial = clientes.N_Historial AND citas.servicioId = servicios.servicioId AND citas.especialistaId = especialistas.especialistaId AND citas.citaFecha = fechas.citaFecha AND citas.horaId = horarios.horaId) WHERE email = '$correo' AND citas.citaFecha BETWEEN 20220601 and now()  ORDER BY fechas.citaFecha ASC";

                    ?>
                                <!--Creamos una tabla para que figuren las citas pendientes-->

                                <table border="1" cellspacing="0" >
                                    <thead>
                                        <tr>
                                            <td class = "cabecera_tabla">FECHA CITA</td>
                                            <td class = "cabecera_tabla">HORA CITA</td>
                                            <td class = "cabecera_tabla">SERVICIO</td>
                                            <td class = "cabecera_tabla">ESPECIALISTA</td>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                            $resultado_consulta_citas = mysqli_query($conexion,$consulta_citas);
                                            while ($registro = mysqli_fetch_array($resultado_consulta_citas))
                                            { //Empieza código del bucle while.

                                           /* Voy a quitar los segundos de la ta la hora de inicio de la cita*/
                                           $valor_hora_inicio = $registro["hora_inicio"];
                                           $valor_hora_inicio_sinS = date("H:i",strtotime($valor_hora_inicio));     
                                        ?>
                                        
                                        <tr>
                                            <td class = "datos_formato"><?php echo $registro["citaFecha"]; ?></td>
                                            <td class = "datos_formato"><?php echo $valor_hora_inicio_sinS; ?></td>
                                            <td class = "datos_formato"><?php echo $registro["servicio"]; ?></td>
                                            <td class = "datos_formato"><?php echo $registro["Nombre"]; echo " ";echo $registro["Apellidos"]; ?></td>
                                        </tr>
                                        <?php }  //Termina código del bucle while.?>
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