<?php
	
	/*Datos conexión.*/
	
		$user = "<ELIMINADO>";
		$pass = "<ELIMINADO>";
		$host = "<ELIMINADO>";
		$base = "<ELIMINADO>";
	
	/*Conexión a la base de datos.*/
	
		$conexion = mysqli_connect($host, $user, $pass, $base);

		
	/*Si no hay conexión que nos diga donde está el error*/

	if (!$conexion)
	{
	 	die("No hay conexión: ".mysqli_connect_error());
	}
?>