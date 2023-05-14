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

    <link rel="stylesheet" type="text/css" href="../css/acceso_principal.css">

</head>
<body>

    <div id="contenedor">
	<!-- Dentro de la cabecera ponemos el nombre completo de la persona logueada obtenido de la base de datos*/-->
	
            <div id="cabecera">
                <h4>Bienvenido/a: <?php  $consulta_nombre = "SELECT * FROM clientes WHERE email = '$correo' "; 
				$resultado_nombre = mysqli_query($conexion,$consulta_nombre); 
				$fila = mysqli_fetch_array($resultado_nombre);
				echo $fila["Nombre"]; echo " "; echo $fila["Apellidos"] ?> <a href="acceso_datos_personales.php">(Consultar datos personales)</a></h4>
            </div>
        <div id="central">
		
			<div id="citas_pendientes">
                <div class="titulo">
                    CITAS PENDIENTES
                </div>
				
				<div id="tabla">
                    
                            <?php
                            /* Hacemos una consulta en la tabla citas, unida a las demás tablas de la base de datos por las claves foraneas, para que nos muestre las citas pendientes del usuario logueado, buscando las fechas entre la fecha de hoy "now()" y la última fecha de la tabla "Fechas"*/
            
                            $consulta_citas = "SELECT date_format(fechas.citaFecha,'%d-%m-%Y') AS citaFecha, horarios.hora_inicio, servicios.servicio, especialistas.Nombre, especialistas.Apellidos FROM citas INNER JOIN (clientes,servicios,especialistas,fechas,horarios) ON (citas.N_Historial = clientes.N_Historial AND citas.servicioId = servicios.servicioId AND citas.especialistaId = especialistas.especialistaId AND citas.citaFecha = fechas.citaFecha AND citas.horaId = horarios.horaId) WHERE email = '$correo' AND citas.citaFecha BETWEEN now() and 20230601 ORDER BY fechas.citaFecha ASC";

                             ?>
                                <!--Creamos una tabla para que figuren las citas pendientes-->

                                <table border="1" cellspacing="0">
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
                                        <?php }  //Termina código del bucle while.
                                        ?>
                                    </tbody>
                                    
                                    
                                
                                 </table>    
                    
                </div>

                 

            </div>

            <div id="enlace_historial">
                <a href="acceso_historial_citas.php">Consultar historial de citas</a>
            </div>  

            <div id="pedir_cita">

                <div class="titulo">
                    PEDIR CITA
                </div>

                <div id="form_pedir_cita">

                <!--Creamos el formulario con el cual pedir citas-->

                <!--Ponemos javaScript para que en el formulario en la casilla fecha solo nos deje elegir fechas a partir del día actual hasta la fecha máxima de la tabla fechas-->

					<script>
						window.onload = function(){
						var fecha = new Date(); //Fecha actual
						var mes = fecha.getMonth()+1; //obteniendo mes
						var dia = fecha.getDate()+1; //obteniendo dia
						var ano = fecha.getFullYear(); //obteniendo año
						if(dia<10)
							dia='0'+dia; //agrega cero si el menor de 10
						if(mes<10)
							mes='0'+mes //agrega cero si el menor de 10
						document.getElementById('citaFecha').min=ano+"-"+mes+"-"+dia;
						}
					</script>
					
				

                <form id="citaform" action ="procesar_nuevas_citas.php" method ="post" >
								
                    <table id ="tabla_pedir_cita">
                        <thead>
                            <tr>
                                <td class = "cabecera_tabla_pedir_cita">FECHA</td>
                                <td class = "cabecera_tabla_pedir_cita">HORA</td>
                                <td class = "cabecera_tabla_pedir_cita">SERVICIO</td>
                                <td></td>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td><input type="date" name="citaFecha" id = "citaFecha" min = "2000-01-01" max = "2023-06-01" required></td>
                                <td>
                                    <select name="horaId" id="horario" required>
                                        <option value="">Elige una opción</option>
                                        <option value="1">10:00</option>
                                        <option value="2">11:00</option>
                                        <option value="3">12:00</option>
                                        <option value="4">16:00</option>
                                        <option value="5">17:00</option>
                                        <option value="6">18:00</option>
                                        <option value="7">19:00</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="servicioId" id="servicio" required>
                                        <option value="">Elige una opción</option>
                                        <option value="1">Tratamientos de cuidado facial</option>
                                        <option value="2">Servicio de bronceado</option>
                                        <option value="3">Servicio de peluquería</option>
                                        <option value="4">Maquillaje</option>
                                        <option value="5">Manicura y pedicura</option>
                                        <option value="6">Depilación con cera</option>
                                        <option value="7">Depilación laser</option>
                                        <option value="8">Tratamientos corporales y masajes</option>
                                    </select>
                                </td>
                                <td><button type="submit"  name="reservar_cita">RESERVAR CITA</button></td>
                            </tr>
                        </tbody>
                    </table>
                                
                            </form>                            

                </div>
                        <p id="resultado_cita"></p>

            </div>

                
                <div id="enlace_cerrar_sesion">
                    <a href="cerrar_sesion.php">(Cerrar sesión)</a>
                </div>

        </div>
        

    </div>
    
    
</body>
</html>