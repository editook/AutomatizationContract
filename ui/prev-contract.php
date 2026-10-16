<?php
include_once('../config/configs.php');
session_start();

function getDataRender(array $dataRender): array
{
    $renderData = [];

    foreach ($dataRender as $group) {
        $items = $group['items'] ?? [];
        if (!is_array($items)) {
            continue;
        }

        foreach ($items as $key => $field) {
            $renderData[$key] = is_array($field) && array_key_exists('value', $field)
                ? $field['value']
                : $field;
        }
    }

    return $renderData;
}

$items_values = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

$data_collection = $_SESSION['collection_data'] ?? [];
$items_collection = $_SESSION['collection_table'] ?? [];
$itemBrands = [];
$itemNames = [];
$itemModels = [];
foreach ($items_collection as $itemIndex => $product) {
    $itemBrands[] = $product['brand'];
    $itemNames[] = $product['item_name'];
    $itemModels[] = $product['model'];
}
$itemBrands = array_values(array_unique($itemBrands));
$itemNames = array_values(array_unique($itemNames));
$itemModels = array_values(array_unique($itemModels));
//echo json_encode($data_collection);
$data = getDataRender($data_collection);



function renderHeader($page)
{
?>
    <div class="print-header print-header-force flex justify-between items-start mb-10">
        <img src="../assets/img/log-company.png?<?= date('Y-m-d-H-i') ?>" class="h-auto w-40" alt="Company Logo">
        <div class="text-right">
            <h1 class="text-lg font-bold leading-tight text-[27px] text-center"><?= $page['header'] ?></h1>
            <p class="text-sm text-center mt-8">Add.:<?= $page['address'] ?></p>
        </div>
    </div>
    <h3 class="print-header-force print-contract-number mb-10 font-bold">Contract No: <span class="font-normal"><?= $page['contract'] ?></span></h3>

<?php
}

if (empty($data)) {
    echo "<script>
        alert('No se pudo completar los datos de forma correcta');
        window.location.href = 'main.php?step=upload';
    </script>";
    exit;
}

$pageRender = [
    'header' => $data['header'] ?? '',
    'address' => $data['address'] ?? '',
    'contract' => $data['contract'] ?? '',
];


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= WEB_TITLE ?> - Previsualización</title>
    <link rel="icon" href="../assets/img/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="../assets/css/tailwind.css?<?= date('Y-m-d-H-i') ?>">
    <style>
        .contract-page {
            width: 800px;
            min-height: auto;
            background: white;
            color: black;
            padding-bottom: 10mm;
            padding-left: 20mm;
            padding-right: 20mm;
            margin: 0 auto;
            font-family: 'Times New Roman', Times, serif;
            position: relative;
        }

        .print-document {
            width: 100%;
            border-collapse: collapse;
        }

        .print-header-force {
            display: none;
        }

        .highlight {
            background-color: yellow;
        }

        .page-break {
            break-before: page;
            page-break-before: always;
        }

        .border-table th,
        .border-table td {
            border: 1px solid black;
            padding: 2px 2px;
        }

        @media print {
            .print-header-force {
                display: flex;
            }

            @page {
                size: A4;
                margin: 0mm;
                margin-top: 10mm;
                margin-bottom: 10mm;
            }

            .no-print {
                display: none;
            }

            body {
                background: white;
                padding: 0;
            }

            .print-document {
                width: 100%;
                table-layout: fixed;
            }

            .print-document thead {
                display: table-header-group;
            }

            .print-document tbody {
                display: table-row-group;
            }

            .print-document tr,
            .print-document td {
                break-inside: auto;
                page-break-inside: auto;
            }

            .print-header-cell {
                padding: 8mm 20mm 6mm;
                vertical-align: top;
            }

            .print-content-cell {
                padding: 0 20mm 20mm;
                vertical-align: top;

            }

            .print-header {
                position: static;
                margin: 0 0 4mm;
            }

            .print-contract-number {
                margin: 0;
            }

            .contract-stack {
                display: block;
            }

            .contract-page {
                width: auto;
                min-height: 0;
                box-shadow: none;
                padding: 0;
                margin: 0 0 8mm;
            }
        }
    </style>
</head>

