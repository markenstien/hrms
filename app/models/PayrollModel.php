<?php 
	use Services\CommonService;
	load(['CommonService'], APPROOT.DS.'services');

	class PayrollModel extends Model
	{

		public $table = 'payrolls';

		public function create($data) {
			$whois = whoIs();
			$createdBy = $whois['id'];

			$isValid = $this->_validateDate($data['start_date'], $data['end_date']);

			if(!$isValid)
				return false;

			$isOkay = parent::store([
				'start_date' => $data['start_date'],
				'end_date' => $data['end_date'],
				'approved_by' => $createdBy,
				'created_by' => $createdBy,
				'created_at' => now()
			]);

			parent::_addRetval('payrollId', $isOkay);
			return $isOkay;
		}

		public function updateDate($data, $id) {
			$isValid = $this->_validateDate($data['start_date'], $data['end_date']);
			$instance = parent::get($id);

			if(is_null($instance->release_date)) {
				return parent::store([
					'start_date' => $data['start_date'],
					'end_date'   => $data['end_date']
				], $id);
			} else {
				$this->addError("This payroll can no longer update, since the payslips are already distributed.");
				return false;
			}
			
		}

		private function _validateDate($startDate, $endDate) {

			if(strtotime($startDate) > strtotime($endDate)) {
				$this->addError("Start Date cannot be greater than End Date");
				return false;
			}

			//check if start date is already created

			$res = parent::single([
				'start_date' => [
					'condition' => '>=',
					'value'     => $startDate
				]
			]);


			if($res) {
				$this->addError("There is already a date created for this cuttoff '{$res->start_date} to {$res->end_date}' , change your date");
				return false;
			}

			return true;
		}

		public function release($id) {
			/*
			*Insert to Payroll Items Which is the wallet
			*/

			if(!isset($this->payrollItemModel)) {
				$this->payrollItemModel = model('PayrollItemModel');
			}

			if(!isset($this->timeSheetModel)) {
				$this->timeSheetModel = model('TimesheetModel');
			}

			$payroll = parent::get($id);

			$dataFilter = [
                'tklog.status' => 'approved',
                'tklog.is_deleted' => false,
                'date(tklog.time_in)' => [
                    'condition' => 'between',
                    'value' => [$payroll->start_date, $payroll->end_date]
                ]
            ];

			$timesheets = $this->timeSheetModel->getAll($dataFilter, "day(tklog.time_in) asc", null);
			$groupedByUsers =  $this->timeSheetModel->groupSheetsByUser($timesheets);

			$holidays = $this->getHolidays($payroll->start_date, $payroll->end_date);

			foreach($groupedByUsers as $key => $user) {
				$usersTimesheets = $user['timesheets'];
				$regTotalAmount = 0;
				$daysOfWork = 0;
				$regTotalWorkHours = 0;
				$uid = $user['uid'];
				$ratePerDay = $user['rate_per_day'];

				$takeHomePay = 0;

				if(!empty($usersTimesheets)) {

					/**
					 * add compensentation **/
					CommonService::_timeSheetComputation($usersTimesheets, $regTotalWorkHours, $daysOfWork, $regTotalAmount);
					$takeHomePay += $regTotalAmount;
					$paramsData = [
						'user_id' => $user['user_id'],
						'payroll_id' => $payroll->id,
						'reg_amount_total' => $regTotalAmount,
						'reg_hours_total' => $regTotalWorkHours,
						'ot_hours_total' => 0,
						'no_of_days' => $daysOfWork
					];

					if(!isset($this->deductionItemModel)) {
						$this->deductionItemModel = model('DeductionItemModel');
					}
						//get deductions
						$deductions = $this->deductionItemModel->getAll([
	             			'where' => [
	             				'di.user_id' => $user['user_id'],
	             				'di.balance' => [
	             					'condition' => '>',
	             					'value' => 0
	             				]
	             			]
	             		]);
						
	             		if($deductions) 
	             		{
	             			$payrollDeductions = [];
							$payrollDeductionAmount = 0;

	             			foreach($deductions as $deductKey => $deductVal) {
	             				$payrollDeductions[] = [
	             					'code' => $deductVal->deduction_code,
	             					'name' => $deductVal->deduction_name,
	             					'amount' => $deductVal->deduction_amount
	             				];
	             				$payrollDeductionAmount += $deductVal->deduction_amount;
	             			}

	             			$paramsData['deduction_notes'] = json_encode($payrollDeductions);
							$takeHomePay = $regTotalAmount - $payrollDeductionAmount;
	             		} else {
	             			$paramsData['deduction_notes'] = '';
	             		}


						if($holidays) {
							$payrollBonus = [];
							$payrolBonusAmount = 0;

							foreach($holidays as $hlKey => $hlRow) {
								$payrollBonus [] = [
									'code' => $hlRow->holiday_name_abbr,
									'name' => $hlRow->holiday_name,
									'amount' => $ratePerDay
								];

								$payrolBonusAmount += $ratePerDay;
							}

							$paramsData['bonus_notes'] = json_encode($payrollBonus);
							$takeHomePay += $payrolBonusAmount;
						}

					$paramsData['take_home_pay'] = $takeHomePay;
					$isOkay = $this->payrollItemModel->release($paramsData);

					//update deductions

					if($isOkay && $deductions) {

						if(!isset($this->deductionPaymentModel)) {
							$this->deductionPaymentModel = model('deductionPaymentModel');
						}
						foreach($deductions as $key => $row) 
						{
							//update deduction it self
							$this->deductionItemModel->update([
								'balance' => $row->balance - $row->deduction_amount
							], $row->id);
							//record payment
							$this->deductionPaymentModel->store([
								'user_id' => $user['user_id'],
								'payroll_id' => $payroll->id,
								'deduction_item_id' => $row->id,
								'amount' => $row->deduction_amount,
								'running_balance' => $row->balance,
								'amount_proceeding' => ($row->balance - $row->deduction_amount),
							]);
						}
					}
				}
			}

			return parent::update([
				'approved_by' => whois('id'),
				'release_date' => now()
			], $payroll->id);
		}


		public function delete($id) {
			$payroll = parent::get($id);

			if($payroll->release_date) {
				//payroll has money has been delivered.
				if(!isset($this->payrollItemModel)) {
					$this->payrollItemModel = model('PayrollItemModel');
				}
				$this->payrollItemModel->delete([
					'payroll_id' => $payroll->id
				]);
			}

			return parent::delete($id);
		}

		public function getHolidays($startDate, $endDate) {
			if(!isset($this->holidayModel)) {
				$this->holidayModel = model('HolidayModel');
			}

			return $this->holidayModel->getAll([
				'where' => [
					'holiday_date' => [
						'condition' => 'between',
						'value' => [$startDate, $endDate]
					]
				]
			]);
		}

	}