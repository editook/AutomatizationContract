<?php

include_once('config/configs.php');
session_start();

if (isset($_SESSION['REST_id_user'])) {
    header("Location: " . LOCALHOST . "/ui/select_contract.php");
} else {
    header('Location: ' . LOCALHOST . '/signin.php');
}
exit;
