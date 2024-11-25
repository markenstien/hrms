<?php
    namespace Services;
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

    require_once APPROOT.DS.'libraries/spreadsheet/vendor/autoload.php';

    class SpreadSheetImport {

        public function import($pathToImport) {
            $reader = IOFactory::createReaderForFile($pathToImport);
            $reader->setReadDataOnly(true);
            
            $spreadsheet = $reader->load($pathToImport, 0);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, false, false, true);

            return $sheetData;
        }
    }