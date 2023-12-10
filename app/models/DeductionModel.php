<?php 

	class DeductionModel extends Model
	{
		public $table = 'deductions';

		public function getCategories() {

			$this->db->query(
				"SELECT * FROM payroll_attribute_categories"
			);
			
			return $this->db->resultSet();
		}

		public function create($deductionData) {

			$single = parent::single([
				'deduction_code' => [
					'condition' => 'equal',
					'value' => $deductionData['deduction_code']
				],

				'deduction_name' => [
					'condition' => 'equal',
					'value' => $deductionData['deduction_name']
				],

				'category_id' => [
					'condition' => 'equal',
					'value' => $deductionData['category_id']
				],
			]);

			if(!$single) {
				$res = parent::store([
					'deduction_code' => $deductionData['deduction_code'],
					'deduction_name' => $deductionData['deduction_name'],
					'category_id' => $deductionData['category_id'],
					'status' => 'active',
				]);
				$this->addMessage("Deduction Created");
				return $res;
			} else {
				$this->addError("Unable to add new Deduction, Deduciton already exists.");
				return false;
			}
		}
	}