<?php
require '../vendor/autoload.php';
include_once('../config/configs.php');
session_start();

use App\Models\ContractDataExtractorOrden;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ordenUpload = $_FILES['orden_file'] ?? null;
    $invoiceUpload = $_FILES['invoice_file'] ?? null;
    $orden_file = $ordenUpload['tmp_name'] ?? null;
    $invoice_file = $invoiceUpload['tmp_name'] ?? null;

    if (
        !$orden_file
        || !$invoice_file
        || ($ordenUpload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK
        || ($invoiceUpload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK
    ) {
        die('Error: Faltan archivos');
    }

    try {
        $extractor = new ContractDataExtractorOrden();
        $extractedData = $extractor->extract($orden_file, $invoice_file);

        $_SESSION['collection_data'] = $extractedData['response'];
        $_SESSION['collection_table'] = $extractedData['items'];
        header('Location: main_orden.php?step=review');
        exit;
    } catch (Exception $e) {
        die('Error procesando archivos: ' . $e->getMessage());
    }
}
