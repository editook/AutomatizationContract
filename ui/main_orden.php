<?php
include_once('../config/configs.php');
session_start();
require_once __DIR__ . '/../models/model_configuration.php';

$step = $_GET['step'] ?? 'upload';
$collection = $_SESSION['collection_data'] ?? [];
unset($collection['3']); // Los totales se muestran en el resumen dedicado.
$itemsTable = $_SESSION['collection_table'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 'review') {
    $submittedGroups = $_POST['collection_data'] ?? [];
    $currentCollection = $_SESSION['collection_data'] ?? [];

    foreach ($currentCollection as $groupKey => $group) {
        if (!is_array($group)) {
            continue;
        }

        $currentItems = $group['items'] ?? [];
        $submittedItems = $submittedGroups[$groupKey]['items'] ?? [];

        if (!is_array($currentItems) || !is_array($submittedItems)) {
            continue;
        }

        foreach ($currentItems as $itemKey => $currentField) {
            if (
                !is_array($currentField)
                || !array_key_exists('value', $currentField)
                || !isset($submittedItems[$itemKey])
                || !is_array($submittedItems[$itemKey])
            ) {
                continue;
            }

            $submittedValue = $submittedItems[$itemKey]['value'] ?? null;
            if (!is_scalar($submittedValue)) {
                continue;
            }

            if ((string) $groupKey === '3' && $itemKey === 'total') {
                $normalizedTotal = preg_replace('/[$,\s]/', '', (string) $submittedValue);
                if (!is_string($normalizedTotal) || !preg_match('/^\d+(?:\.\d{1,2})?$/D', $normalizedTotal)) {
                    continue;
                }
                $submittedValue = $normalizedTotal;
            }

            $currentField['value'] = (string) $submittedValue;
            $currentCollection[$groupKey]['items'][$itemKey] = $currentField;
        }
    }

    $_SESSION['collection_data'] = $currentCollection;

    $currentTable = $_SESSION['collection_table'] ?? [];
    $submittedTable = $_POST['collection_table'] ?? [];
    $editableItemFields = ['item_name', 'model', 'brand', 'material', 'condition', 'length', 'width', 'heigth', 'hc_code', 'gross_weight'];

    if (is_array($currentTable) && is_array($submittedTable)) {
        foreach ($currentTable as $itemIndex => $currentItem) {
            if (!is_array($currentItem) || !isset($submittedTable[$itemIndex]) || !is_array($submittedTable[$itemIndex])) {
                continue;
            }

            foreach ($editableItemFields as $fieldName) {
                $submittedValue = $submittedTable[$itemIndex][$fieldName] ?? null;
                if (is_scalar($submittedValue)) {
                    $currentTable[$itemIndex][$fieldName] = (string) $submittedValue;
                }
            }
        }
    }

    $_SESSION['collection_table'] = $currentTable;
    header('Location: prev-contract.php');
    exit;
}

$currentTable = $_SESSION['collection_table'] ?? [];
$itemsTable = is_array($currentTable) ? $currentTable : [];
$cartonesTotal = 0;
$total = 0.0;
$cantidadTotal = 0;
$gross_weightTotal = 0.0;

foreach ($currentTable as $item) {
    if (!is_array($item)) {
        continue;
    }

    if (is_numeric($item['cartons'] ?? null)) {
        $cartonesTotal += (int) $item['cartons'];
    }
    if (is_numeric($item['subtotal'] ?? null)) {
        $total += (float) $item['subtotal'];
    }
    if (is_numeric($item['quantity'] ?? null)) {
        $cantidadTotal += (int) $item['quantity'];
    }
    if (is_numeric($item['gross_weight'] ?? null)) {
        $gross_weightTotal = $gross_weightTotal + (float) $item['gross_weight'];
    }
}
$gross_weightTotal ??= 'N/D';
$amountInWords = amountToEnglishWords((string)$total);

if ($itemsTable !== [] && is_array($_SESSION['collection_data'] ?? null)) {
    $summaryDefaults = [
        'cartonesTotal' => ['value' => $cartonesTotal, 'name_show' => 'Total Cartones'],
        'total' => ['value' => $total, 'name_show' => 'Total'],
        'cantidadTotal' => ['value' => $cantidadTotal, 'name_show' => 'Cantidad Total'],
        'gross_weightTotal' => ['value' => $gross_weightTotal, 'name_show' => 'Peso Bruto Total'],
        'amountInWords' => ['value' => $amountInWords, 'name_show' => 'Monto en Texto'],
    ];
    $savedSummary = $_SESSION['collection_data']['3']['items'] ?? [];
    $summaryFields = [];

    foreach ($summaryDefaults as $key => $default) {
        $savedField = $savedSummary[$key] ?? [];
        $summaryFields[$key] = [
            'value' => is_array($savedField) && array_key_exists('value', $savedField)
                ? $savedField['value']
                : $default['value'],
            'name_show' => $default['name_show'],
            'visible' => is_array($savedField) ? (bool) ($savedField['visible'] ?? true) : true,
        ];
    }

    $_SESSION['collection_data']['3'] = [
        'titulo' => '',
        'items' => $summaryFields,
    ];

    $cartonesTotal = $summaryFields['cartonesTotal']['value'];
    $total = $summaryFields['total']['value'];
    $cantidadTotal = $summaryFields['cantidadTotal']['value'];
    $gross_weightTotal = $summaryFields['gross_weightTotal']['value'];
    $amountInWords = $summaryFields['amountInWords']['value'];
} else {
    $summaryFields = [];
}

