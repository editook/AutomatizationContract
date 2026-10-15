<?php
require '../vendor/autoload.php';
require '../vendor/autoload_custom.php';
include_once('../config/configs.php');
session_start();

use App\Models\ContractDataExtractor;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mspi_file = $_FILES['mspi_file']['tmp_name'] ?? null;
    $packing_file = $_FILES['packing_file']['tmp_name'] ?? null;

    if (!$mspi_file || !$packing_file) {
        die('Error: Faltan archivos');
    }

    try {
        $extractor = new ContractDataExtractor();
        $extractedData = $extractor->extract($mspi_file, $packing_file);

        $_SESSION['collection_data'] = $extractedData;
        header('Location: main.php?step=review');
        exit;

    } catch (Exception $e) {
        die('Error procesando archivos: ' . $e->getMessage());
    }
}
