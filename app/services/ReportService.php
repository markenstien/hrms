<?php
    namespace Services;
    
    class ReportService {
        const CATEGORY_GOV_CONTRIBUTION = '1';
        const CATEGORY_COMPANY_LOAN = '2';


        const DEDUCTION_PAGIBIG = '2';
        const DEDUCTION_PHILHEALTH = '1';
        const DEDUCTION_SSS = '4';

        public function groupByUser($contributions = []) {
            $retVal = [];
            foreach($contributions as $key => $row) {
                if(!isset($retVal[$row->user_id])) {
                    $retVal[$row->user_id] = [];
                }
                array_push($retVal[$row->user_id], $row);
            }

            return $retVal;
        }
    }