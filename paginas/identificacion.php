<?php
	
	

	/*Incluimos el archivo de conexión a la base de datos*/
	
		include("conexion.php");
	
	/* Variables que cargan lo introducido en el formulario a través de variables superglobales.*/	
	
		$usuario = $_POST["usuario"] ;
		$contraseña = $_POST["password"];

		session_start();
		$_SESSION["usuario"] = $usuario;
		
	/* Consulta de los datos introducidos para ver si coinciden con los guardados en la base de datos. */

		$consulta = "SELECT * FROM clientes WHERE email = '$usuario' AND passwords = '$contraseña'";
	
	/* Ejecución de la consulta. */
	
		$resultado = mysqli_query($conexion,$consulta);

	/* Obtener el número de filas que coinciden con la consulta especificada.*/
	
		$fila = mysqli_num_rows($resultado);
	
	/* Acciones si se produce o no la identificación correcta. */
	
		if($fila)
		{
			header("Location:acceso_principal.php");
							
		}
			/*Mensaje de error si el usuario o la contraseña no son correctos, enlace para volver a la página de login*/	
		else
		{	
			
			echo "<link rel='stylesheet' type='text/css' href='../css/identificacion.css'>";
			echo "<h3  id = 'error'>Error de identificación</h3>";
			echo "<p><a href='../index.php'>Vuelve a intentarlo</a></p>";
					
		}

	/* Cerrar la conexión. */

		mysqli_close($conexion);
	

?>