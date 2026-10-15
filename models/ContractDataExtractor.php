<?php

namespace App\Models;

use PhpOffice\PhpSpreadsheet\IOFactory;

require_once __DIR__ . '/model_configuration.php';

class ContractDataExtractor
{
    public function extract(string $mspiPath, string $packingPath): array
    {
        $response = [];

        $mspiData = $this->parseMspi($mspiPath);
        $packingData = $this->parsePacking($packingPath);

        $solicitante = [//Datos Empresa Contratante
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
            'title' => [
                'value' => '',
                'name_show' => 'Datos Empresa Contratada',
                'visible' => true,
            ],
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
                'name_show' => 'Direccion',
                'visible' => true,
            ],
            'email' => [
                'value' => $packingData['contract']['value'],
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
            ['titulo'=>'Empresa Contratante','items'=>$solicitante],
            ['titulo'=>'Empresa Contratada','items'=>$solicitado],
            ['titulo'=>'MSDI','items'=>$mspiData],
            ['titulo'=>'Lista de Paquetes','items'=>$packingData]
        ];

        return $response;
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

            'cartons' => '120', //package list
            'gross_weight' => '255.80', //list empaque package list
            'total_amount' => $total_amount, //MSPI
            'total_amount_letters' => $amountInWords,

            //items
            //Led Luminaire const 
            //brand: Lumens const
            //Models: LC110030,APB-150W sacar de la factura comercial
            //Dimensions: 720*280*110mm MSPI lista de empaque
            //Material: Aluminum const
            //Condition: New const

            'contract_brand' => [
                'value' => 'Lumens',
                'name_show' => 'Marca',
                'visible' => true,
            ],
            'contract_model' => [
                'value' => 'Lumens',
                'name_show' => 'Marca',
                'visible' => true,
            ],

            'bank_swift' => [
                'value' => $this->clean($data[19][1] ?? ''),
                'name_show' => 'Swift Banco',
                'visible' => true,
            ],
            'bank_account' => [
                'value' => $this->clean($data[20][1] ?? ''),
                'name_show' => 'Cuenta Banco',
                'visible' => true,
            ],
            'bank_name' => [
                'value' => $this->clean($data[22][1] ?? ''),
                'name_show' => 'Nombre Banco',
                'visible' => true,
            ],

            'bank_address' => [
                'value' => $this->clean($data[23][1] ?? ''),
                'name_show' => 'Direccion Banco',
                'visible' => true,
            ],
        ];
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
