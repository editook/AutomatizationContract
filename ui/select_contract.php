<?php
include_once('../config/configs.php');
session_start();

if (!isset($_SESSION['REST_id_user'])) {
    header('Location: ' . LOCALHOST . '/signin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destinations = [
        'main' => 'main.php',
        'orden' => 'main_orden.php',
    ];
    $destination = $_POST['destination'] ?? '';

    if (isset($destinations[$destination])) {
        header('Location: ' . $destinations[$destination]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= WEB_TITLE ?> - Seleccionar flujo</title>
    <link rel="icon" href="../assets/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/tailwind.css">
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4 text-gray-100">
    <main class="w-full max-w-3xl rounded-lg border border-gray-700 bg-gray-800 p-8 shadow-2xl">
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold text-white">Selecciona el tipo de documento</h1>
            <p class="mt-2 text-gray-400">Elige el flujo que deseas utilizar.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <form method="post" action="select_contract.php">
                <button type="submit" name="destination" value="main" class="w-full rounded-lg border border-blue-700 bg-gray-900 p-6 text-left transition hover:border-blue-500 hover:bg-gray-700">
                    <span class="mb-2 block text-lg font-semibold text-white">MSPI + Packing List</span>
                    <span class="block text-sm text-gray-400">Cargar el MSPI y el Packing List para generar el contrato.</span>
                </button>
            </form>

            <form method="post" action="select_contract.php">
                <button type="submit" name="destination" value="orden" class="w-full rounded-lg border border-green-700 bg-gray-900 p-6 text-left transition hover:border-green-500 hover:bg-gray-700">
                    <span class="mb-2 block text-lg font-semibold text-white">Orden</span>
                    <span class="block text-sm text-gray-400">Cargar la orden compacta para generar el contrato.</span>
                </button>
            </form>
        </div>
    </main>
</body>
</html>
