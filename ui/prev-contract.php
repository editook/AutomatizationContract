<?php
include_once('../config/configs.php');
session_start();

$data = $_SESSION['contract_data'] ?? [];
$data = null;
$page1 = [
    'header' => 'Jiangmen Mosun Optoelectronics Technology Co., Ltd',
    'address' => 'Add: Building 2, Smart Manufacturing Port, 370 Lianhai Road, Jianghai District, Jiangmen City, Guangdong Province Postal:529000',
    'contact_info' => 'Contact : Mia@ledmosun.com Tel： +86 18237627197 Web: www.ledmosun.com',
    'title' => 'PROFORMA INVOICE',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= WEB_TITLE ?> - Previsualización</title>
    <link rel="stylesheet" href="../assets/css/tailwind.css">
    <style>
        .contract-page {
            width: 800px;
            min-height: 1123px;
            margin: 20px auto;
            background: white;
            color: black;
            padding: 80px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            font-family: 'Times New Roman', Times, serif;
            position: relative;
        }
        .highlight {
            background-color: yellow;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            padding: 0 4px;
        }
        .border-table th, .border-table td {
            border: 1px solid black;
            padding: 2px 2px;
        }
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
            .contract-page { box-shadow: none; margin: 0; }
        }
    </style>
</head>
<body class="bg-gray-900 p-8">
    <div class="no-print flex justify-center gap-4 mb-10">
        <a href="main.php?step=review" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-600">Volver a Editar</a>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Imprimir PDF</button>
    </div>

    <div class="flex flex-col items-center">
        <div class="contract-page">
            <!-- Top Section: Logo and Header -->
            <div class="flex justify-between items-start mb-10">
                <img src="../assets/img/log-company.png" class="h-auto w-40" alt="Company Logo">
                <div class="text-right">
                    <h1 class="text-lg font-bold leading-tight text-[27px] text-center"><?= $page1['header'] ?></h1>
                    <p class="text-sm text-center mt-8"><?= $page1['address'] ?></p>
                </div>
            </div>

            <h3 class="mb-10 font-bold">Contract No: <span class="font-normal"><?= $data['pi_number'] ?? 'C-MSPI20260429BOLMIG' ?></span></h3>

            <!-- International Sales Contract Section -->
            <div class="mb-10 text-sm">
                <table class="w-full border-collapse border border-black">
                    <thead>
                        <tr>
                            <th colspan="2" class="border border-black text-center text-lg font-bold uppercase">
                                INTERNATIONAL SALES CONTRACT
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-black font-bold pl-1">Contract No: <span class="font-normal"><?= $data['pi_number'] ?? 'C-MSPI20260429BOLMIG' ?></span></td>
                            <td class="border border-black font-bold pl-1">Date: <span class="font-normal"><?= $data['order_date'] ?? '22/05/2026' ?></span></td>
                        </tr>
                        <tr>
                            <td class="border border-black pl-1"><strong>Buyer:</strong> <span class="font-normal"><?= $data['customer_name'] ?? 'Led Nation LLC' ?></span></td>
                            <td class="border border-black pl-1"><strong>Seller:</strong> <span class="font-normal"><?= $page1['header'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="border border-black pl-1"><strong>Address:</strong> <span class="font-normal"><?= $data['customer_address'] ?? '1309 Coffeen Avenue, Suite 1200, Sheridan, WY 82801' ?></span></td>
                            <td class="border border-black pl-1"><strong>Address:</strong> <span class="font-normal"><?= $page1['address'] ?></span></td>
                        </tr>
                        <tr>
                            <td class="border border-black pl-1"><strong>Contact:</strong> <span class="font-normal"><?= $data['customer_contact'] ?? '(1)3072284125' ?></span></td>
                            <td class="border border-black pl-1"><strong>Tel:</strong> <span class="font-normal"><?= $data['customer_tel'] ?? '+86 18237627197' ?></span></td>
                        </tr>
                        <tr>
                            <td class="border border-black pl-1"><strong>Email:</strong> <span class="font-normal">contact@lednationllc.com</span></td>
                            <td class="border border-black pl-1"><strong>Email:</strong> <span class="font-normal">Mia@ledmosun.com</span></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="border border-black pl-1 italic">
                                This agreement is made between the buyer and the seller under the following terms and conditions:
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3 class="mb-10 font-bold">Product Details</h3>

            <!-- Product Table -->
            <table class="w-full border-collapse border-table text-sm mb-10">
                <thead class="bg-white">
                    <tr>
                        <th class="text-center font-bold w-20" >No Item</th>
                        <th class="text-center font-bold">Item Name</th>
                        <th class="text-center font-bold">Item Description</th>
                        <th class="text-center font-bold">HS Code</th>
                        <th class="text-center font-bold">Quantity</th>
                        <th class="text-center font-bold">Unitary Price FOB (USD)</th>
                        <th class="text-center font-bold w-25">Total Amount (USD)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="pl-1 text-center">1</td>
                        <td class="pl-1 text-center">LED LUMINAIRE</td>
                        <td class="pl-1 font-bold">Brand:Lumens<br>Model:LC12323<br>Dimensions:570*250*120mm<br>Material:Aluminam<br>Condition:New</td>
                        <td class="pl-1 text-center">(9405)</td>
                        <td class="pl-1 text-center">76</td>
                        <td class="pl-1 text-center">21.00</td>
                        <td class="pl-1 text-center">1,596.00</td>
                    </tr>
                    <tr>
                        <td class="pl-1 text-center">2</td>
                        <td class="pl-1 text-center">LED LUMINAIRE</td>
                        <td class="pl-1 font-bold">Brand:Lumens<br>Model:LC12323<br>Dimensions:570*250*120mm<br>Material:Aluminam<br>Condition:New</td>
                        <td class="pl-1 text-center">(9405)</td>
                        <td class="pl-1 text-center">44</td>
                        <td class="pl-1 text-center">36.10</td>
                        <td class="pl-1 text-center">1,588.40</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class=""></td>
                        <td class="text-center font-bold">120</td>
                        <td class="text-center font-bold">Total Amount</td>
                        <td class="text-center font-bold highlight"><?= $data['total_amount'] ?? '3,184.40' ?></td>
                    </tr>
                </tfoot>
            </table>

            <div id="hoja2" class="flex justify-between items-start mb-10">
                <img src="../assets/img/log-company.png" class="h-auto w-40" alt="Company Logo">
                <div class="text-right">
                    <h1 class="text-lg font-bold leading-tight text-[27px] text-center"><?= $page1['header'] ?></h1>
                    <p class="text-sm text-center mt-8"><?= $page1['address'] ?></p>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
