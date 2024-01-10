<?php 

	class DeductionItemModel extends Model
	{
		public $table = 'deduction_items';

		public function create($deductionData) {
			if(!isset($this->user_model)) {
				$this->user_model = model('UserModel');
			}
			//search user 
			$user = $this->user_model->single([
				'uid' => $deductionData['uid']
			]);
			
			if(!$user) {
				$this->addError("User {$deductionData['uid']} not found.");
				return false;
			}

			$insertData = [
				'user_id' => $user->id,
				'deduction_id' => $deductionData['deduction_id'],
				'stop_if_zero' => $deductionData['stop_if_zero'] ?? false,
				'initial_balance' => $deductionData['initial_balance'],
				'deduction_type' => $deductionData['deduction_type'],
				'balance' => $deductionData['initial_balance'],
				'deduction_cycle' => $deductionData['deduction_cycle'],
				'description' => $deductionData['description']
			];

			if(isEqual($deductionData['deduction_type'], 'percentage')) {
				// $insertData['deduction_percentage']
			} else {
				$insertData['deduction_amount'] = $deductionData['payment_amount'];
			}

			return parent::store($insertData);
		}

		public function getTotalContribution($deductionIds, $startDate, $endDate, $userId = null) {
			$this->db->query(
				"SELECT * FROM users"
			);

			$users = $this->db->resultSet();

			$this->db->query(
				"SELECT sum(initial_balance) "
			);
		}

		public function get($id) {
			if(is_array($id)) {
				$condition = $id;
			} else {
				$condition = "id = '{$id}'";
			}

			return $this->getAll([
				'where' => $condition
			])[0] ?? false;
		}

		public function getAll($params = []) {

			$where = null;

			if(isset($params['where'])) {
				$where = ' WHERE '.parent::convertWhere($params['where']);
			}

			$this->db->query(
				"SELECT di.*,
				user.uid as uid,
				concat(firstname, ' ', lastname) as fullname,
					deduct.deduction_name,
					deduct.deduction_code,
					deduct.category_id as deduction_category
					FROM {$this->table} as di

					LEFT JOIN users as user 
					ON user.id = di.user_id

					LEFT JOIN deductions as deduct
					ON deduct.id = di.deduction_id
					{$where}"
			);

			return $this->db->resultSet();
		}
	}