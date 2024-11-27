<?php 

	class PayrollItemModel extends Model
	{
		public $table = 'payroll_items';
	
		public function release($params = []) {
			$payrollIncome = $this->getTotalFromPayroll($params['user_id'], $params['payroll_id']);
			if($payrollIncome) {
				$this->addError("Already Received salary");
				return false;
			}

			$isSaved = parent::store([
				'user_id' => $params['user_id'],
				'payroll_id' => $params['payroll_id'],
				'reg_amount_total' => $params['reg_amount_total'],
				'reg_hours_total' => $params['reg_hours_total'],
				'ot_hours_total' => $params['ot_hours_total'],
				'no_of_days' => $params['no_of_days'],
				'deduction_notes' => $params['deduction_notes'],
				'bonus_notes' => $params['bonus_notes'],
				'take_home_pay ' => $params['take_home_pay'],
			]);

			if($isSaved) {
				$this->addMessage("Payment Recieved");
				return true;
			} else {
				$this->addError("Unable to post payment");
				return false;
			}
		}

		public function getTotal($userId) {
			$this->db->query(
				"SELECT SUM(reg_amount_total) as total_amount
					FROM {$this->table}
					WHERE user_id = '{$userId}' "
			);
			return $this->db->single()->total_amount ?? 0;
		}

		public function getTotalFromPayroll($userId, $payrollId) {
			$this->db->query(
				"SELECT SUM(reg_amount_total) as total_amount
					FROM {$this->table}
					WHERE user_id = '{$userId}' 
					AND payroll_id = {$payrollId}"
			);
			return $this->db->single()->total_amount ?? 0;
		}

		public function getAll($params = []) {
			$where = null;
			$order = null;

			if(!empty($params['where'])) {
				 $where = " WHERE ".parent::convertWhere($params['where']);
			}

			if(!empty($params['order'])) {
				$order = " ORDER BY {$params['order']}";
			}
			$this->db->query(
				"SELECT item.*,
					concat(user.firstname , ' ', user.lastname) as fullname ,
					user.uid, user.firstname, user.lastname,
					eed.department_id as department_id, department.branch as department_name,
					payroll.start_date, payroll.end_date,position.position_name as position_name
					
					FROM {$this->table} as item

					LEFT JOIN payrolls as payroll
					on payroll.id = item.payroll_id

					LEFT JOIN users as user 
					ON user.id = item.user_id

					LEFT JOIN employee_datas as eed
					ON user.id = eed.user_id

					LEFT JOIN branches as department 
					on department.id = eed.department_id

					LEFT JOIN positions as position 
					on position.id = eed.position_id
					{$where} {$order}"
			);


			return $this->db->resultSet();
		}

		public function get($id) {
			return $this->getAll([
				'where' => [
					'item.id' => $id
				]
			])[0] ?? false;
		}

		public function getAsPayslip($params = []) {
			$where = null;
			$order = null;

			if(isset($params['where'])) {
				 $where = " WHERE ".parent::convertWhere($params['where']);
			}

			if(isset($params['order'])) {
				$order = " ORDER BY {$params['order']}";
			}

			$this->db->query(
				"SELECT item.payroll_id as payroll_id,
					item.user_Id as user_id, SUM(item.reg_amount_total) as reg_amount_total,
					concat(user.firstname , ' ', user.lastname) as fullname ,
					user.uid, user.firstname, user.lastname,
					branch_id, branch.branch as branch_name,
					payroll.start_date, payroll.end_date
					
					FROM {$this->table} as item
					LEFT JOIN payrolls as payroll
					on payroll.id = item.payroll_id
					LEFT JOIN users as user 
					ON user.id = item.user_id

					LEFT JOIN branches as branch 
					on branch.id = user.branch_id
					{$where}

					GROUP BY item.user_id,item.payroll_id
					{$order}
				"
			);

			return $this->db->resultSet();
		}
	}