<?php 

	class DeductionPaymentModel extends Model
	{
		public $table = 'deduction_payments';

		public function getAll($params = []) {
			$where = null;
			$order = null;
			$limit = null;

			if(!empty($params['where'])) {
				$where  = " WHERE ".parent::convertWhere($params['where']);
			}

			if(!empty($params['order'])) {
				$order  = " ORDER BY {$params['order']} ";
			}

			if(!empty($params['limit'])) {
				$limit  = " LIMIT {$params['limit']} ";
			}

			$this->db->query(
				"SELECT dp.*, pp.release_date FROM {$this->table} as dp
					LEFT JOIN payrolls as pp
						ON pp.id = dp.payroll_id
				{$where} {$order} {$limit}"
			);

			return $this->db->resultSet();
		}
	}