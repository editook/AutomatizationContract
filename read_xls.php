<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Reader\Xls;
try {
    \ = new Xls();
    \ = \->load('example/entrada/MSPI20260429BOLMIG.xls');
    \ = \->getActiveSheet();
    print_r(\->toArray());
} catch (Exception \) {
    echo 'Error: ' . \->getMessage();
}
?>
