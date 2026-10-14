<?php
include_once('../config/configs.php');
session_start();

$step = $_GET['step'] ?? 'upload';
$data = $_SESSION['contract_data'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 'review') {
    $_SESSION['contract_data'] = $_POST['contract_data'];
    header('Location: prev-contract.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= WEB_TITLE ?> - Cargar Archivos</title>
    <link rel="icon" href="../assets/img/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="../assets/css/tailwind.css">
    <style>
        .drop-zone {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: border 0.3s ease;
        }
        .drop-zone:hover {
            border-color: #0054a6;
        }
    </style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4 text-gray-100">
    <div class="bg-gray-800 p-8 rounded-lg shadow-2xl max-w-2xl w-full border border-gray-700">
        <h1 class="text-2xl font-bold text-center text-white mb-6">MOSUN LED TECNOLOGY</h1>
        
        <?php if ($step === 'upload'): ?>
            <form action="process.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Archivo MSPI (.xls)</label>
                        <input type="file" name="mspi_file" accept=".xls,.xlsx" required 
                                class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-900 file:text-blue-200 hover:file:bg-blue-800">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Archivo Packing List (.xls)</label>
                        <input type="file" name="packing_file" accept=".xls,.xlsx" required 
                                class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-900 file:text-blue-200 hover:file:bg-blue-800">
                    </div>
                </div>
                <div class="flex justify-center pt-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                        Procesar Archivos
                    </button>
                </div>
            </form>
        <?php elseif ($step === 'review'): ?>
            <form action="main.php?step=review" method="POST" class="space-y-4">
                <h2 class="text-xl font-semibold text-gray-200 mb-4">Datos Extraídos</h2>
                <div class="grid grid-cols-1 gap-4">
                    <?php foreach ($data as $key => $value): ?>
                        <div class="flex flex-col space-y-1">
                            <label class="text-xs font-bold text-gray-400 uppercase"><?= str_replace('_', ' ', $key) ?></label>
                            <input type="text" name="contract_data[<?= $key ?>]" value="<?= htmlspecialchars($value) ?>" 
                                    class="bg-gray-700 border border-gray-600 text-white p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="flex justify-between pt-6">
                    <a href="main.php?step=upload" class="text-blue-400 hover:underline">Volver a cargar</a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                        Confirmar y Previsualizar
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>

</body>
</html>
