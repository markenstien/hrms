<?php 
    namespace Services;
    require_once APPROOT.DS.'libraries/spreadsheet/vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

    class SpreadSheetExport {

        public $sheet;
        public $workSheet;
        public $title;

        public function __construct($title)
        {
            $this->sheet = new Spreadsheet();
            $this->title = $title;
        }

        public function setItems($items, $sheetTitle) {
            $this->workSheet = new Worksheet($this->sheet, $sheetTitle);
            foreach($items as $itemRow => $itemColumns) {
                foreach($itemColumns as $itemCol => $row) {
                    if(is_numeric($row)) {
                        $row = strval($row);
                    }
                    $this->workSheet->setCellValue($this->cellPosition($itemCol, $itemRow), $row);
                }
            }
            $this->sheet->addSheet($this->workSheet,0);
        }

        private function cellPosition($col , $row)
		{
			$alphabhet = range('A' , 'Z');
			return $alphabhet[$col].''.(++$row);
        }
        
        public function setActiveWorkSheet($sheetTitle) {
            $this->sheet->setActiveSheetIndexByName($sheetTitle);
        }

        public function export() {
            $filename = $this->title.'-'.time().'.xlsx';
			// Redirect output to a client's web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			 
			// If you're serving to IE over SSL, then the following may be needed
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
			header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header('Pragma: public'); // HTTP/1.

			$writer = IOFactory::createWriter($this->sheet, 'Xlsx');
            
			ob_get_clean();
			$writer->save('php://output');
        }
    }