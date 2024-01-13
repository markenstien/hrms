<?php
    use Services\ReportService;
    load(['ReportService'], SERVICES);

    class ReportController extends Controller
    {
        public $reportService;
        public $deductionModel, $deductionItemModel, 
        $userModel, $deductionPaymentModel;

        public function __construct()
        {
            parent::__construct();
            $this->deductionModel = model('DeductionModel');
            $this->deductionItemModel = model('DeductionItemModel');
            $this->userModel = model('UserModel');
            $this->deductionPaymentModel = model('DeductionPaymentModel');
            $this->reportService = new ReportService();
        }

        public function index() {
            $req = request()->inputs();
            $this->data['result'] = null;
            $this->data['req'] = $req;
            $this->data['employees'] = $this->userModel->getAll([
                'order' => 'user.firstname asc'
            ]);
            
            if(!empty($req['category'])) {
                switch($req['category']) {
                    case ReportService::CATEGORY_GOV_CONTRIBUTION:
                        $this->data['result'] = $this->reportService->groupByUser($this->getDeductionItems(ReportService::CATEGORY_GOV_CONTRIBUTION));
                        $this->data['categoryId'] = $req['category'];
                        $this->data['resultViewSource'] = 'gov_contribution';

                        if(!empty($req['uid'])) {

                            $user = $this->userModel->get([
                                'uid' => $req['uid']
                            ]);

                            if(!$user) {
                                Flash::set("Employee {$req['uid']} not foun");
                                return request()->return();
                            }
                            $pagibig = $this->deductionItemModel->get([
                                'user_id' => $user->id,
                                'deduction_id' => ReportService::DEDUCTION_PAGIBIG
                            ]);

                            $philhealth = $this->deductionItemModel->get([
                                'user_id' => $user->id,
                                'deduction_id' => ReportService::DEDUCTION_PHILHEALTH
                            ]);

                            $sss = $this->deductionItemModel->get([
                                'user_id' => $user->id,
                                'deduction_id' => ReportService::DEDUCTION_SSS
                            ]);

                            if($pagibig) {
                                $pagibigPayments = $this->deductionPaymentModel->getAll([
                                    'where' => [
                                        'deduction_item_id' => $pagibig->id,
                                        'dp.user_id' => $user->id
                                    ],

                                    'order' => 'dp.id desc'
                                ]);
                            }
                            

                            if($philhealth) {
                                $philhealthPayments = $this->deductionPaymentModel->getAll([
                                    'where' => [
                                        'deduction_item_id' => $philhealth->id,
                                        'user_id' => $user->id
                                    ],

                                    'order' => 'dp.id desc'
                                ]);
                            }

                            if($sss) {
                                $sssPayments = $this->deductionPaymentModel->getAll([
                                    'where' => [
                                        'deduction_item_id' => $sss->id,
                                        'user_id' => $user->id
                                    ],

                                    'order' => 'dp.id desc'
                                ]);
                            }

                            $this->data['contributionSummary'] = [
                                'payments' => [
                                    'pagibig' => $pagibigPayments ?? [],
                                    'philhealth' => $philhealthPayments ?? [],
                                    'sss' => $sssPayments ?? []
                                ],

                                'user' => $user
                            ];
                        }
                    break;

                    case ReportService::CATEGORY_COMPANY_LOAN:
                        //get deductions for the category
                        $deductions = $this->deductionModel->all([
                            'category_id' => $req['category']
                        ], 'id desc');
                        $this->data['categoryId'] = $req['category'];
                        $this->data['deductionLabels'] = arr_layout_keypair($deductions, ['id', 'deduction_name']);
                        $this->data['result'] = $this->reportService->groupByUser($this->getDeductionItems($req['category']));
                        $this->data['resultViewSource'] = 'company_loan';

                        if(!empty($req['uid'])) {

                            if(empty($req['deduction_id'])){
                                Flash::set("There is nothing to review on this record", 'warning');
                                return request()->return();
                            }
                            //get user
                            $user = $this->userModel->get([
                                'uid' => $req['uid']
                            ]);

                            if(!$user) {
                                Flash::set('User not found!');
                                return redirect(_route('report:index'));
                            }
                            
                            $deduction = $this->deductionItemModel->get([
                                'di.id' => $req['deduction_id']
                            ]);
                            
                            $payments = $this->deductionPaymentModel->getAll([
                                'where' => [
                                    'deduction_item_id' => $deduction->id
                                ]
                            ]);

                            $this->data['otherLoanSummary'] = [
                                'payments' => $payments,
                                'user' => $user,
                                'deduction' => $deduction
                            ];
                        }
                    break;
                }
            }
           
            $this->data['categories'] = $this->deductionModel->getCategories();
            return $this->view('report/index', $this->data);
        }

        /**
         * fetch deduction items of selected
         * category
         */
        private function getCategoryItemIds($categoryId) {
            $deductionIds = $this->deductionModel->all([
                'category_id' => $categoryId
            ]);

            $id = [];
            foreach($deductionIds as $key => $row) {
                $id [] = $row->id;
            }

            return $id;
        }

        private function getDeductionItems($categoryId, $userId = null) {
            $ids = $this->getCategoryItemIds($categoryId);
            
            $condition = [
                'deduction_id' => [
                    'condition' => 'in',
                    'value' => $ids
                ]
            ];

            if(!is_null($userId)) {
                $condition['user_id'] = $userId;
            }
            //fetch contrubtions
            $deductionItems = $this->deductionItemModel->getAll([
                'where' => $condition
            ]);

            return $deductionItems;
        }

        public function review() {
            $req = request()->inputs();
            /**
             * fetch contributions  
             */
            dd($req);
        }
    }