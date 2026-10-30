<?php

namespace App\Models;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Smalot\PdfParser\Parser;

require_once __DIR__ . '/model_configuration.php';

class ContractDataExtractorOrden
{
    public function extract(string $ordenPath, string $invoicePath): array
    {
        $ordenData = $this->parseOrden($ordenPath);
        $ordenItems = $this->getOrdenItems($ordenPath);
        $invoiceItems = $this->parseInvoice($invoicePath);
        $inputItems = $this->getCreateItems(count($ordenItems));
        $itemsTable = $this->getItemsFormatOrdenArray($ordenItems, $invoiceItems, $inputItems);

        $buyer = [
            'customer_name' => ['value' => 'Led National LLC', 'name_show' => 'Empresa', 'visible' => true],
            'customer_address' => ['value' => '1309 Coffeen Avenue, Suite 1200, Sheridan, WY 82801', 'name_show' => 'Direccion', 'visible' => true],
            'customer_tel' => ['value' => '(1)3072284125', 'name_show' => 'Telefono', 'visible' => true],
            'customer_email' => ['value' => 'contact@lednationllc.com', 'name_show' => 'Correo', 'visible' => true],
            'contract' => ['value' => $ordenData['contract'], 'name_show' => 'NUM. CONTRATO', 'visible' => true],
            'order_date_proforma' => ['value' => $ordenData['order_date'], 'name_show' => 'FECHA DE PROFORMA', 'visible' => true],
        ];

        $seller = [
            'header' => ['value' => $ordenData['seller_name'], 'name_show' => 'Empresa', 'visible' => true],
            'header2' => [
                'value' => '',
                'name_show' => 'Empresa(Adicional)',
                'visible' => true,
            ],
            'address' => ['value' => $ordenData['seller_address'], 'name_show' => 'Direccion', 'visible' => true],
            'contact' => ['value' => $ordenData['seller_contact'], 'name_show' => 'Informacion', 'visible' => true],
            'email' => ['value' => $ordenData['seller_email'], 'name_show' => 'Correo', 'visible' => true],
            'tel' => ['value' => $ordenData['seller_tel'], 'name_show' => 'Telefono', 'visible' => true],
        ];

        $orderFields = [
            'order_date' => ['value' => $ordenData['order_date'], 'name_show' => 'Fecha Contrato', 'visible' => true],
            'account_name' => ['value' => $ordenData['account_name'], 'name_show' => 'Titular de Cuenta', 'visible' => true],
            'bank_account' => ['value' => $ordenData['bank_account'], 'name_show' => 'Cuenta Banco', 'visible' => true],
            'bank_country' => ['value' => $ordenData['bank_country'], 'name_show' => 'Pais', 'visible' => true],
            'bank_name' => ['value' => $ordenData['bank_name'], 'name_show' => 'Nombre Banco', 'visible' => true],
            'bank_swift' => ['value' => $ordenData['bank_swift'], 'name_show' => 'Swift Code', 'visible' => true],
            'bank_address' => ['value' => $ordenData['bank_address'], 'name_show' => 'Direccion Banco', 'visible' => true],
        ];

        return [
            'response' => [
                ['titulo' => 'Datos del Comprador', 'items' => $buyer],
                ['titulo' => 'Datos del Vendedor', 'items' => $seller],
                ['titulo' => 'Orden', 'items' => $orderFields],
            ],
            'items' => $itemsTable,
        ];
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
    private function getItemsFormatOrdenArray(array $ordenItems, array $invoiceItems, array $inputItems): array
    {
        if(count($ordenItems) !== count($invoiceItems)){
            $invoiceItems = array_values(array_unique($invoiceItems));
        }
        
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
            static fn(string $value): bool => $value !== '' && !ctype_digit($value)
        ));
    }

    private function parseOrden(string $path): array
    {
        if (!is_file($path) || !is_readable($path)) {
            throw new \RuntimeException('No se puede leer el archivo de la orden.');
        }

        $sheet = IOFactory::load($path)->getActiveSheet();
        $bankText = $this->clean($sheet->getCell('A19')->getCalculatedValue());
        $bank = [
            'bank_account' => '',
            'bank_name' => '',
            'bank_swift' => '',
            'bank_address' => '',
            'account_name' => '',
        ];
        $bankPattern = '/Account No\\.?\\s*:\\s*(.*?)\\s+Bank Name\\s*:\\s*(.*?)\\s+SWIFT CODE\\s*:\\s*(.*?)\\s+ADDRESS of bank address\\s*:\\s*(.*?)\\s+Account Company Name\\s*:\\s*(.*?)\\s*$/i';
        if (preg_match($bankPattern, $bankText, $matches)) {
            $bank = [
                'bank_account' => trim($matches[1]),
                'bank_name' => trim($matches[2]),
                'bank_swift' => trim($matches[3]),
                'bank_address' => trim($matches[4]),
                'account_name' => trim($matches[5]),
            ];
        }

        $sellerContact = $this->clean($sheet->getCell('A3')->getCalculatedValue());
        $sellerEmailLine = $this->clean($sheet->getCell('A4')->getCalculatedValue());
        preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\\.[A-Z]{2,}/i', $sellerEmailLine, $emailMatch);
        preg_match('/\\d{7,}/', $sellerContact, $phoneMatch);

        $dateLine = $this->clean($sheet->getCell('A8')->getCalculatedValue());
        $orderDate = '';
        if (preg_match('/(\\d{4})[.\\/-](\\d{2})[.\\/-]?(\\d{2})/', $dateLine, $dateMatch)) {
            $year = (int) $dateMatch[1];
            $month = (int) $dateMatch[2];
            $day = (int) $dateMatch[3];
            if (checkdate($month, $day, $year)) {
                $orderDate = sprintf('%02d-%02d-%04d', $day, $month, $year);
            }
        }

        $contractLine = $this->clean($sheet->getCell('D6')->getCalculatedValue());
        $contract = preg_replace('/^PI\\s*:\\s*/i', '', $contractLine);

        return [
            'seller_name' => $this->clean($sheet->getCell('A1')->getCalculatedValue()),
            'seller_address' => preg_replace('/^Add\\s*:\\s*/i', '', $this->clean($sheet->getCell('A2')->getCalculatedValue())),
            'seller_contact' => $sellerContact,
            'seller_email' => $emailMatch[0] ?? '',
            'seller_tel' => $phoneMatch[0] ?? '',
            'contract' => trim($contract),
            'order_date' => $orderDate,
            'account_name' => $bank['account_name'],
            'bank_account' => $bank['bank_account'],
            'bank_country' => 'Hong Kong',
            'bank_name' => $bank['bank_name'],
            'bank_swift' => $bank['bank_swift'],
            'bank_address' => $bank['bank_address'],
        ];
    }

    private function getOrdenItems(string $path): array
    {
        if (!is_file($path) || !is_readable($path)) {
            throw new \RuntimeException('No se puede leer el archivo de la orden.');
        }

        $sheet = IOFactory::load($path)->getActiveSheet();
        $headerRow = null;
        for ($row = 1; $row <= $sheet->getHighestDataRow(); $row++) {
            $header = strtolower($this->clean($sheet->getCell('A' . $row)->getCalculatedValue()));
            if (in_array($header, ['item no.', 'item no', 'item number'], true)) {
                $headerRow = $row;
                break;
            }
        }

        if ($headerRow === null) {
            throw new \RuntimeException('No se encontró la tabla de productos en el archivo de la orden.');
        }

        $items = [];
        for ($row = $headerRow + 1; $row <= $sheet->getHighestDataRow(); $row++) {
            $model = $this->clean($sheet->getCell('A' . $row)->getCalculatedValue());
            $quantity = $this->numericCellValue($sheet->getCell('F' . $row)->getCalculatedValue());
            $price = $this->numericCellValue($sheet->getCell('G' . $row)->getCalculatedValue());
            $subtotal = $this->numericCellValue($sheet->getCell('H' . $row)->getCalculatedValue());
            $cartons = $this->numericCellValue($sheet->getCell('J' . $row)->getCalculatedValue());

            // Ignore continuation/packing rows without a product code (for example row 12).
            if ($model === '' || $quantity === null || $price === null || $subtotal === null || $cartons === null) {
                continue;
            }

            $dimensions = [];
            foreach (['K', 'L', 'M'] as $column) {
                $dimension = $this->numericCellValue($sheet->getCell($column . $row)->getCalculatedValue());
                if ($dimension === null) {
                    $dimensions = [];
                    break;
                }
                $dimensions[] = (int) round($dimension) * 10;
            }
            if (count($dimensions) !== 3) {
                continue;
            }

            $items[] = [
                'price' => $price,
                'quantity' => floor($quantity) === $quantity ? (int) $quantity : $quantity,
                'subtotal' => $subtotal,
                'cartons' => floor($cartons) === $cartons ? (int) $cartons : $cartons,
                'length' => $dimensions[0],
                'width' => $dimensions[1],
                'heigth' => $dimensions[2],
                'gross_weight' => '0',
            ];
        }

        if ($items === []) {
            throw new \RuntimeException('No se encontraron productos válidos en el archivo de la orden.');
        }

        return $items;
    }

    private function numericCellValue($value): ?float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        $normalized = preg_replace('/[^0-9.\\-]/', '', $this->clean($value));
        return $normalized !== '' && is_numeric($normalized) ? (float) $normalized : null;
    }

    private function clean($value): string
    {
        if (is_array($value)) return '';
        return trim((string)$value);
    }
}
