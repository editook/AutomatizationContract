<?php

include_once('config/configs.php');
include_once('config/session_manager.php');

if( $_SESSION['REST_type_user'] == 'Administrador' || $_SESSION['REST_type_user'] == 'CLIENTE' )
    header("Location: ". LOCALHOST ."/ui/main.php");
else
    header( 'Location: '. LOCALHOST .'/signin.php' );