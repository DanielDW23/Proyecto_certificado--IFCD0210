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

    /*Comprobamos si se ha enviado el formulario para pedir la cita y tiene todos los datos*/

    if (isset($_POST["reservar_cita"]))
    {	
    
    
        if (strlen($_POST["citaFecha"]) >= 1 && strlen($_POST["horaId"]) >= 1 && strlen($_POST["servicioId"]) >=1 )
        {
        
            $citaFecha = $_POST["citaFecha"] ;
            $horaId = $_POST["horaId"] ;
            $servicioId = $_POST["servicioId"];
            
            /*A través del correo electrónico de login voy a obtener el nuevo de historial de usuario logueado que pide la cita*/

            $historial = "SELECT N_Historial from clientes WHERE email = '$correo'";
            $resultado_historial = mysqli_query($conexion,$historial); 
			$valor_historial = mysqli_fetch_array($resultado_historial);
            $N_Historial = $valor_historial[0];
           

            /*Obtenemos el Id del profesional de la tabla especialistas que realiza el servicio solicitado así como el nombre y apellidos para el posterior envío del correo de confirmación al cliente*/

            $especialista = "SELECT especialistaId,Nombre,Apellidos from especialistas WHERE servicio1 = '$servicioId' OR servicio2 = '$servicioId'";
            $resultado_especialista = mysqli_query($conexion,$especialista); 
			$valor_especialista = mysqli_fetch_array($resultado_especialista);
            $especialistaId = $valor_especialista["especialistaId"];
            $nombre_especialista = $valor_especialista["Nombre"];
            $apellidos_especialista = $valor_especialista["Apellidos"];
            


            /*Ahora procedemos a meter el dato de la nueva cita en la tabla de la base de datos citas*/

        
            $insertar_cita = "INSERT INTO citas (citaFecha, horaId, servicioId, especialistaId,N_Historial,Fecha_registro) VALUES ('$citaFecha','$horaId','$servicioId', '$especialistaId','$N_Historial',now())";
            
            $resultado_cita = mysqli_query($conexion,$insertar_cita);
        
            if($resultado_cita)
                {
                    /* Vamos a enviar un correo de confirmación al cliente con la cita, los datos que no hemos obtenido en el código superior los obtenemos ahora*/

                    

                    /*Obtenemos el nombre*/
                    $consulta_nombre = "SELECT Nombre FROM clientes WHERE email = '$correo' "; 
				    $resultado_nombre = mysqli_query($conexion,$consulta_nombre); 
				    $array_nombre = mysqli_fetch_array($resultado_nombre);
                    $nombre = $array_nombre["Nombre"];

                    /*Obtenemos la hora elegida a través del Id*/
                    $consulta_hora = "SELECT hora_inicio FROM horarios WHERE horaId = '$horaId' "; 
				    $resultado_hora = mysqli_query($conexion,$consulta_hora); 
				    $array_hora = mysqli_fetch_array($resultado_hora);
                    $hora = $array_hora["hora_inicio"];

                    /*Obtenemos el servicio elegido a través del Id*/
                    $consulta_servicio = "SELECT servicio FROM servicios WHERE servicioId = '$servicioId' "; 
				    $resultado_servicio = mysqli_query($conexion,$consulta_servicio); 
				    $array_servicio = mysqli_fetch_array($resultado_servicio);
                    $servicio = $array_servicio["servicio"];

                    /*Cambiamos el formato de la fecha elegida, la hora y quitamos las mayúsculas al nombre y apellido del especialista*/

                    $nuevo_formato_fecha = date("d-m-Y", strtotime($citaFecha));
                    $nuevo_formato_hora = date("H:i",strtotime($hora));
                    $nuevo_nombre_especialista = ucwords(strtolower($nombre_especialista));
                    $nuevo_apellidos_especialista = ucwords(strtolower($apellidos_especialista));

                    /*Codigo para enviar el correo de confirmación de cita*/

                    $destinatario = "$correo";
                    $asunto = "Confirmación cita CENTRO ESTETICO LOPEZ GARCIA";
                    $mensaje = "Hola"." ".$nombre. "," . " " . "te confirmamos tu cita con nosotros el día" . " " .$nuevo_formato_fecha. " " ."a las" . " " . $nuevo_formato_hora . " " . "para" ." " . '"'. $servicio . '"'. " " . "con" . " " . $nuevo_nombre_especialista . " " . $nuevo_apellidos_especialista . "\n \n" . "Un saludo";
                    $header = "From: citas@<ELIMINADO>";

                    

                    mail($destinatario,$asunto,$mensaje,$header);

                    header("Location: confirmacion_cita.php");
                    
                }
                
                
        }

    }
       

/* Cerrar la conexión. */

    mysqli_close($conexion); 


?>