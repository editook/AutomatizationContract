<?php

namespace App\Models;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Smalot\PdfParser\Parser;

require_once __DIR__ . '/model_configuration.php';

class ContractDataExtractor
{
    public function extract(string $mspiPath, string $packingPath, string $invoicePath): array
    {
        $response = [];

        $mspiData = $this->parseMspi($mspiPath);
        $packingData = $this->parsePacking($packingPath);

        $mspiItems = $this->getMspiItems($mspiPath);
        $packingItems = $this->getPackingItems($packingPath);
        $invoiceItems = $this->parseInvoice($invoicePath);
        $inputItems = $this->getCreateItems(count($invoiceItems));
        $itemsTable = $this->getItemsFormatArray($mspiItems, $packingItems, $invoiceItems,$inputItems);

        

        //echo json_encode($packingItems);
        //exit;

        $solicitante = [ //Datos Empresa Contratante
            'customer_name' => [
                'value' => 'Led National LLC',
                'name_show' => 'Empresa',
                'visible' => true,
            ],
            'customer_address' => [
                'value' => '1309 Coffeen Avenue, Suite 1200, Sheridan, WY 82801',
                'name_show' => 'Direccion',
                'visible' => true,
            ],
            'customer_tel' => [
                'value' => '(1)3072284125',
                'name_show' => 'Telefono',
                'visible' => true,
            ],
            'customer_email' => [
                'value' => 'contact@lednationllc.com',
                'name_show' => 'Correo',
                'visible' => true,
            ],
        ];

        $page_email = 'Mia@ledmosun.com';
        $page_tel = '+86 18237627197';
        $solicitado = [
            'header' => [
                'value' => 'Jiangmen Mosun Optoelectronics Technology Co., Ltd',
                'name_show' => 'Empresa',
                'visible' => true,
            ],
            'address' => [
                'value' => 'Building 2, Smart Manufacturing Port, 370 Lianhai Road, Jianghai District, Jiangmen City, Guangdong Province',
                'name_show' => 'Direccion',
                'visible' => true,
            ],
            'contact' => [
                'value' => 'Postal:529000    Contact :  ' . $page_email . '   Tel：  ' . $page_tel . '    Web: www.ledmosun.com',
                'name_show' => 'Informacion',
                'visible' => true,
            ],
            'email' => [
                'value' => $page_email,
                'name_show' => 'Correo',
                'visible' => true,
            ],
            'tel' => [
                'value' => $page_tel,
                'name_show' => 'Telefono',
                'visible' => true,
            ],
        ];

        $response = [
            ['titulo' => 'Empresa Contratante', 'items' => $solicitante],
            ['titulo' => 'Empresa Contratada', 'items' => $solicitado],
            ['titulo' => 'MSDI', 'items' => $mspiData],
            ['titulo' => 'Lista de Paquetes', 'items' => $packingData]
        ];

        return ['response'=>$response,'items'=>$itemsTable];
    }

