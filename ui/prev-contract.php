<?php
include_once('../config/configs.php');
session_start();
function renderHeader($page1) {
    ?>
    <div class="flex justify-between items-start mb-10">
        <img src="../assets/img/log-company.png" class="h-auto w-40" alt="Company Logo">
        <div class="text-right">
            <h1 class="text-lg font-bold leading-tight text-[27px] text-center"><?= $page1['header'] ?></h1>
            <p class="text-sm text-center mt-8"><?= $page1['address'] ?></p>
        </div>
    </div>
    <h3 class="mb-10 font-bold">Contract No: <span class="font-normal"><?= $data['pi_number'] ?? 'C-MSPI20260429BOLMIG' ?></span></h3>

    <?php
}
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
    <link rel="icon" href="../assets/img/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="../assets/css/tailwind.css">
    <style>
        .contract-page {
            width: 800px;
            min-height: 1123px;
            margin: 5px auto;
            background: white;
            color: black;
            padding: 80px;
            padding-top: 50px;
            padding-bottom: 50px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            font-family: 'Times New Roman', Times, serif;
            position: relative;
        }
        .highlight {
            background-color: yellow;
            padding: 0 4px;
        }
        .page-break {
            break-before: page;
            page-break-before: always; 
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
            
            <?php renderHeader($page1); ?>

            
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
            <table class="w-full border-collapse border-table text-sm">
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

            

        </div>
            <div class="contract-page page-break">
                <?php renderHeader($page1); ?>
                
                <div class="text-sm space-y-6">
                    <h3 class="font-bold uppercase mb-10">CLAUSES:</h3>

                    <div class="space-y-3">
                        <p class="font-bold">Clause One – (Parties)</p>
                        <p class="text-justify">
                            <span class="highlight"><?= $page1['header'] ?></span>, a limited liability company organized and existing under the laws of China, with its registered office at <span class="highlight"><?= $page1['address'] ?></span>, email <span class="highlight">Mia@ledmosun.com</span>, and phone number <span class="highlight">+86 18237627197</span>. Hereinafter referred to as the <strong class="uppercase">“SELLER.”</strong>
                        </p>
                        <p class="text-justify">
                            <span class="highlight"><?= $data['customer_name'] ?? 'Led Nation LLC' ?></span>, a limited liability company organized and existing under the laws of the United States of America, with its registered office at <span class="highlight"><?= $data['customer_address'] ?? '1309 Coffeen Avenue, Suite 1200, Sheridan, WY 82801' ?></span>, email <span class="highlight">contact@lednationllc.com</span>, and phone number <span class="highlight"><?= $data['customer_tel'] ?? '+1 (307) 228 4125' ?></span>. Hereinafter referred to as the <strong class="uppercase">“BUYER.”</strong>
                        </p>
                    </div>

                    <div class="space-y-3">
                        <p class="font-bold">Clause Two – (Contract Item)</p>
                        <p class="text-justify">
                            The Seller sells to the Buyer the items described in Clause Three, which correspond to:
                        </p>
                        <ul class="list-none pl-4 space-y-1">
                            <li>1) Proforma No. <span class="highlight"><?= $data['pi_number'] ?? 'MSP20260429BOLMIG' ?></span>, dated <span class="highlight"><?= $data['order_date'] ?? '14/04/2026' ?></span></li>
                            <li>2) Commercial Invoice No. <span class="highlight"><?= $data['pi_number'] ?? 'MSPI20260429BOLMIG' ?></span>, dated <span class="highlight"><?= $data['order_date'] ?? '22/05/2026' ?></span></li>
                        </ul>
                    </div>

                    <div class="space-y-3">
                        <p class="font-bold">Clause Three – (Specification of Goods to Be Imported)</p>
                        <p class="text-justify">
                            The goods to be imported, which correspond to the documentation referenced in Clause Two, are described as follows:
                        </p>
                        <ul class="list-none pl-4 space-y-1">
                            <li>- <span class="highlight"><?= $data['total_cartons'] ?? '120' ?></span> cartons.</li>
                            <li>- Gross weight: <span class="highlight"><?= $data['gross_weight'] ?? '255.8' ?></span> kg.</li>
                            <li>- Item Brand:
                                <div class="pl-4">a. Lumens</div>
                            </li>
                            <li>- Items:</li>
                        </ul>
                    </div>
                </div>
            </div>


        </div>

    </div>
</body>
</html>