$editableItemFields = [
    'item_name' => 'Item Name(Nombre)',
    'model' => 'Model(Modelo)',
    'brand' => 'Brand(Marca)',
    'material' => 'Material',
    'condition' => 'Condicion',
    'length' => 'Largo',
    'width' => 'Ancho',
    'heigth' => 'Alto',
    'hc_code' => 'HC Code(Codigo HC)',
    'gross_weight' => 'Gross Weight',

];
$fixedItemFields = [
    'price' => 'Precio',
    'quantity' => 'Cantidad',
    'subtotal' => 'Subtotal',
    'cartons' => 'Cartones',
    
];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= WEB_TITLE ?> - Cargar Archivos por medio de una Orden</title>
    <link rel="icon" href="../assets/img/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="../assets/css/tailwind.css?<?= date('Y-m-d-H-i') ?>">
    <style>
        .drop-zone {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: border 0.3s ease;
        }

        .drop-zone:hover {
            border-color: #0054a6;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
        }

        .summary-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }
    </style>
</head>

<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4 text-gray-100">
    <div class="bg-gray-800 p-8 rounded-lg shadow-2xl w-full border border-gray-700 <?php if ($step === 'upload') {
                                                                                        echo 'max-w-6xl';
                                                                                    } ?>">
        <h1 class="text-2xl font-bold text-center text-white mb-6">LECTURA DE ARCHIVOS</h1>

        <?php if ($step === 'upload'): ?>

            <form action="process_orden.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2 justify-self-center">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-300">
                            <img src="../assets/svg/excel.svg" class="h-7 w-7" alt="">
                            <span>Archivo Orden (.xls)</span>
                        </label>
                        <input type="file" name="orden_file" accept=".xls,.xlsx" required
                            class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-900 file:text-blue-200 hover:file:bg-blue-800">
                    </div>
                    
                    <div class="space-y-2 justify-self-center">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-300">
                            <img src="../assets/svg/pdf.svg" class="h-7 w-7" alt="">
                            <span>Factura (.pdf)</span>
                        </label>
                        <input type="file" name="invoice_file" accept=".pdf" required
                            class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-900 file:text-blue-200 hover:file:bg-blue-800">
                    </div>
                </div>
                <div class="flex justify-center pt-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                        Procesar Archivos
                    </button>
                </div>
            </form>
        <?php elseif ($step === 'review'): ?>
            <form action="main_orden.php?step=review" method="POST" class="space-y-4">
                <?php if (is_array($itemsTable) && $itemsTable !== []): ?>
                    <section class="space-y-3">
                        <h3 class="text-lg font-semibold text-yellow-500">Items</h3>
                        <div class="">
                            <table class="w-full text-sm border-collapse">
                                <thead>
                                    <tr>
                                        <?php foreach (array_merge(['model' => 'Modelo'], $fixedItemFields, array_diff_key($editableItemFields, ['model' => true])) as $label): ?>
                                            <th class="border border-gray-600 px-2 py-2 text-left text-gray-300"><?= htmlspecialchars($label) ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($itemsTable as $itemIndex => $item): ?>
                                        <?php if (!is_array($item)) continue; ?>
                                        <tr>
                                            <td class="border border-gray-600 px-2 py-2">
                                                <input type="text" name="collection_table[<?= (int) $itemIndex ?>][model]" value="<?= htmlspecialchars( ($item['model'] ?? '')) ?>" class="bg-gray-700 border border-gray-600 text-white p-2 rounded w-full">
                                            </td>
                                            <?php foreach ($fixedItemFields as $fieldName => $label): ?>
                                                <td class="border border-gray-600 px-2 py-2 text-gray-200"><?= htmlspecialchars( ($item[$fieldName] ?? '')) ?></td>
                                            <?php endforeach; ?>
                                            <?php foreach (array_diff_key($editableItemFields, ['model' => true]) as $fieldName => $label): ?>
                                                <td class="border border-gray-600 px-2 py-2">
                                                    <input type="text" name="collection_table[<?= (int) $itemIndex ?>][<?= htmlspecialchars($fieldName) ?>]" value="<?= htmlspecialchars( ($item[$fieldName] ?? '')) ?>" class="bg-gray-700 border border-gray-600 text-white p-2 rounded w-full">
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="summary-grid">
                            <div class="rounded border border-gray-700 bg-gray-900 p-2">
                                <span class="block text-xs font-bold text-gray-400 uppercase">Cartones</span>
                                
                                    <input type="text" <?= $summaryFields['cartonesTotal']['visible']?'':'disabled' ?>  name="collection_data[3][items][cartonesTotal][value]" value="<?= htmlspecialchars( $cartonesTotal) ?>" class="mt-1 w-full rounded border border-gray-600 bg-gray-900 p-2 text-xs font-bold text-white">
                               
                            </div>
                            <div class="rounded border border-gray-700 bg-gray-900 p-2">
                                <span class="block text-xs font-bold text-gray-400 uppercase">Total</span>
                                
                                    <input type="text" <?= $summaryFields['total']['visible']?'':'disabled' ?>  name="collection_data[3][items][total][value]" value="<?= htmlspecialchars(number_format((float) $total, 2, '.', ',')) ?>" class="mt-1 w-full rounded border border-gray-600 bg-gray-900 p-2 text-xs font-bold text-white">
                                
                            </div>
                            <div class="rounded border border-gray-700 bg-gray-900 p-2">
                                <span class="block text-xs font-bold text-gray-400 uppercase">Cantidad</span>
                                
                                    <input type="text" <?= $summaryFields['cantidadTotal']['visible']?'':'disabled' ?> name="collection_data[3][items][cantidadTotal][value]" value="<?= htmlspecialchars( $cantidadTotal) ?>" class="mt-1 w-full rounded border border-gray-600 bg-gray-900 p-2 text-xs font-bold text-white">
                               
                            </div>
                            <div class="rounded border border-gray-700 bg-gray-900 p-2">
                                <span class="block text-xs font-bold text-gray-400 uppercase">Peso Total</span>
                                
                                    <input type="text" <?= $summaryFields['gross_weightTotal']['visible']?'':'disabled' ?>  name="collection_data[3][items][gross_weightTotal][value]" value="<?= htmlspecialchars( $gross_weightTotal) ?>" class="mt-1 w-full rounded border border-gray-600 bg-gray-900 p-2 text-xs font-bold text-white">
                                  
                            </div>

                        </div>
                        <div class="rounded border border-gray-700 bg-gray-900 p-2">
                            <span class="block text-xs font-bold text-gray-400 uppercase">Monto en Literal</span>
                            
                                <input type="text" <?= $summaryFields['amountInWords']['visible']?'':'disabled' ?>  name="collection_data[3][items][amountInWords][value]" value="<?= htmlspecialchars( $amountInWords) ?>" class="mt-1 w-full rounded border border-gray-600 bg-gray-900 p-2 text-xs font-bold text-white">
                            
                        </div>
                    </section>
                <?php endif; ?>
                <div class="summary-grid-2">
                    <?php foreach ($collection as $groupKey => $group): ?>
                        <?php
                        if (!is_array($group)) {
                            continue;
                        }

                        $groupTitle = $group['titulo'] ?? '';
                        $items = $group['items'] ?? [];
                        if (!is_array($items)) {
                            continue;
                        }
                        ?>
                        <section class="space-y-3 border border-gray-700 p-2">
                            <?php if ($groupTitle !== ''): ?>
                                <h3 class="text-lg font-semibold text-yellow-500"><?= htmlspecialchars( $groupTitle) ?></h3>
                            <?php endif; ?>

                            <?php foreach ($items as $key => $field): ?>
                                <?php
                                if (!is_array($field) || !array_key_exists('value', $field)) {
                                    continue;
                                }
                                if (!($field['visible'] ?? false)) {
                                    continue;
                                }

                                $fieldValue = is_scalar($field['value']) ? (string) $field['value'] : '';

                                $fieldLabel = $field['name_show'] ?? str_replace('_', ' ', (string) $key);
                                ?>
                                <div class="flex flex-col space-y-1">
                                    <label class="text-xs font-bold text-gray-400 uppercase"><?= htmlspecialchars( $fieldLabel) ?></label>
                                    <input type="text" name="collection_data[<?= htmlspecialchars( $groupKey) ?>][items][<?= htmlspecialchars( $key) ?>][value]" value="<?= htmlspecialchars( $fieldValue) ?>"
                                        class="bg-gray-700 border border-gray-600 text-white p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                            <?php endforeach; ?>
                        </section>
                    <?php endforeach; ?>
                </div>
                <div class="flex justify-between pt-6">
                    <a href="main_orden.php?step=upload" class="text-blue-400 hover:underline">Volver a cargar</a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                        Confirmar
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>

</body>

</html>
