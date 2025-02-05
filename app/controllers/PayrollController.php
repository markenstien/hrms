<?php 
	use Services\CommonService;
	use Services\PayrollService;
	use Services\UserService;

	load(['CommonService', 'PayrollService', 'UserService'], APPROOT.DS.'services');

	class PayrollController extends Controller
	{

		public function __construct(){
			parent::__construct();
			$this->model = model('PayrollModel');
			$this->payrollItemModel = model('PayrollItemModel');
			$this->timesheet = model('TimesheetModel');
			$this->userModel = model('UserModel');
			$this->deductionItemModel = model('DeductionItemModel');

			$this->CommonService = new CommonService();
			$this->payrollService = new PayrollService();
		}

		public function index() {
			$this->data['payrollList'] = $this->model->all(null, 'id desc');
			return $this->view('payroll/index', $this->data);
		}

		public function create() {
			// if(!isEqual(whoIs('type'),[UserService::PAYROLL, UserService::SUPER_ADMIN])) {
			// 	Flash::set('Invalid Access');
			// 	return redirect(_route('dashboard:index'));
			// }
			
			$message = '';

			if(isSubmitted()) {
				$post = request()->posts();
				$holidays = $this->model->getHolidays($post['start_date'], $post['end_date']);
				
				$isOkay = $this->model->create($post);

				if(!$isOkay) {
					Flash::set($this->model->getErrorString(), 'danger');
					return request()->return();
				}

				if(!empty($holidays)) {
					$message = "Holidays has been found for this payroll, ";	
				}
				$message .= "Payroll Created";
				Flash::set($message);
				return redirect('PayrollController/show/'.$this->model->_getRetval('payrollId'));
			}

			$payrolls = $this->model->all(null, 'id desc');
			if(!empty($payrolls)) {

				$payrollDates = [
					date('Y-m-d', strtotime('+1 day '.$payrolls[0]->end_date)),
					date('Y-m-d', strtotime('+7 day '.$payrolls[0]->end_date)),
				];
			}

			return $this->view('payroll/create', $this->data);
		}

		public function edit() {

		}

		public function show($id) {
			$payroll = $this->model->get($id);

			$dataFilter = [
                'tklog.status' => 'approved',
                'tklog.is_deleted' => false,
                'date(tklog.time_in)' => [
                    'condition' => 'between',
                    'value' => [$payroll->start_date, $payroll->end_date]
                ]
            ];

            $groupedByBranch = [];
            $totalUsers = 0;
            $totalSalaryAmount = 0;

            $timesheets = $this->timesheet->getAll($dataFilter, "day(tklog.time_in) asc", null);

            $timesheetsGroupedByUser = $this->timesheet->groupSheetsByUser($timesheets);


            /**
             * group timesheetsdays by branch
             */
            foreach ($timesheetsGroupedByUser as $key => $row) {
                if (!isset($groupedByBranch[$row['department_id']])) {
                    $groupedByBranch[$row['department_id']] = [
                        'name' => $row['department_name'],
                        'users' => []
                    ];
                }

                if (!empty($row['timesheets'])) 
                {

                	$totalUsers++;

                	foreach($row['timesheets'] as $tSheet) {
                		$totalSalaryAmount += $tSheet->amount;
                	}
                	/**
             		*Append Deductions**/

             		$deductions = $this->deductionItemModel->getAll([
             			'where' => [
             				'di.user_id' => $row['user_id'],
             				'di.balance' => [
             					'condition' => '>',
             					'value' => 0
             				]
             			]
             		]);

             		$row['deductions'] = $deductions;

                	$groupedByBranch[$row['department_id']]['users'][] = $row;
                }
            }

			$data = [
				'payroll' => $payroll,
				'groupedByBranch' => $groupedByBranch,
				'totalSalaryAmount' => $totalSalaryAmount,
				'totalUsers' => $totalUsers,
				'CommonService' => $this->CommonService,
				'holidays' => $this->model->getHolidays($payroll->start_date, $payroll->end_date)
			];

			return $this->view('payroll/show', $data);
		}

		public function release($param) {
			$csrf = csrfGet();
			$param = unseal($param);
			csrfReload();

			$id = $param['id'];
			$isOkay = $this->model->release($id);

			if($isOkay) {
				Flash::set("Payroll sent.");
				return redirect('PayrollController/show_release/'.$id);
			}


			// if(!isEqual($param['token'], $csrf)) {
			// 	echo 'Invalid Token';
			// } else {
			// 	$id = $param['id'];
			// 	$isOkay = $this->model->release($id);

			// 	if($isOkay) {
			// 		Flash::set("Payroll sent.");
			// 		return redirect('PayrollController/show_release/'.$id);
			// 	}
			// }
		}

		public function show_release($id) {
			$req = request()->inputs();
			$payroll = $this->model->get($id);
			
			$payrollItems = $this->payrollItemModel->getAll([
				'where' => [
					'payroll_id' => $payroll->id
				]
			]);

			if(isset($req['export'])) {
				$this->export($payrollItems, $payroll->start_date, $payroll->end_date);
			}

			$totalSalaryAmount = 0;

			foreach($payrollItems as $key => $row) {
				$totalSalaryAmount += $row->reg_amount_total;
			}	
			$groupedByBranch = [];
			//group by branch

			foreach($payrollItems as $key => $row) {
				if(!isset($groupedByBranch[$row->department_id])) {
					$groupedByBranch[$row->department_id] = [
						'name' => $row->department_name,
						'users' => []
					];
				}
				$groupedByBranch[$row->department_id]['users'][] = $row;
			}

			$data = [
				'payroll' => $payroll,
				'payrollItems' => $payrollItems,
				'totalUsers' => count($payrollItems),
				'totalSalaryAmount' => $totalSalaryAmount,
				'groupedByBranch' => $groupedByBranch
			];
			return $this->view('payroll/show_release', $data);
		}

		public function delete($id) {
			$this->model->delete($id);
			Flash::set("Payroll Removed", 'danger');
			return redirect(_route('payroll:index'));
		}

		/**
		 * pass payroll_id and user_id
		 * *
		 */
		public function show_payslip($id) {
			$req = request()->inputs();
			$userId = unseal($req['user_id']);

			$payslip = $this->payrollItemModel->getAll([
				'where' => [
					'item.payroll_id' => $id,
					'item.user_id' => $userId
				]
			])[0] ?? false;


			$user = $this->userModel->get($payslip->user_id);

			$data = [
				'payslip' => $payslip,
				'user'    => $user
			];

			return $this->view('payroll/show_payslip', $data);
		}


		private function export($items, $startDate, $endDate) {
			if(!empty($items)) {
				$this->payrollService->setItems($items)
				->setPayrollPeriod($startDate, $endDate)
				->soryByDepartment();
				$sortedbyDepartments = $this->payrollService->getByDepartments();
				$this->payrollService->exportPerSheetByDepartment();
			}
		}
	}