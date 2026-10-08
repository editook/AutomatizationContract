<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

function analyzeFile($path) {
    echo "\n--- Analyzing file: $path ---\n";
    try {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();
        
        // Print first 50 rows to get a sense of the structure
        for ($i = 0; $i < min(50, count($data)); $i++) {
            echo "Row $i: " . implode(" | ", array_filter($data[$i])) . "\n";
        }
    } catch (Exception $e) {
        echo "Error loading $path: " . $e->getMessage() . "\n";
    }
}

analyzeFile('example/entrada/MSPI20260429BOLMIG.xls');
analyzeFile('example/entrada/Packing-list-MSPI20260429BOLMIG-left.xls');
