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
        
        $rawDate = $this->clean($data[11][1] ?? '');
        $orderDate = $rawDate ? date("d-m-Y", strtotime($rawDate)) : '';

        return [
           
            'models_name' => 'LC110030,APB-150W',
            'order_date' => $orderDate,
            
            'cartons' => '120',
            'gross_weight'=>'255.80',
            'total_amount'=> '$3184.40',
            'total_amount_letters' => 'Three thousand one hundred eighty-four and 40/100 US Dollars',


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
            'order_date_proforma'=>'14/04/2026',
            'contract' => 'C-'.$this->clean($sheet->getCell('O5')->getValue() ?? ''),
        ];
    }

    private function clean($value): string {
        if (is_array($value)) return '';
        return trim((string)$value);
    }
}
