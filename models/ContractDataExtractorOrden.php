<?php

namespace App\Models;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Smalot\PdfParser\Parser;

require_once __DIR__ . '/model_configuration.php';

class ContractDataExtractor
{
    public function extract(string $ordenPath, string $invoicePath): array
    {
        $response = [];

        $ordenData = $this->parseOrden($ordenPath);
        $ordenItems = $this->getOrdenItems($ordenPath);
        
        $invoiceItems = $this->parseInvoice($invoicePath);
        $inputItems = $this->getCreateItems(count($invoiceItems));
        $itemsTable = $this->getItemsFormatOrdenArray($ordenItems, $invoiceItems,$inputItems);
        return [];
    }
    private function getCreateItems(int $quantity)
    {
        $item = [
            'item_name'=>'LED LUMINAIRE',
            'hc_code'=>'9405',
            'brand' => 'Lumens',
            'material' => 'Aluminum',
            'condition' => 'New',
        ];

        $items = [];

        for ($i = 0; $i < $quantity; $i++) {
            $items[] = $item;
        }

        return $items;
    }
    private function getItemsFormatArray(array $ordenItems, array $invoiceItems,array $inputItems): array
    {
        if (count($ordenItems) !== count($invoiceItems)
            || count($ordenItems) !== count($inputItems)
        ) {
            throw new \RuntimeException('La cantidad de items del Orden y factura debe coincidir.');
        }

        $items = [];
        foreach ($ordenItems as $index => $ordenItem) {
            $inputItem = $inputItems[$index];
            $items[] = [
                'model' => $invoiceItems[$index],
                'brand' => $inputItem['brand'],
                'hc_code' => $inputItem['hc_code'],
                'item_name' => $inputItem['item_name'],
                'material' => $inputItem['material'],
                'condition' => $inputItem['condition'],
                'price' => $ordenItem['price'],
                'quantity' => $ordenItem['quantity'],
                'subtotal' => $ordenItem['subtotal'],
                'cartons' => $ordenItem['cartons'],
                'length' => $ordenItem['length'],
                'width' => $ordenItem['width'],
                'heigth' => $ordenItem['heigth'],
                'gross_weight' => $ordenItem['gross_weight'],
            ];
        }

        return $items;
    }
    private function parseInvoice(string $path): array
    {
        if (!is_file($path) || !is_readable($path)) {
            throw new \RuntimeException('No se puede leer el archivo PDF de la factura.');
        }

        $parser = new Parser();
        $pdf = $parser->parseFile($path);
        $text = $pdf->getText();

        preg_match_all('/\[([^\]]+)\]/u', $text, $matches);

        return array_values(array_filter(
            array_map('trim', $matches[1] ?? []),
            static fn(string $value): bool => $value !== ''
        ));
    }
    private function clean($value): string
    {
        if (is_array($value)) return '';
        return trim((string)$value);
    }
}
