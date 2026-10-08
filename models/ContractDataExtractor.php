<?php
namespace App\Models;

use PhpOffice\PhpSpreadsheet\IOFactory;

class ContractDataExtractor {
    public function extract(string $mspiPath, string $packingPath): array {
        $mspiData = $this->parseMspi($mspiPath);
        $packingData = $this->parsePacking($packingPath);

        return array_merge($mspiData, $packingData);
    }

    private function parseMspi(string $path): array {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        return [
            'pi_number' => $this->clean($data[3][0] ?? $data[3][1] ?? ''), // PI No: MSPI...
            'customer_name' => $this->clean($data[4][1] ?? ''),
            'customer_address' => $this->clean($data[5][1] ?? ''),
            'customer_contact' => $this->clean($data[6][1] ?? ''),
            'customer_tel' => $this->clean($data[7][1] ?? ''),
            'order_date' => $this->clean($data[11][1] ?? ''),
            'total_amount' => $this->clean($data[15][1] ?? $data[15][0] ?? ''),
            'bank_swift' => $this->clean($data[19][1] ?? ''),
            'bank_account' => $this->clean($data[20][1] ?? ''),
            'bank_name' => $this->clean($data[22][1] ?? ''),
            'bank_address' => $this->clean($data[23][1] ?? ''),
        ];
    }

    private function parsePacking(string $path): array {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        return [
            'client_po' => $this->clean($data[7][1] ?? ''),
            'total_cartons' => $this->clean($data[14][1] ?? ''),
            'gross_weight' => $this->clean($data[14][4] ?? ''),
            'total_volume' => $this->clean($data[14][5] ?? ''),
        ];
    }

    private function clean($value): string {
        if (is_array($value)) return '';
        return trim((string)$value);
    }
}
