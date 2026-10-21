<?php
require '../vendor/autoload.php';
include_once('../config/configs.php');
session_start();

use App\Models\ContractDataExtractor;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orden_file = $_FILES['orden_file']['tmp_name'] ?? null;
    $invoice_file = $_FILES['invoice_file']['tmp_name'] ?? null;

    if (!$mspi_file || !$packing_file || !$invoice_file) {
        die('Error: Faltan archivos');
    }

    try {
        $extractor = new ContractDataExtractor();
        $extractedData = $extractor->extract($orden_file, $invoice_file);

        $_SESSION['collection_data'] = $extractedData['response'];
        $_SESSION['collection_table'] = $extractedData['items'];
        header('Location: main_orden.php?step=review');
        exit;
    } catch (Exception $e) {
        die('Error procesando archivos: ' . $e->getMessage());
    }
}
