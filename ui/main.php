<?php
include_once('../config/configs.php');
session_start();

$step = $_GET['step'] ?? 'upload';
$collection = $_SESSION['collection_data'] ?? [];

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
            if (!is_array($currentField)
                || !array_key_exists('value', $currentField)
                || !isset($submittedItems[$itemKey])
                || !is_array($submittedItems[$itemKey])) {
                continue;
            }

            $submittedValue = $submittedItems[$itemKey]['value'] ?? null;
            if (!is_scalar($submittedValue)) {
                continue;
            }

            $currentField['value'] = (string) $submittedValue;
            $currentCollection[$groupKey]['items'][$itemKey] = $currentField;
        }
    }

    $_SESSION['collection_data'] = $currentCollection;
    header('Location: prev-contract.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= WEB_TITLE ?> - Cargar Archivos</title>
    <link rel="icon" href="../assets/img/logo.png" type="image/x-icon" />
    <link rel="stylesheet" href="../assets/css/tailwind.css">
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
    </style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4 text-gray-100">
    <div class="bg-gray-800 p-8 rounded-lg shadow-2xl max-w-2xl w-full border border-gray-700">
        <h1 class="text-2xl font-bold text-center text-white mb-6">LECTURA DE ARCHIVOS</h1>
        
        <?php if ($step === 'upload'): ?>
            <form action="process.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Archivo MSPI (.xls)</label>
                        <input type="file" name="mspi_file" accept=".xls,.xlsx" required 
                                class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-900 file:text-blue-200 hover:file:bg-blue-800">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Archivo Packing List (.xls)</label>
                        <input type="file" name="packing_file" accept=".xls,.xlsx" required 
                                class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-900 file:text-blue-200 hover:file:bg-blue-800">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Factura local (.pdf)</label>
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
            <form action="main.php?step=review" method="POST" class="space-y-4">
                <div class="grid grid-cols-1 gap-4">
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
                        <section class="space-y-3">
                            <?php if ($groupTitle !== ''): ?>
                                <h3 class="text-lg font-semibold text-yellow-500"><?= htmlspecialchars((string) $groupTitle, ENT_QUOTES, 'UTF-8') ?></h3>
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
                                    <label class="text-xs font-bold text-gray-400 uppercase"><?= htmlspecialchars((string) $fieldLabel, ENT_QUOTES, 'UTF-8') ?></label>
                                    <input type="text" name="collection_data[<?= htmlspecialchars((string) $groupKey, ENT_QUOTES, 'UTF-8') ?>][items][<?= htmlspecialchars((string) $key, ENT_QUOTES, 'UTF-8') ?>][value]" value="<?= htmlspecialchars((string) $fieldValue, ENT_QUOTES, 'UTF-8') ?>"
                                            class="bg-gray-700 border border-gray-600 text-white p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                            <?php endforeach; ?>
                        </section>
                    <?php endforeach; ?>
                </div>
                <div class="flex justify-between pt-6">
                    <a href="main.php?step=upload" class="text-blue-400 hover:underline">Volver a cargar</a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                        Confirmar
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>

</body>
</html>
