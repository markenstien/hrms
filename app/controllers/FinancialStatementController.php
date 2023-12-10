<?php 

    class FinancialStatementController extends Controller
    {

        public function index() {
            return $this->view('financial_statement/index');
        }
    }