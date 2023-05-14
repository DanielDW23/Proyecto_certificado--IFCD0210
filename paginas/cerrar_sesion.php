<?php session_start(); 

    /*Cerramos la sesión del usuario y le redirigimos a la página de login*/

   
    session_destroy();
    
    header("Location: ../index.php");


?>