    private function getCreateItems(int $quantity)
    {
        $item = [
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
    private function getItemsFormatArray(array $mspiItems, array $packingItems, array $invoiceItems,array $inputItems): array
    {
        if (
            count($mspiItems) !== count($packingItems)
            || count($mspiItems) !== count($invoiceItems)
            || count($mspiItems) !== count($inputItems)
        ) {
            throw new \RuntimeException('La cantidad de items del MSPI, packing list y factura debe coincidir.');
        }

        $items = [];
        foreach ($mspiItems as $index => $mspiItem) {
            $packingItem = $packingItems[$index];
            $inputItem = $inputItems[$index];
            $items[] = [
                'model' => $invoiceItems[$index],
                'brand' => $inputItem['brand'],
                'material' => $inputItem['material'],
                'condition' => $inputItem['condition'],
                'price' => $mspiItem['price'],
                'quantity' => $mspiItem['quantity'],
                'subtotal' => $mspiItem['subtotal'],
                'cartons' => $packingItem['cartons'],
                'length' => $packingItem['length'],
                'width' => $packingItem['width'],
                'heigth' => $packingItem['heigth'],
            ];
        }

        return $items;
    }

    private function getPackingItems(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $lastColumn = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
        $headerRow = null;
        $columns = [];

        for ($row = 1; $row < $sheet->getHighestDataRow(); $row++) {
            $cartonsColumn = null;
            $dimensionColumns = [];

            for ($column = 1; $column <= $lastColumn; $column++) {
                $columnLetter = Coordinate::stringFromColumnIndex($column);
                $header = strtolower($this->clean($sheet->getCell($columnLetter . $row)->getValue() ?? ''));
                $dimension = strtolower($this->clean($sheet->getCell($columnLetter . ($row + 1))->getValue() ?? ''));

                if ($header === 'cartons') {
                    $cartonsColumn = $column;
                }
                if (in_array($dimension, ['length', 'width', 'height'], true)) {
                    $dimensionColumns[$dimension] = $column;
                }
            }

            if ($cartonsColumn !== null && count($dimensionColumns) === 3) {
                $headerRow = $row;
                $columns = [
                    'cartons' => $cartonsColumn,
                    'length' => $dimensionColumns['length'],
                    'width' => $dimensionColumns['width'],
                    'heigth' => $dimensionColumns['height'],
                ];
                break;
            }
        }

        if ($headerRow === null) {
            throw new \RuntimeException('No se encontraron las columnas de items en el archivo de packing list.');
        }

        $items = [];
        for ($row = $headerRow + 2; $row <= $sheet->getHighestDataRow(); $row++) {
            $values = [];
            foreach ($columns as $field => $column) {
                $cell = Coordinate::stringFromColumnIndex($column) . $row;
                $values[$field] = $sheet->getCell($cell)->getCalculatedValue();
            }

            if (
                !is_numeric($values['cartons'])
                || !is_numeric($values['length'])
                || !is_numeric($values['width'])
                || !is_numeric($values['heigth'])
            ) {
                continue;
            }

            $cartons = (float) $values['cartons'];
            $items[] = [
                'cartons' => floor($cartons) === $cartons ? (int) $cartons : $cartons,
                'length' => (int) (round((float) $values['length']))*10,
                'width' => (int) (round((float) $values['width']))*10,
                'heigth' => (int) (round((float) $values['heigth']))*10,
            ];
        }

        return $items;
    }
    private function getMspiItems(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $lastColumn = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
        $headerRow = null;
        $columns = [];

        for ($row = 1; $row <= $sheet->getHighestDataRow(); $row++) {
            $rowColumns = [];

            for ($column = 1; $column <= $lastColumn; $column++) {
                $header = strtolower(preg_replace(
                    '/\s+/',
                    ' ',
                    $this->clean($sheet->getCell(Coordinate::stringFromColumnIndex($column) . $row)->getValue() ?? '')
                ));

                if ($header === 'quantity') {
                    $rowColumns['quantity'] = $column;
                } elseif (str_contains($header, 'unit price')) {
                    $rowColumns['price'] = $column;
                } elseif (str_contains($header, 'total amount')) {
                    $rowColumns['subtotal'] = $column;
                }
            }

            if (count($rowColumns) === 3) {
                $headerRow = $row;
                $columns = $rowColumns;
                break;
            }
        }

        if ($headerRow === null) {
            throw new \RuntimeException('No se encontraron las columnas de items en el archivo MSPI.');
        }

        $items = [];
        for ($row = $headerRow + 1; $row <= $sheet->getHighestDataRow(); $row++) {
            $values = [];
            foreach ($columns as $field => $column) {
                $cell = Coordinate::stringFromColumnIndex($column) . $row;
                $values[$field] = $sheet->getCell($cell)->getCalculatedValue();
            }

            if (
                !is_numeric($values['price'])
                || !is_numeric($values['quantity'])
                || !is_numeric($values['subtotal'])
            ) {
                continue;
            }
            //number_format(1020.0, 2, '.', ',');
            $quantity = (float) $values['quantity'];
            $items[] = [
                'price' => (float) $values['price'],
                'quantity' => floor($quantity) === $quantity ? (int) $quantity : $quantity,
                'subtotal' => (float) $values['subtotal'],
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
    private function parseMspi(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        $rawDate = $this->clean($data[11][1] ?? '');
        $orderDate = $rawDate ? date("d-m-Y", strtotime($rawDate)) : ''; //B12

        $total_amount = '3184.40';
        $amountInWords = amountToEnglishWords($total_amount);
        return [
            'order_date' => [
                'value' => $orderDate,
                'name_show' => 'Fecha Contrato',
                'visible' => true,
            ],
            'cartons' => [
                'value' => '120', //package list
                'name_show' => 'Cartones',
                'visible' => true,
            ],
            'gross_weight' => [
                'value' => '255.80', //list empaque package list
                'name_show' => 'Peso Bruto',
                'visible' => true,
            ],
            'total_amount' => [
                'value' => $total_amount, //MSPI
                'name_show' => 'Monto Total',
                'visible' => true,
            ],
            'total_amount_letters' => [
                'value' => $amountInWords,
                'name_show' => 'Monto Total en Texto',
                'visible' => true,
            ],

            'account_name' => [
                'value' => $this->findValueRightOfLabel($sheet, 'Account name'),
                'name_show' => 'Nombre Banco',
                'visible' => true,
            ],
            'bank_account' => [
                'value' => $this->findValueRightOfLabel($sheet, 'Account number'),
                'name_show' => 'Cuenta Banco',
                'visible' => true,
            ],
            'bank_country' => [
                'value' => $this->findValueRightOfLabel($sheet, 'Country/Region'),
                'name_show' => 'Pais',
                'visible' => true,
            ],
            'bank_name' => [
                'value' => $this->findValueRightOfLabel($sheet, 'Bank Name'),
                'name_show' => 'Nombre Banco',
                'visible' => true,
            ],
            'bank_address' => [
                'value' => $this->findValueRightOfLabel($sheet, 'Bank Address'),
                'name_show' => 'Direccion Banco',
                'visible' => true,
            ],
        ];
    }

    private function findValueRightOfLabel(Worksheet $sheet, string $label): string
    {
        for ($row = 1; $row <= $sheet->getHighestDataRow(); $row++) {
            $cellLabel = $this->clean($sheet->getCell('B' . $row)->getValue() ?? '');
            if (strcasecmp($cellLabel, $label) === 0) {
                return $this->clean($sheet->getCell('C' . $row)->getValue() ?? '');
            }
        }

        return '';
    }

    private function parsePacking(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();

        return [
            'order_date_proforma' => [
                'value' => date("d-m-Y"),
                'name_show' => 'FECHA DE PROFORMA',
                'visible' => true,
            ],
            'contract' => [
                'value' => 'C-' . $this->clean($sheet->getCell('O5')->getValue() ?? ''),
                'name_show' => 'No CONTRATO',
                'visible' => true,
            ],
        ];
    }

    private function clean($value): string
    {
        if (is_array($value)) return '';
        return trim((string)$value);
    }
}
