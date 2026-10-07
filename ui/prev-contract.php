<?php
include_once('../configs.php');
// MCMI IV Result View
session_start();

//codifica lo que fuera necesario para completar la tarea
// el html es un ejemplo de como generar tablas y informacion para realizar el contrato
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= WEB_TITLE ?> - Resultados MCMI-IV</title>
    <link rel="icon" href="/../assets/img/favicon.png" type="image/x-icon" />

    <link href="../../assets/css/prev-contract.css?v=<?= VERSION_CODE ?>" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/tailwind.css">

    <style>
        @media (min-width: 1536px) {
            .container {
                max-width: 1200px !important;
            }
        }

        svg {
            display: inline !important;
        }

        .main-content {
            background: white;
        }
    </style>
</head>

<body class="main-body">


    <div class="page <?= TESTING == '1' ? 'istesting' : '' ?>">
        <?php include("../include/header_top.php"); ?>
        <?php include("../include/header_bottom.php"); ?>

        <div class="main-content horizontal-content">
            <br>
            <div class="container">
                <div class="row row-sm container-short">
                    <div class="col-md-12 col-xl-12 col-xs-12 col-sm-12 hidden">
                        <div id="contenidoPortada">
                            <div class="h-6 bg-[#0054a6] w-full mb-1"></div>
                            <div class="flex justify-between pt-4 pb-4">
                                <img width="400" src="../../assets/img/test_image/mcmmi.png?v=<?= VERSION_CODE ?>" class="img-logo">
                            </div>
                            <div class="border-b-2 border-[#0054a6] mt-1 mb-4"></div>
                        </div>
                        <div class="flex justify-between items-end text-[17px] text-black leading-tight">
                            <div class="text-[17px] text-black">
                                <div class="mb-2"><?= $register['type_question_name'] ?></div>
                                <div class="mb-2">Millon* Clinical Multiaxial Inventory-IV</div>
                                <div class="mb-2">Interpretive Report</div>
                                <div class="mb-4">Theodore Millon, PhD, DSc</div>
                                <div class="font-bold mb-2">Nombre: <span class="font-normal"><?= htmlspecialchars($register['id_client']) ?></span></div>
                                <div class="font-bold mb-2">Número de C.I: <span class="font-normal"><?= htmlspecialchars($inputRegister['ci']) ?></span></div>
                                <div class="font-bold mb-2">Edad: <span class="font-normal"><?= htmlspecialchars($register['id_client']) ?></span></div>
                                <div class="font-bold mb-2">Sexo: <span class="font-normal"><?= $register['sex'] ?></span></div>
                                <div class="font-bold mb-2">Ambito: <span class="font-normal"><?= htmlspecialchars($inputRegister['ambito']) ?></span></div>
                                <div class="font-bold mb-2">Estudios: <span class="font-normal"><?= htmlspecialchars($inputRegister['estudios']) ?></span></div>
                                <div class="font-bold mb-2">Región: <span class="font-normal"><?= htmlspecialchars($inputRegister['region']) ?></span></div>
                                <div class="font-bold mb-2">Estado civil: <span class="font-normal"><?= htmlspecialchars($inputRegister['estado_civil']) ?></span></div>
                                <div class="font-bold mb-2">Fecha de evaluación: <span class="font-normal"><?= date("d/m/Y", strtotime($register['date_create'])) ?></span></div>
                                <div class="font-bold mb-2 flex items-center gap-3">Baremo: <span class="font-normal whitespace-nowrap">
                                        <form action="result2.php" method="post" id="form_baremo" class="flex-1">
                                            <input type="hidden" name="id_user" id="id_user" value="<?= $idClient ?>">
                                            <input type="hidden" name="id_register" id="id_register" value="<?= $register['id'] ?>">
                                            <select class="field max-left" id="baremo_id" name="baremo_id">
                                                <?php
                                                foreach ($baremos as $baremo) {
                                                ?>
                                                    <option value="<?= $baremo['id'] ?>" <?= $baremo['active'] == '0' ? 'disabled' : '' ?> <?= $register['baremo_id'] == $baremo['id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($baremo['name']) ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </form>
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <img width="200" src="../../assets/img/test_image/mcmmi-folder.jpg?v=<?= VERSION_CODE ?>" class="img-logo">
                                </div>
                                <div class="mb-2">
                                    Copyright @ 2015 DICANDRIEN, Inc. All rights reserved.
                                </div>
                                <div class="mb-2">
                                    Pearson, the PSI logo, and PsychCorp are trademarks in the U.S. and/or other countries of Pearson Education, Inc., or its affiliate(s). MCMI
                                </div class="mb-2">
                                <div class="mb-2">
                                    and MIllon are registered trademarks of DICANDRIEN, Inc. DSM-5 is a registered trademark of the American Psychiatric Association.
                                </div>
                                <div class="mb-2">
                                    TRADE SECRET INFORMATION
                                </div class="mb-2">
                                <div class="mb-2">
                                    Not for release under HIPAA or other data disclosure laws that exempt trade secrets from disclosure.
                                </div>
                                <div class="mb-2">
                                    [1.0/RE1/QG1]
                                </div>
                                <div>

                                </div>
                                <div>
                                    <img src="../../assets/img/test_image/mcmmi-line.jpg?v=<?= VERSION_CODE ?>" class="img-logo">
                                    <br>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
                        <div id="contenidoID">
                            <div class="h-6 bg-[#0054a6] w-full mb-1"></div>
                            <div class="flex justify-between items-end text-sm text-black leading-tight">
                                <div class="fix-margin">
                                    <div><?= $register['type_question_name'] ?> Informe</div>
                                    <div><?= date("d/m/Y", strtotime($register['date_create'])) ?></div>
                                </div>
                                <div class="text-right fix-margin">
                                    <div><?= $register['type_question_name'] ?></div>
                                    <?= htmlspecialchars($register['id_client']) ?>
                                </div>
                            </div>
                            <div class="border-b-2 border-[#0054a6] mt-1 mb-4"></div>
                        </div>
                    </div>

                    <div class="col-md-12 col-xl-12 col-xs-12 col-sm-12" style="padding-top:10px;padding-left: 0px;padding-right: 0px;">

                        <!-- PAGE 3: RESUMEN DE LAS PUNTUACIONES Y PERFIL -->
                        <div class="font-sans">
                            <!-- Top header banner -->
                            <div id="contenido2" class="pb-4">
                                <h1 class="text-center font-bold text-lg text-black uppercase tracking-wide leading-none mt-2">Inventario Clínico Multiaxial de Millon-IV</h1>
                                <h2 class="text-center font-bold text-[13px] text-black uppercase tracking-wide mt-1">Resumen de las Puntuaciones y Perfil</h2>

                                <div class="flex justify-between text-sm text-black font-sans mt-4 leading-tight">
                                    <div>
                                        <div>CÓDIGO DE PUNTUACIONES MÁXIMAS = <span><?= $max_score_code ?></span></div>
                                        <div>AJUSTES DE LAS TASAS BASE = <span><?= $ajustes_text ?></span></div>
                                    </div>
                                    <div class="text-right">
                                        <div>INVALIDEZ (V) = <span><?= $invalidez ?></span></div>
                                        <div>INCONSISTENCIA (W) = <span><?= $inconsistencia ?></span></div>
                                    </div>
                                </div>
                            </div>


                            <!-- VALIDEZ Table -->
                            <div id="contenido3">
                                <table class="w-full border-collapse border-1 border-black mb-4 font-sans">
                                    <thead>
                                        <tr class="bg-white-100 text-xs text-black">
                                            <th class="border-r-0 border-l border-t border-b border-black-100 text-left px-4 py-1 w-[36.5%] uppercase font-bold text-black align-middle">
                                                <p class="h-full m-0 p-0 fix-margin">Validez</p>
                                            </th>
                                            <th class="border-l-0 border-r border-t border-b border-black text-center py-1 w-20 font-bold"></th>
                                            <th class="border-1 border-black text-center py-1 w-60 font-bold" colspan="2">
                                                <div class="relative w-full h-[28px] font-sans text-xs text-black select-none fix-margin">
                                                    <div class="justify-items-center top-0 font-bold ">Puntuación</div>
                                                    <div class="absolute left-[18%] top-[14px] -translate-x-1/2 font-semibold">PD</div>
                                                    <div class="absolute left-[50%] top-[14px] -translate-x-1/2 font-semibold">PC</div>
                                                    <div class="absolute left-[86%] top-[14px] -translate-x-full font-semibold">TB</div>
                                                </div>
                                            </th>
                                            <th class="border-1 border-black text-center py-1 font-bold">
                                                <div class="h-full fix-margin">
                                                    Perfil de las tasas base
                                                    <div class="relative w-full h-[12px] font-sans text-xs text-black select-none">
                                                        <div class="absolute left-0 top-0 font-bold">0</div>
                                                        <div class="absolute left-[35%] top-0 -translate-x-1/2 font-bold">35</div>
                                                        <div class="absolute left-[75%] top-0 -translate-x-1/2 font-bold">75</div>
                                                        <div class="absolute left-[100%] top-0 -translate-x-full font-bold">100</div>
                                                    </div>
                                                </div>

                                            </th>
                                        </tr>
                                        <tr class="bg-white-100 text-xs text-black">
                                            <th class="border-r-0 border-l border-t border-b border-black text-left w-[36.5%] px-4 py-0.5 font-bold text-black">
                                                <p class="h-full m-0 p-0 fix-margin">Índices modificadores</p>
                                            </th>
                                            <th class="border-l-0 border-r-0 border-t border-b border-black text-center py-1 w-20 font-bold"></th>
                                            <th class="border-l-0 border-r border-t border-b border-black text-center py-0.5 w-[12%]" colspan="2"></th>
                                            <th class="border-1 border-black p-1 w-full">
                                                <div class="relative w-full h-[18px] font-sans text-xs text-black select-none fix-margin">

                                                    <div class="absolute left-[17.5%] top-[1px] -translate-x-1/2 font-semibold">Bajo</div>
                                                    <div class="absolute left-[57.5%] top-[1px] -translate-x-1/2 font-semibold">Medio</div>
                                                    <div class="absolute left-[88.5%] top-[1px] -translate-x-full font-semibold">Alto</div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?= renderScaleRow('validez', 'X', $scale_names, $raw_scores, $percentiles, $final_tbs) ?>
                                        <?= renderScaleRow('validez', 'Y', $scale_names, $raw_scores, $percentiles, $final_tbs) ?>
                                        <?= renderScaleRow('validez', 'Z', $scale_names, $raw_scores, $percentiles, $final_tbs) ?>
                                    </tbody>
                                </table>
                            </div>

                            <div id="contenido4">
                                <table class="w-full border-collapse border border-black mb-4 font-sans">
                                    <thead>
                                        <tr class="bg-white-100 text-xs text-black">
                                            <th class="border-r-0 border-l border-t border-b border-black border-black text-left px-4 py-1 w-[30%] uppercase font-bold text-black">
                                                <p class="h-full m-0 p-0 fix-margin">Personalidad</p>
                                            </th>
                                            <th class="border-l-0 border-r border-t border-b border-black text-center py-1 w-20 font-bold"></th>
                                            <th class="border-1 border-black text-center py-1 w-60 font-bold" colspan="3">
                                                <div class="relative w-full h-[28px] font-sans text-xs text-black select-none fix-margin">
                                                    <div class="justify-items-center top-0 font-bold">Puntuación</div>
                                                    <div class="absolute left-[18%] top-[14px] -translate-x-1/2 font-semibold">PD</div>
                                                    <div class="absolute left-[50%] top-[14px] -translate-x-1/2 font-semibold">PC</div>
                                                    <div class="absolute left-[86%] top-[14px] -translate-x-full font-semibold">TB</div>
                                                </div>
                                            </th>
                                            <th class="border-1 border-black text-center py-1 w-full font-bold">
                                                <div class="h-full fix-margin">
                                                    Perfil de las tasas base
                                                    <div class="relative w-full h-[12px] font-sans text-xs text-black select-none">
                                                        <div class="absolute left-0 top-0 font-bold">0</div>
                                                        <div class="absolute left-[52.17%] top-0 -translate-x-1/2 font-bold">60</div>
                                                        <div class="absolute left-[65.22%] top-0 -translate-x-1/2 font-bold">75</div>
                                                        <div class="absolute left-[73.91%] top-0 -translate-x-1/2 font-bold">85</div>
                                                        <div class="absolute left-[100%] top-0 -translate-x-full font-bold">115</div>
                                                    </div>
                                                </div>

                                            </th>
                                        </tr>
                                        <tr class="bg-white-100 text-xs text-black">
                                            <th class="border-r-0 border-l border-t border-b border-black border-black text-left px-4 py-0.5 font-bold text-black">
                                                <p class="h-full m-0 p-0 fix-margin">Patrones clínicos de la personalidad</p>
                                            </th>
                                            <th class="border-l-0 border-r-0 border-t border-b border-black text-center py-0.5"></th>
                                            <th class="border-l-0 border-r border-t border-b border-black text-center py-0.5 w-[18%]" colspan="3"></th>
                                            <th class="border-1 border-black p-1 w-full">
                                                <div class="relative w-full h-[18px] font-sans text-xs text-black select-none fix-margin">
                                                    <div class="absolute left-[58.7%] top-[1px] -translate-x-1/2 font-semibold">Estilo</div>
                                                    <div class="absolute left-[69.57%] top-[1px] -translate-x-1/2 font-semibold">Tipo</div>
                                                    <div class="absolute left-[91.95%] top-[1px] -translate-x-full font-semibold">Trastorno</div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $scales = ['1', '2A', '2B', '3', '4A', '4B', '5', '6A', '6B', '7', '8A', '8B'];
                                        foreach ($scales as $code) {
                                            echo renderScaleRow('personalidad', $code, $scale_names, $raw_scores, $percentiles, $final_tbs);
                                        }
                                        ?>
                                        <tr class="bg-white-100  text-xs">
                                            <td colspan="6" class="border-1 border-black px-4 py-0.5 font-bold text-black">
                                                <p class="h-full m-0 p-0 fix-margin">Patología grave de la personalidad</p></td>
                                        </tr>
                                        <?= renderScaleRow('personalidad', 'S', $scale_names, $raw_scores, $percentiles, $final_tbs) ?>
                                        <?= renderScaleRow('personalidad', 'C', $scale_names, $raw_scores, $percentiles, $final_tbs) ?>
                                        <?= renderScaleRow('personalidad', 'P', $scale_names, $raw_scores, $percentiles, $final_tbs) ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- PERSONALIDAD Table -->


                            <!-- PSICOPATOLOGÍA Table -->
                            <div id="contenido5">
                                <table class="w-full border-collapse border border-black mb-2 font-sans">
                                    <thead>
                                        <tr class="bg-white-100 text-xs text-black">
                                            <th class="border-r-0 border-l border-t border-b border-black text-left px-4 py-1 w-[30%] uppercase font-bold text-black">
                                                <p class="h-full m-0 p-0 fix-margin">Psicopatología</p>
                                            </th>
                                            <th class="border-l-0 border-r border-t border-b border-black text-center py-1 w-20 font-bold"></th>
                                            <th class="border-1 border-black text-center py-1 w-[18%] font-bold" colspan="3">
                                                <div class="relative w-full h-[28px] font-sans text-xs text-black select-none fix-margin">
                                                    <div class="justify-items-center top-0 font-bold">Puntuación</div>
                                                    <div class="absolute left-[18%] top-[13px] -translate-x-1/2 font-semibold">PD</div>
                                                    <div class="absolute left-[50%] top-[13px] -translate-x-1/2 font-semibold">PC</div>
                                                    <div class="absolute left-[86%] top-[13px] -translate-x-full font-semibold">TB</div>
                                                </div>
                                            </th>
                                            <th class="border-1 border-black text-center py-1 w-full font-bold">
                                                <div class="h-full fix-margin">
                                                    Perfil de las tasas base
                                                    <div class="relative w-full h-[18px] font-sans text-xs text-black select-none">
                                                        <div class="absolute left-0 top-0 font-bold">0</div>
                                                        <div class="absolute left-[45%] top-0 -translate-x-1/2 font-bold">60</div>
                                                        <div class="absolute left-[60%] top-0 -translate-x-1/2 font-bold">75</div>
                                                        <div class="absolute left-[70%] top-0 -translate-x-1/2 font-bold">85</div>
                                                        <div class="absolute left-[100%] top-0 -translate-x-full font-bold">115</div>
                                                    </div>
                                                </div>

                                            </th>
                                        </tr>
                                        <tr class="bg-white-100 text-xs text-black">
                                            <th class="border-r-0 border-l border-t border-b border-black text-left px-4 py-0.5 font-bold text-black">
                                                <p class="h-full m-0 p-0 fix-margin">Síndromes clínicos</p>
                                            </th>
                                            <th class="border-l-0 border-r border-t border-b border-black text-center py-0.5"></th>
                                            <th class="border-1 border-black text-center py-0.5 w-[18%]" colspan="3"></th>
                                            <th class="border-1 border-black p-1 w-full">
                                                <div class="relative w-full h-[14px] font-sans text-xs text-black select-none fix-margin">
                                                    <div class="absolute left-[65.57%] top-[0px] -translate-x-1/2 font-semibold">Presente</div>
                                                    <div class="absolute left-[91.95%] top-[0px] -translate-x-full font-semibold">Prominente</div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $syndromes = ['A', 'H', 'N', 'D', 'B', 'T', 'R'];
                                        foreach ($syndromes as $code) {
                                            echo renderScaleRow('psicopatologia', $code, $scale_names, $raw_scores, $percentiles, $final_tbs);
                                        }
                                        ?>
                                        <tr class="bg-white-100 text-xs">
                                            <td colspan="6" class="border-1 border-black px-4 py-0.5 font-bold text-black">
                                                <p class="h-full m-0 p-0 fix-margin">Síndromes clínicos graves</p> </td>
                                        </tr>
                                        <?= renderScaleRow('psicopatologia', 'SS', $scale_names, $raw_scores, $percentiles, $final_tbs) ?>
                                        <?= renderScaleRow('psicopatologia', 'CC', $scale_names, $raw_scores, $percentiles, $final_tbs) ?>
                                        <?= renderScaleRow('psicopatologia', 'PP', $scale_names, $raw_scores, $percentiles, $final_tbs) ?>
                                    </tbody>
                                </table>

                                <div class="text-xs text-black pt-2 leading-tight fix-margin">Nota. Los guiones (-) indican puntuaciones no disponibles debido a la falta de información de demasiados ítems de una misma escala.</div>
                            </div>

                        </div>

                        <!-- PAGE 4: FACETAS DE GROSSMAN CON LA PUNTUACIÓN MÁS ALTA -->
                        <div class="font-sans pt-5">
                            <!-- Top header banner -->
                            <div id="contenido6">
                                <div>
                                <h1 class="text-center font-bold text-sm text-black uppercase tracking-wide leading-none mt-2">Inventario Clínico Multiaxial de Millon-IV</h1>
                                <h2 class="text-center font-bold text-sm text-black uppercase tracking-wide mt-1 mb-6">Facetas de Grossman con la Puntuación más Alta</h2>
                                </div>
                                

                                <!-- Top 3 scales elevated facets table -->
                                <table class="w-full border-collapse border-1 border-black font-sans" style="margin-top: 30px;">
                                    <thead>
                                        <tr class="bg-white-100 text-xs text-black">
                                            <th class="border-r-0 border-l border-t border-b border-black text-left px-4 py-1 w-[30%] uppercase font-bold text-black">
                                                <p class="h-full m-0 p-0 fix-margin">Facetas de Grossman</p>
                                            </th>
                                            <th class="border-l-0 border-r border-t border-b border-black text-center py-1 w-20 font-bold"></th>
                                            <th class="border-1 border-black text-center py-1 w-[18%] font-bold" colspan="3">
                                                <div class="relative w-full h-[28px] font-sans text-xs text-black select-none fix-margin">
                                                    <div class="justify-items-center top-0 font-bold">Puntuación</div>
                                                    <div class="absolute left-[18%] top-[13px] -translate-x-1/2 font-semibold">PD</div>
                                                    <div class="absolute left-[50%] top-[13px] -translate-x-1/2 font-semibold">PC</div>
                                                    <div class="absolute left-[86%] top-[13px] -translate-x-full font-semibold">TB</div>
                                                </div>
                                            </th>
                                            <th class="border-1 border-black text-center py-1 w-full font-bold">
                                                <div class="relative w-full h-[28px] font-sans text-xs text-black select-none fix-margin">
                                                    <div class="justify-items-center top-0 font-bold">Perfil de las tasas base</div>
                                                    <div class="absolute left-[1%] top-3.5 -translate-x-1/2 font-bold">0</div>
                                                    <div class="absolute left-[35%] top-3.5 -translate-x-1/2 font-bold">35</div>
                                                    <div class="absolute left-[75%] top-3.5 -translate-x-1/2 font-bold">75</div>
                                                    <div class="absolute left-[100%] top-3.5 -translate-x-full font-bold">100</div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $select = 0;
                                        foreach ($top_scales_t as $parent_code => $children) {

                                            $parent_name = $scale_names[$parent_code] ?? '';

                                            $parent_tb = $final_tbs[$parent_code] ?? 0;
                                            $interpretable_label = ($parent_tb >= 60) ? "Interpretable" : "";
                                            if ($parent_tb >= 60) {
                                                $select++;
                                            }
                                            if ($select > 1) {
                                                $interpretable_label = "";
                                            }
                                        ?>
                                            <tr class="border-1 border-black bg-white-100 text-xs h-[22px] font-bold text-black">
                                                <td class="border-r-0 border-l border-t border-b-2 px-4 border-black justify-between">
                                                    <p class="h-full m-0 p-0 fix-margin"><?= htmlspecialchars($parent_name) ?></p>
                                                    
                                                </td>
                                                <td class="border-l-0 border-b-2 border-t border-r-0 border-black px-[.3rem] text-left text-xs font-bold" colspan="4">
                                                    <p class="h-full m-0 p-0 fix-margin"><?= $parent_code ?></p></td>
                                                <td class="border-l border-b-2 border-t border-r border-black text-center text-black text-xs  font-bold" colspan="2">
                                                    <div class="relative w-full font-sans text-center text-xs text-black fix-margin">
                                                        <div class="relative ml-[72%] font-bold "><?= $interpretable_label ?></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                            foreach ($facet_categories[$parent_code] as $f_code) {
                                                $f_name = $facet_names[$f_code] ?? '';
                                                $pd = $raw_scores[$f_code] ?? 0;
                                                $pc = $percentiles[$f_code] ?? 0;
                                                $tb = $facets_tb[$f_code] ?? 0;
                                            ?>
                                                <tr class="border-1 border-black text-xs h-[22px] text-black">
                                                    <td class="border-r-0 border-l border-t border-b border-black px-[2.3rem] text-left">
                                                        <p class="h-full m-0 p-0 fix-margin"><?= $f_name ?></p></td>
                                                    <td class="border-l-0 border-l border-t border-r-0 border-black text-center font-semibold">
                                                        <p class="h-full m-0 p-0 fix-margin"><?= $f_code ?></p></td>
                                                    <td class="border-1 w-[6%] border-black text-center">
                                                        <p class="h-full m-0 p-0 fix-margin"><?= $pd ?></p></td>
                                                    <td class="border-1 w-[6%] border-black text-center">
                                                        <p class="h-full m-0 p-0 fix-margin"><?= $pc ?></p></td>
                                                    <td class="border-1 w-[6%] border-black text-center font-bold">
                                                        <p class="h-full m-0 p-0 fix-margin"><?= $tb ?></p></td>
                                                    <td class="p-0" colspan="2"><?= getBarChartCell('facetas', $tb) ?></td>
                                                </tr>
                                        <?php
                                            }
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <div id="contenido7">
                                <!-- Full 45 Grossman facets score grid -->
                                <h2 class="text-left font-bold text-sm text-black text-center uppercase tracking-wide pb-1 mb-4" style="margin-top: 30px;">Puntuaciones de las Facetas de Grossman</h2>

                                <div class="grid grid-cols-2 gap-x-8 gap-y-4" style="margin-top: 30px;">
                                    <!-- Left Column: Scales 1 to 6A -->
                                    <div>
                                        <?php
                                        $left_scales = ['1', '2A', '2B', '3', '4A', '4B', '5', '6A'];
                                        foreach ($left_scales as $sc) {
                                            $sc_name = $scale_names[$sc] ?? '';
                                        ?>
                                            <div class="mb-3">
                                                <div class="font-bold text-[11px] text-black border-b border-white pb-0.5 mb-1 flex justify-between items-end">
                                                    <span><?= $sc ?> &nbsp; <?= $sc_name ?></span>
                                                    <span class="text-xs text-black tracking-wider">PD &nbsp; PC &nbsp; TB</span>
                                                </div>
                                                <table class="w-full text-xs font-sans">
                                                    <tbody>
                                                        <?php foreach ($facet_categories[$sc] as $f_code) {
                                                            $f_name = $facet_names[$f_code] ?? '';
                                                            $pd = $raw_scores[$f_code] ?? 0;
                                                            $pc = $percentiles[$f_code] ?? 0;
                                                            $tb = $facets_tb[$f_code] ?? 0;
                                                        ?>
                                                            <tr class="h-[16px] border-b border-white">
                                                                <td class="text-left text-black truncate max-w-[170px]">
                                                                    <p class="h-full m-0 p-0 fix-margin"><?= $f_code ?> &nbsp; <?= $f_name ?></p></td>
                                                                <td class="text-right text-black pr-1 w-[20px]">
                                                                    <p class="h-full m-0 p-0 fix-margin"><?= $pd ?></p></td>
                                                                <td class="text-right text-black pr-1 w-[25px]">
                                                                    <p class="h-full m-0 p-0 fix-margin"><?= $pc ?></p></td>
                                                                <td class="text-right text-black w-[25px]">
                                                                    <p class="h-full m-0 p-0 fix-margin"><?= $tb ?></p></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <!-- Right Column: Scales 6B to P -->
                                    <div>
                                        <?php
                                        $right_scales = ['6B', '7', '8A', '8B', 'S', 'C', 'P'];
                                        foreach ($right_scales as $sc) {
                                            $sc_name = $scale_names[$sc] ?? '';
                                        ?>
                                            <div class="mb-3">
                                                <div class="font-bold text-xs text-black pb-0.5 mb-1 flex justify-between items-end">
                                                    <span><?= $sc ?> &nbsp; <?= $sc_name ?></span>
                                                    <span class="text-xs text-black tracking-wider">PD &nbsp; PC &nbsp; TB</span>
                                                </div>
                                                <table class="w-full text-xs font-sans">
                                                    <tbody>
                                                        <?php foreach ($facet_categories[$sc] as $f_code) {
                                                            $f_name = $facet_names[$f_code] ?? '';
                                                            $pd = $raw_scores[$f_code] ?? 0;
                                                            $pc = $percentiles[$f_code] ?? 0;
                                                            $tb = $facets_tb[$f_code] ?? 0;
                                                        ?>
                                                            <tr class="h-[16px]">
                                                                <td class="text-left text-black truncate max-w-[170px]">
                                                                    <p class="h-full m-0 p-0 fix-margin"><?= $f_code ?> &nbsp; <?= $f_name ?></p></td>
                                                                <td class="text-right text-black pr-1 w-[20px]">
                                                                    <p class="h-full m-0 p-0 fix-margin"><?= $pd ?></p></td>
                                                                <td class="text-right text-black pr-1 w-[25px]">
                                                                    <p class="h-full m-0 p-0 fix-margin"><?= $pc ?></p></td>
                                                                <td class="text-right text-black w-[25px]">
                                                                    <p class="h-full m-0 p-0 fix-margin"><?= $tb ?></p></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- ASPECTOS DE INTERPRETACIÓN -->
                        <div class="font-sans" style="text-align: justify;">
                            <h1 class="text-left font-bold text-lg text-black uppercase tracking-wide mb-3" id="jsonvalue1">ASPECTOS DE INTERPRETACIÓN</h1>
                            <p class="text-sm text-black leading-relaxed mb-6" id="jsonvalue2">
                                El MCMI-IV se ha diseñado para evaluar a adultos que buscan atención o tratamiento psicológico. No es, por tanto, un instrumento de evaluación de la personalidad general para población no clínica. Por ello, solo debe utilizarse el MCMI-IV con poblaciones que no difieran notablemente de los sujetos de la muestra en la que se basan los baremos (p. ej., no es apropiado para adolescentes o poblaciones no clínicas). El informe del MCMI-IV no puede interpretarse de forma aislada y debe integrarse con otras fuentes de información. La interpretación del MCMI-IV la debe llevar a cabo un profesional de la salud mental con experiencia en evaluación psicológica.
                            </p>
                            <p class="text-sm text-black leading-relaxed mb-6" id="jsonvalue3">
                                <?= $asp_interpretacion ?>
                            </p>
                            <p class="text-sm text-black leading-relaxed mb-6" id="jsonvalue4">
                                <?= $resultado_final_1 ?>
                            </p>
                            <h1 class="text-left font-bold text-lg text-black uppercase tracking-wide mb-3 " id="jsonvalue5">PATRONES DE PERSONALIDAD</h1>
                            <p class="text-sm text-black leading-relaxed mb-6" id="jsonvalue6">
                                Los siguientes apartados se refieren a los rasgos de personalidad persistentes y generalizados que subyacen a las dificultades emocionales, cognitivas e interpersonales de este evaluado(a). En lugar de centrarse en los síntomas mayormente transitorios que conforman los síndromes clínicos, esta sección se concentra en sus estilos más habituales y desadaptativos de relacionarse, comportarse, pensar y sentir.
                            </p>
                            <p class="text-sm text-black leading-relaxed mb-6" id="jsonvalue7">
                                <?= nl2br(htmlspecialchars($report['personality'])) ?>
                            </p>
                            <h1 class="text-left font-bold text-lg text-black uppercase tracking-wide mb-3" id="jsonvalue8">ESCALAS DE FACETAS DE GROSSMAN</h1>
                            <p class="text-sm text-black leading-relaxed mb-6" id="jsonvalue9">
                                Al examinar las puntuaciones elevadas obtenidas en las escalas de patrones de personalidad clínicos y de patología grave de la personalidad de la Escala de Facetas de Grossman, es posible identificar los ámbitos funcionales y estructurales más problemáticos o clínicamente significativos (por ejemplo, la autoimagen o la conducta interpersonal). Un análisis minucioso de las puntuaciones de esta persona en las escalas de facetas sugiere que las siguientes características se encuentran entre sus rasgos de personalidad más destacados.
                            </p>
                            <p class="text-sm text-black leading-relaxed mb-6" id="jsonvalue10">
                                <?= nl2br(htmlspecialchars($report['facets'])) ?>
                            </p>
                            <h1 class="text-left font-bold text-lg text-black uppercase tracking-wide mb-3" id="jsonvalue11">SÍNDROMES CLÍNICOS</h1>
                            <p class="text-sm text-black leading-relaxed mb-6" id="jsonvalue12">
                                Los síndromes clínicos que se presentan deben entenderse como trastornos integrados en el contexto de los patrones de personalidad del evaluado. Estos síndromes representan estados en los que se manifiesta un proceso patológico activo, a menudo acelerado por acontecimientos externos o situaciones estresantes. Clínicamente, estos síndromes se consideran ampliaciones o distorsiones del estilo básico de personalidad. Durante los periodos de malestar psicológico, los síndromes clínicos exageran, realzan y acentúan las características de la estructura de personalidad básica.
                            </p>
                            <p class="text-sm text-black leading-relaxed mb-6" id="jsonvalue13">
                                <?= nl2br(htmlspecialchars($report['syndromes'])) ?>
                            </p>
                        </div>
                        <!-- PAGE 5: RESPUESTAS SIGNIFICATIVAS -->
                        <div class="font-sans">

                            <h1 class="text-left font-bold text-lg text-black uppercase tracking-wide mb-3" id="jsonvalue14">Respuestas Significativas</h1>

                            <p class="text-sm text-black leading-relaxed mb-6" id="jsonvalue15">
                                El paciente ha respondido a los siguientes ítems en el sentido indicado entre paréntesis. Estos ítems pertenecen a categorías específicas y sugieren problemas que el profesional puede analizar en mayor profundidad.
                            </p>
                            
                            <?php
                            $index_cat = 1;// index - max - 
                            foreach ($noteworthy_by_category as $category => $items) {
                            ?>
                                <div class="mb-6">
                                    <h3 class="font-bold text-sm text-black mb-2 pb-1" id="jsonvalue15_<?= $index_cat ?>_<?= count($noteworthy_by_category) ?>_<?= count($items)  ?>"><?= $category ?>(<?= count($items)  ?>/<?= count($noteworthy_categories[$category]) ?>)</h3>
                                    <ul class="text-sm text-black pl-4 space-y-1.5 list-none">
                                        <?php
                                        $index_sub_cat = 1;
                                        foreach ($items as $item) { ?>
                                            <li class="flex items-start gap-1">
                                                <span class="font-semibold w-[30px] flex-shrink-0 text-back" id="jsonvalue-sub-15_<?= $index_sub_cat ?>_<?= count($items) ?>"><?= $item['item_order'] ?>.</span>
                                                <span class="text-justify"><?= $item['question'] ?> <span class="font-semibold text-black" id="jsonvalue-sub-15-<?= $index_sub_cat ?>">(Verdadero)</span></span>
                                            </li>
                                        <?php $index_sub_cat++; } ?>
                                    </ul>
                                </div>
                            <?php
                            $index_cat++;
                            }
                            ?>
                            <h1 class="text-left font-bold text-lg text-black uppercase tracking-wide mb-3" id="jsonvalue16">CONSIDERACIONES DIAGNÓSTICAS DSM-5®</h1>
                            <p class="text-sm text-black leading-relaxed" id="jsonvalue17">
                                Las siguientes asignaciones diagnósticas deben considerarse juicios de prototipos clínicos y de personalidad que corresponden conceptualmente a categorías diagnósticas formales. Los criterios diagnósticos y los ítems utilizados en el MCMI-IV difieren en cierta medida de los del DSM-5, pero existen suficientes paralelismos en los ítems del MCMI-IV para recomendar la consideración de las siguientes asignaciones. Es importante señalar que varios síndromes clínicos del DSM-5 no se evalúan en el MCMI-IV. Los diagnósticos definitivos deben fundamentarse en datos biográficos, de observación y de entrevista, además de los inventarios de autoinforme como el MCMI-IV.

                            <p class="text-sm text-black leading-relaxed mb-6" id="jsonvalue18">
                                Antes del nombre de cada trastorno, se presentan los códigos del CIE-9-MC, seguidos de los códigos del CIE-10-MC entre paréntesis
                            </p>
                            <h1 class="text-left font-bold text-lg text-black tracking-wide mb-3" id="jsonvalue19">Síndromes Clínicos</h1>
                            <p class="text-sm text-black leading-relaxed mb-6" id="jsonvalue20">
                                La sintomatología reportada y las conductas de la persona evaluada, son consistentes con los siguientes síndromes clínicos, enumerados según su relevancia.
                            </p>
                            <p class="text-sm text-black leading-relaxed" id="jsonvalue21">
                                <?= $textoSincromeClinico ?>
                            </p>
                            <?php
                            if ($sincromeClinico_informe1) { ?>
                                <p class="text-sm text-black leading-relaxed text-center" id="jsonvalue22">
                                    <?= $sincromeClinico_informe1 ?>
                                </p>
                            <?php } ?>

                            <?php
                            if ($sincromeClinico_informe2) { ?>
                                <p class="text-sm text-black leading-relaxed text-center" id="jsonvalue23">
                                    <?= $sincromeClinico_informe2 ?>
                                </p>
                            <?php } ?>

                            <?php
                            if ($sincromeClinico_informe3) { ?>
                                <p class="text-sm text-black leading-relaxed text-center" id="jsonvalue24">
                                    <?= $sincromeClinico_informe3 ?>
                                </p>
                            <?php } ?>


                            <h1 class="text-left font-bold text-lg text-black uppercase tracking-wide mb-3" id="jsonvalue25">Trastornos de la personalidad</h1>
                            <p class="text-sm text-black leading-relaxed mb-6" id="jsonvalue26">
                                Los patrones duraderos y consistentes de funcionamiento psicológico desadaptativo explican la forma en que la persona percibe la realidad, se relaciona con los demás y regula sus emociones. Estos rasgos conforman la base estructural de su modo de ser y están en el origen de las manifestaciones clínicas observadas. En este caso, los siguientes prototipos de personalidad se relacionan con los diagnósticos más probables del DSM-5.
                            </p>
                            <?php
                            if ($transtorno_personalidad_informe_1) { ?>
                                <p class="text-sm text-black leading-relaxed text-center" id="jsonvalue27">
                                    <?= $transtorno_personalidad_informe_1 ?>
                                </p>
                            <?php } ?>

                            <?php
                            if ($transtorno_personalidad_informe_2) { ?>
                                <p class="text-sm text-black leading-relaxed text-center" id="jsonvalue28">
                                    <?= $transtorno_personalidad_informe_2 ?>
                                </p>
                            <?php } ?>

                            <?php
                            if ($transtorno_personalidad_informe_3) { ?>
                                <p class="text-sm text-black leading-relaxed text-center" id="jsonvalue29">
                                    <?= $transtorno_personalidad_informe_3 ?>
                                </p>
                            <?php } ?>

                            <?php
                            if ($transtorno_sintoma_informe_1) { ?>
                                <p class="text-sm text-black leading-relaxed text-center" id="jsonvalue30">
                                    <?= $transtorno_sintoma_informe_1 ?>
                                </p>
                            <?php } ?>

                            <?php
                            if ($transtorno_sintoma_informe_2) { ?>
                                <p class="text-sm text-black leading-relaxed text-center" id="jsonvalue31">
                                    <?= $transtorno_sintoma_informe_2 ?>
                                </p>
                            <?php } ?>


                            <h5 class="text-left font-bold text-lg text-black tracking-wide mb-3" id="jsonvalue32">
                                Fin del informe.
                            </h5>
                            <p class="text-sm text-black leading-relaxed mb-6" id="jsonvalue33">
                                Copyright @ 2020 DICANDRIEN, Inc. All rights reserved.
                            </p>

                        </div>


                    </div>
                </div>



            </div>
        </div>

        <?php include("/include/footer.php"); ?>
    </div>
    <?php include("/include/print_content.php"); ?>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        const name_user = "<?php echo $register['id_client'] ?>";
        const testname = "<?php echo $register['type_question_name'] ?>";

        const noteworthy_by_category = <?php echo json_encode($noteworthy_by_category); ?>;
        const sincromeClinico_informe1 = `<?php echo $sincromeClinico_informe1 ?>`;
        const sincromeClinico_informe2 = `<?php echo $sincromeClinico_informe2 ?>`;
        const sincromeClinico_informe3 = `<?php echo $sincromeClinico_informe3 ?>`;
        
        const transtorno_personalidad_informe_1 = `<?php echo $transtorno_personalidad_informe_1 ?>`;
        const transtorno_personalidad_informe_2 = `<?php echo $transtorno_personalidad_informe_2 ?>`;
        const transtorno_personalidad_informe_3 = `<?php echo $transtorno_personalidad_informe_3 ?>`;

        const transtorno_sintoma_informe_1 = `<?php echo $transtorno_sintoma_informe_1 ?>`;
        const transtorno_sintoma_informe_2 = `<?php echo $transtorno_sintoma_informe_2 ?>`;
        console.log(noteworthy_by_category);
        const filenamepdf = (name_user + "_" + testname).replace(/\s+/g, '');
        var jsonpdf = [];

        jsonpdf.push({type: 2,image: "contenidoID"});

        jsonpdf.push({type: 2,image: "contenido2"});
        
        jsonpdf.push({
            type: 2,
            image: "contenido3"
        });
        
        jsonpdf.push({
            type: 2,
            image: "contenido4"
        });
        jsonpdf.push({
            type: 2,
            image: "contenido5"
        });
        jsonpdf.push({
            type: 3
        });
        jsonpdf.push({
            type: 2,
            image: "contenido6"
        });

        jsonpdf.push({
            type: 2,
            image: "contenido7",
            size: 100
        });

        jsonpdf.push({
            type: 7,
            text: getvalue('jsonvalue1')
        });
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:5,text:getvalue('jsonvalue2')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:5,text:getvalue('jsonvalue3')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:5,text:getvalue('jsonvalue4')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:7,text:getvalue('jsonvalue5')} );
        jsonpdf.push({type: 5,text: ''});


        jsonpdf.push({type:5,text:getvalue('jsonvalue6')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:5,text:getvalue('jsonvalue7')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:7,text:getvalue('jsonvalue8')} );
        jsonpdf.push({type: 5,text: ''});

        jsonpdf.push({type:5,text:getvalue('jsonvalue9')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:5,text:getvalue('jsonvalue10')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:7,text:getvalue('jsonvalue11')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:5,text:getvalue('jsonvalue12')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:5,text:getvalue('jsonvalue13')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:7,text:getvalue('jsonvalue14')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:5,text:getvalue('jsonvalue15')} );
        jsonpdf.push({type: 5,text: ''});

        Object.entries(noteworthy_by_category).forEach(([categoria, items]) => {
            jsonpdf.push({type:10,text:categoria} );
           
            items.forEach(item => {
                console.log(item.question);
                const value = item.item_order+". "+item.question +"(Verdadero)";
                jsonpdf.push({type:5,text:value});
                
            });
            jsonpdf.push({type: 5,text: ''});
        });

        jsonpdf.push({type:7,text:getvalue('jsonvalue16')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:5,text:getvalue('jsonvalue17')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:5,text:getvalue('jsonvalue18')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:7,text:getvalue('jsonvalue19')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:5,text:getvalue('jsonvalue20')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:5,text:getvalue('jsonvalue21')} );
        if(sincromeClinico_informe1!=""){
            jsonpdf.push({type:11,text:sincromeClinico_informe1} );
            jsonpdf.push({type: 5,text: ''});
        }
        if(sincromeClinico_informe2!=""){
            jsonpdf.push({type:11,text:sincromeClinico_informe2} );
            jsonpdf.push({type: 5,text: ''});
        }
        if(sincromeClinico_informe3!=""){
            jsonpdf.push({type:11,text:sincromeClinico_informe3} );
            
        }
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:7,text:getvalue('jsonvalue25')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:5,text:getvalue('jsonvalue26')} );
        jsonpdf.push({type: 5,text: ''});
        if(transtorno_personalidad_informe_1!=""){
            jsonpdf.push({type:11,text:transtorno_personalidad_informe_1} );
            jsonpdf.push({type: 5,text: ''});
        }
        if(transtorno_personalidad_informe_2!=""){
            jsonpdf.push({type:11,text:transtorno_personalidad_informe_2} );
            jsonpdf.push({type: 5,text: ''});
        }
        if(transtorno_personalidad_informe_3!=""){
            jsonpdf.push({type:11,text:transtorno_personalidad_informe_3} );
            jsonpdf.push({type: 5,text: ''});
        }
       
        if(transtorno_sintoma_informe_1!=""){
            jsonpdf.push({type:11,text:transtorno_sintoma_informe_1} );
            jsonpdf.push({type: 5,text: ''});
        }
        if(transtorno_sintoma_informe_2!=""){
            jsonpdf.push({type:11,text:transtorno_sintoma_informe_2} );
        }
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:7,text:getvalue('jsonvalue32')} );
        jsonpdf.push({type: 5,text: ''});
        jsonpdf.push({type:5,text:getvalue('jsonvalue33')} );
        jsonpdf.push({type: 5,text: ''});
        function descargarDeforePDF() {

            document.querySelectorAll(".fix-margin").forEach(element => {
                element.style.setProperty("bottom", "5px", "important");
                element.style.setProperty("position", "relative", "important");
            });

            descargarPDF();
        }
    </script>
    <script src="../../assets/js/print.js?v=<?= VERSION_CODE ?>"></script>
</body>

</html>