<body class="bg-gray-900 p-8">
    <div class="no-print flex justify-center gap-4 mb-10">
        <a href="main.php?step=review" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-600">Volver a Editar</a>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Imprimir PDF</button>
    </div>

    <div class="contract-stack flex flex-col items-center">
        <table class="print-document">
            <thead>
                <tr>
                    <td class="print-header-cell"><?php renderHeader($pageRender); ?></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="print-content-cell">
                        <div class="contract-page">

                            <div class="space-y-6 text-sm pt-6">
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
                                            <td class="border border-black font-bold pl-1">Contract No: <span class="font-normal highlight"><?= $data['contract'] ?></span></td>
                                            <td class="border border-black font-bold pl-1">Date: <span class="font-normal highlight"><?= $data['order_date'] ?></span></td>
                                        </tr>
                                        <tr>
                                            <td class="border border-black pl-1"><strong>Buyer:</strong> <span class="font-normal highlight"><?= $data['customer_name'] ?></span></td>
                                            <td class="border border-black pl-1"><strong>Seller:</strong> <span class="font-normal highlight"><?= $data['header'] ?></span></td>
                                        </tr>
                                        <tr>
                                            <td class="border border-black pl-1"><strong>Address:</strong> <span class="font-normal highlight"><?= $data['customer_address'] ?></span></td>
                                            <td class="border border-black pl-1"><strong>Address:</strong> <span class="font-normal highlight"><?= $data['address'] ?></span></td>
                                        </tr>
                                        <tr>
                                            <td class="border border-black pl-1"><strong>Contact:</strong> <span class="font-normal highlight"><?= $data['customer_tel'] ?></span>
                                                <br><strong>Email:</strong> <span class="font-normal highlight"><?= $data['customer_email'] ?></span>
                                            </td>
                                            <td class="border border-black pl-1"><strong>Tel:</strong> <span class="font-normal highlight"><?= $data['tel'] ?></span>
                                                <br><strong>Email:</strong> <span class="font-normal highlight"><?= $data['email'] ?></span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" class="border border-black pl-1 italic">
                                                This agreement is made between the buyer and the seller under the following terms and conditions:
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="contract-page">
                            <div class="text-sm space-y-6">
                                <h3 class="mb-10 font-bold">Product Details</h3>

                                <!-- Product Table -->
                                <table class="w-full border-collapse border-table text-sm">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="text-center font-bold w-20">No Item</th>
                                            <th class="text-center font-bold">Item Name</th>
                                            <th class="text-center font-bold">Item Description</th>
                                            <th class="text-center font-bold">HS Code</th>
                                            <th class="text-center font-bold">Quantity</th>
                                            <th class="text-center font-bold">Unitary Price FOB (USD)</th>
                                            <th class="text-center font-bold w-25">Total Amount (USD)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items_collection as $itemIndex => $product): ?>
                                            <?php
                                            if (!is_array($product)) {
                                                continue;
                                            }

                                            $productDimensions = implode('*', [
                                                $product['length'],
                                                $product['width'],
                                                $product['heigth'],
                                            ]) . 'mm';
                                            ?>
                                            <tr>
                                                <td class="pl-1 text-center"><?= (int) $itemIndex + 1 ?></td>
                                                <td class="pl-1 text-center"><?= htmlspecialchars($product['item_name']) ?></td>
                                                <td class="pl-1 font-bold">
                                                    Brand: <span class="highlight"><?= htmlspecialchars($product['brand']) ?></span><br>
                                                    Model:<span class="highlight"><?= htmlspecialchars($product['model']) ?></span><br>
                                                    Dimensions:<span class="highlight"><?= htmlspecialchars($productDimensions) ?></span><br>
                                                    Material:<span class="highlight"><?= htmlspecialchars($product['material']) ?></span><br>
                                                    Condition:<span class="highlight"><?= htmlspecialchars($product['condition']) ?></span>
                                                </td>
                                                <td class="pl-1 text-center">(<span class="highlight"><?= htmlspecialchars($product['hc_code']) ?></span>)</td>
                                                <td class="pl-1 text-center"><span class="highlight"><?= $product['quantity'] ?></span> </td>
                                                <td class="pl-1 text-center"><span class="highlight">$<?= number_format((float) ($product['price'] ?? 0), 2, '.', ',') ?></span></td>
                                                <td class="pl-1 text-center"><span class="highlight">$<?= number_format((float) ($product['subtotal'] ?? 0), 2, '.', ',') ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class=""></td>
                                            <td class="text-center font-bold"> <span class="highlight"><?= $data['cantidadTotal'] ?? 0 ?></span></td>
                                            <td class="text-center font-bold">Total Amount</td>
                                            <td class="text-center font-bold"><span class="highlight">$<?= number_format($data['total'], 2, '.', ',') ?></span> </td>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>
                        <div class="contract-page page-break">
                            <div class="text-sm space-y-6">
                                <h3 class="font-bold uppercase mb-10">CLAUSES:</h3>

                                <div class="space-y-3">
                                    <p class="font-bold">Clause One – (Parties)</p>
                                    <p class="text-justify">
                                        <span class="highlight"><?= $data['header'] ?></span>, a limited liability company organized and existing under the laws of China, with its registered office at <span class="highlight"><?= $data['address'] ?></span>, email <span class="highlight"><?= $data['email'] ?></span>, and phone number <span class="highlight"><?= $data['tel'] ?></span>. Hereinafter referred to as the <strong class="uppercase">“SELLER.”</strong>
                                    </p>
                                    <p class="text-justify">
                                        <span class="highlight"><?= $data['customer_name'] ?></span>, a limited liability company organized and existing under the laws of the United States of America, with its registered office at <span class="highlight"><?= $data['customer_address'] ?></span>, email <span class="highlight"><?= $data['customer_email'] ?></span>, and phone number <span class="highlight"><?= $data['customer_tel'] ?></span>. Hereinafter referred to as the <strong class="uppercase">“BUYER.”</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="contract-page">
                            <div class="text-sm space-y-6">
                                <p class="font-bold">Clause Two – (Contract Item)</p>
                                <p class="text-justify">
                                    The Seller sells to the Buyer the items described in Clause Three, which correspond to:
                                </p>
                                <ul class="list-none pl-4 space-y-1">
                                    <li>1) Proforma No. <span class="highlight"><?= $data['contract'] ?></span>, dated <span class="highlight"><?= $data['order_date_proforma'] ?></span></li>
                                    <li>2) Commercial Invoice No. <span class="highlight"><?= $data['contract'] ?></span>, dated <span class="highlight"><?= $data['order_date']  ?></span></li>
                                </ul>
                            </div>
                        </div>

                        <div class="contract-page">
                            <div class="text-sm space-y-6">
                                <p class="font-bold">Clause Three – (Specification of Goods to Be Imported)</p>
                                <p class="text-justify">
                                    The goods to be imported, which correspond to the documentation referenced in Clause Two, are described as follows:
                                </p>
                                <ul class="list-none pl-4 space-y-1">
                                    <li>- <span class="highlight"><?= $data['cartonesTotal'] ?></span> cartons.</li>
                                    <li>- Gross weight: <span class="highlight"><?= $data['gross_weightTotal'] ?></span> kg.</li>
                                    <li>- Item Brand:
                                        <?php foreach ($itemBrands as $itemIndex => $item) {
                                            $index = $items_values[$itemIndex];
                                        ?>
                                            <div class="pl-4"><?= $index ?>. <span class="highlight"><?= ucwords(strtolower($item)) ?></span></div>
                                        <?php } ?>
                                    </li>
                                    <li>- Items:
                                        <?php foreach ($itemNames as $itemIndex => $item) {
                                            $index = $items_values[$itemIndex];
                                        ?>
                                            <div class="pl-4"><?= $index ?>. <span class="highlight"><?= ucwords(strtolower($item)) ?> </span></div>
                                        <?php } ?>

                                    </li>
                                    <li>- Model:
                                        <?php foreach ($itemModels as $itemIndex => $item) {
                                            $index = $items_values[$itemIndex];
                                        ?>
                                            <div class="pl-4"><?= $index ?>. <span class="highlight"><?= ucwords($item) ?></span> </div>
                                        <?php } ?>
                                    </li>

                                </ul>
                            </div>
                        </div>

                        <div class="contract-page">
                            <div class="text-sm space-y-6">
                                <p class="font-bold">Clause Four – (Price and Forms of Payment)</p>
                                <p class="text-justify">
                                    The price agreed upon between the Seller and the Buyer for the imported item shall be <span class="highlight">$<?= number_format($data['total'], 2, '.', ',') ?></span> (<span class="highlight"><?= $data['amountInWords'] ?></span>).
                                </p>
                                <p class="text-justify">
                                    The total contract price shall be paid under mixed payment terms, consisting of advance and deferred payments, as follows:
                                </p>
                                <ol class="list-decimal pl-8 space-y-2">
                                    <li>First advance payment: An amount equivalent to 20% of the total contract price, referenced to the Proforma. This payment shall be made as an advance payment prior to the commencement of production.</li>
                                    <li>Second advance payment: An amount equivalent to 30% of the total contract price, referenced to the Proforma. This payment shall be made prior to the issuance of the Telex Bill of Lading.</li>
                                    <li>Deferred payment: An amount equivalent to the remaining 50% of the total contract price, referenced to the Commercial Invoice. This payment shall be made within a maximum period of ninety (90) days after the arrival of the goods at the port of destination.</li>
                                </ol>
                                <p class="text-justify mt-4">
                                    Pursuant to Article 59 of the CISG, “The buyer must pay the price on the date fixed or determinable under the contract and the present Convention, without the need for any request or other formality from the seller.”
                                </p>
                                <p class="text-justify">
                                    If there is any excess shipment, the surplus shall be returned by the receiving party or, alternatively, may be used as partial payment for the next import.
                                </p>
                            </div>
                        </div>

                        <div class="contract-page">
                            <div class="text-sm space-y-6">
                                <p class="font-bold">Clause Five – (Currency and Method of Payment)</p>
                                <p class="text-justify">
                                    The payment will be made via bank transfer, which shall be deposited to the seller in the following account:
                                </p>

                                <div class="mt-4">
                                    <div class="text-let font-bold uppercase mb-2">BENEFICIARY BANKING INFORMATION: (SELLER)</div>
                                    <div class="border border-black pt-2 pb-2 pl-15 space-y-2 text-sm">
                                        <div class="flex">
                                            <strong class="w-40">Beneficiary:</strong> <span class="highlight"><?= $data['header'] ?></span>
                                        </div>
                                        <div class="flex">
                                            <strong class="w-40">Account No:</strong> <span class="highlight"><?= $data['bank_account'] ?></span>
                                        </div>
                                        <div class="flex">
                                            <strong class="w-40">Country:</strong> <span class="highlight">Hong Kong (China)</span>
                                        </div>
                                        <div class="flex">
                                            <strong class="w-40">Beneficiary Bank:</strong> <span class="highlight"><?= $data['bank_name'] ?></span>
                                        </div>
                                        <div class="flex">
                                            <strong class="w-40">Swift Code:</strong> <span class="highlight"><?= $data['bank_swift'] ?></span>
                                        </div>
                                        <div class="flex">
                                            <strong class="w-40">Bank Address:</strong> <span class="highlight"><?= $data['bank_address'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="contract-page">
                            <div class="text-sm space-y-6">
                                <div class="text-justify mt-6 space-y-4 text-sm">
                                    <p>
                                        In accordance with Article 53 of the CISG, ‘The buyer must pay the price for the goods and take delivery of them as required by the contract,’ and Article 54, ‘The buyer’s obligation to pay the price includes taking such steps and complying with such formalities as may be required under the contract or any laws or regulations to enable payment to be made.’
                                    </p>
                                    <p>
                                        In the event of any change in the beneficiary account, such change must be notified by email at least 3 days in advance.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="contract-page">
                            <div class="text-sm space-y-6">
                                <p class="font-bold">Clause Six – (Delivery Conditions and Timeframe)</p>
                                <p class="text-justify">
                                    The seller undertakes to deliver the merchandise detailed in Clause Three under the FOB Incoterm within a maximum period of 150 calendar days from the issuance of this contract, in accordance with the conditions agreed in Proforma No. <span class="highlight"><?= $data['contract'] ?></span>. The goods must be packaged in cardboard boxes that comply with all safety and protection standards to ensure they arrive at their destination in sound physical condition, with the seller being responsible for contracting inland transportation to the port of departure and/or loading.
                                </p>
                            </div>
                        </div>

                        <div class="contract-page">
                            <div class="text-sm space-y-6">
                                <p class="font-bold">Clause Seven – (Warranty and Replacement Conditions)</p>
                                <p class="text-justify">
                                    The seller guarantees that all goods supplied are free from manufacturing or operational defects:
                                </p>
                                <p class="text-justify">
                                    General Warranty: A warranty period of 2 years is established for all products against any manufacturing defects. The buyer shall only be required to give written notice specifying the defective goods to activate coverage.
                                </p>
                                <p class="text-justify">
                                    Invoicing and Customs Value for Replacements: Any replacement goods delivered under this warranty shall be declared at their regular commercial value on the corresponding shipping and customs documentation. However, the commercial invoice must expressly state that no payment is due (zero balance/no payment receipt required) as the shipment constitutes a warranty replacement for a previously executed order.
                                </p>
                            </div>
                        </div>

                        <div class="contract-page">
                            <div class="text-sm space-y-6">
                                <p class="font-bold">Clause Eight – (Transport)</p>
                                <p class="text-justify">
                                    The seller commits to delivering the goods at the port of Yantian, China. Likewise, the seller undertakes to ensure that the products are in the necessary condition for safe transportation.
                                </p>
                            </div>
                        </div>

                        <div class="contract-page">
                            <div class="text-sm space-y-6">
                                <p class="font-bold">Clause Nine – (Documents Provided by Seller)</p>
                                <p class="text-justify">
                                    The seller undertakes to provide the following documents:
                                </p>
                                <ul class="list-none pl-4 space-y-1">
                                    <li>1) Proforma</li>
                                    <li>2) Commercial Invoice</li>
                                    <li>3) Packing List</li>
                                    <li>4) Invoice Certificate</li>
                                    <li>5) Other documents as required</li>
                                </ul>
                                <p class="text-justify mt-4">
                                    The same documentation shall be delivered to the buyer at the address: <span class="highlight"><?= $data['customer_address'] ?></span> or to the corresponding email address, in order to have proof of delivery by the seller, pursuant to Article 30 of the CISG: “The seller must deliver the goods, transfer property in them, and deliver any documents relating to them in the manner and form required by the contract and this Convention.”
                                </p>
                            </div>
                        </div>

                        <div class="contract-page">
                            <div class="text-sm space-y-6">
                                <p class="font-bold">Clause Ten – (Contract Validity)</p>
                                <p class="text-justify">
                                    The term of this contract shall remain in effect until the parties have fulfilled their respective obligations specified in Clauses Three and Four. Upon fulfillment of the obligations by the parties, this contract shall be considered concluded.
                                </p>
                            </div>
                        </div>

                        <div class="contract-page">
                            <div class="text-sm space-y-6">
                                <p class="font-bold">Clause Eleven – (Termination)</p>
                                <p class="text-justify">
                                    This Contract may be terminated by mutual agreement of the parties or by unilateral declaration in the event of a fundamental breach by the other party.
                                </p>
                                <p class="text-justify">
                                    For purposes of this Contract, a breach shall be deemed fundamental in accordance with article 25 CISG: “A breach of contract by one of the parties is fundamental when it results in such detriment to the other party as substantially to deprive them of what they are entitled to expect under the contract, unless the party in breach could not have foreseen such result, and a reasonable person in the same circumstances would not have foreseen it.” when it substantially deprives the aggrieved party of what it is entitled to expect under the Contract. The exercise of avoidance, termination, or related remedies shall be interpreted in accordance with the CISG and, to the extent necessary for matters not expressly settled therein, the UNIDROIT Principles of International Commercial Contracts 2016.
                                </p>
                                <p class="text-justify">
                                    Termination shall be without prejudice to claims for damages or any other remedy available under the CISG.
                                </p>
                            </div>
                        </div>

                        <div class="contract-page">
                            <div class="text-sm space-y-6">
                                <p class="font-bold">Clause Twelve – (Arbitration)</p>
                                <p class="text-justify">
                                    For the interpretation, execution, and fulfillment of the clauses of this contract, and for the resolution of any dispute arising from it, the parties agree to submit to the existing foreign trade conciliation and arbitration in the exporting country.
                                </p>
                            </div>
                        </div>

                        <div class="contract-page">
                            <div class="text-sm space-y-6">
                                <p class="font-bold">Clause Thirteen – (General Conditions)</p>
                                <p class="text-justify">
                                    These general conditions are agreed upon to the extent they are applicable and form part of an international sales contract between the two named parties.
                                </p>
                                <p class="text-justify mt-2">
                                    In case of discrepancy between these general conditions and any specific condition agreed upon by the parties in the future, the clauses described above shall prevail.
                                </p>
                                <p class="text-justify mt-2">
                                    Any situation regarding this contract not expressly or implicitly agreed upon shall be governed by:
                                </p>
                                <ul class="list-none pl-4 space-y-1">
                                    <li>a. The United Nations Convention on Contracts for the International Sale of Goods (CISG).</li>
                                    <li>b. For matters not covered by the CISG, the law of the country where the seller has its headquarters shall apply.</li>
                                </ul>
                                <p class="text-justify mt-2">
                                    No party may unilaterally modify this document without the consent of the other party through acceptance via email.
                                </p>
                            </div>
                        </div>


                        <div class="contract-page">
                            <div class="text-sm space-y-6">
                                <p class="font-bold">Clause Fourteen – (Acceptance)</p>
                                <p class="text-justify">
                                    Upon signing this contract, the parties accept and acknowledge that there is no defect of consent that could jeopardize the validity of this contract.
                                </p>
                            </div>
                        </div>

                        <div class="contract-page">
                            <div class="mt-10 space-y-6">
                                <table class="w-full border-collapse border border-black">
                                    <thead>
                                        <tr>
                                            <th class="border border-black p-2 text-left font-bold w-1/2">SELLER (SIGNATURE)</th>
                                            <th class="border border-black p-2 text-left font-bold w-1/2">BUYER (SIGNATURE)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border border-black h-32"></td>
                                            <td class="border border-black h-32"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>




                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
<script>

</script>

</html>