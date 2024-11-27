<?php

	namespace Form;

	load(['Form'], CORE);
	use Core\Form;

	class UserForm extends Form
	{

		public function __construct( $name = null)
		{
			parent::__construct();

			parent::init([
				'enctype' => 'multipart/form-data',
				'method'  => 'post'
			]);

			$this->name = $name ?? 'form_user';

			/*personal details*/
			$this->addFirstName();
			$this->addLastName();
			$this->addGender();
			$this->addBirthDate();
			/*end*/
			$this->addPhoneNumber();
			$this->addEmail();
			$this->addAddress();

			$this->addUsername();
			$this->addPassword();
			$this->addUserType();
			$this->addProfile();
			
			$this->addHireDate();
			$this->addPosition();
			$this->addDepartment();
			$this->addSchedule();
			$this->addSalaryComputationType();
			$this->addMonthlyRate();
			$this->addDailyRate();
			$this->addHourlyRate();
			$this->addGovSSS();
			$this->addGovPhilhealth();
			$this->addGovPagibig();
			$this->addAttendanceExternalID();
			
			$this->addSubmit('');
		}
		public function addFirstName()
		{
			$this->add([
				'type' => 'text',
				'name' => 'firstname',
				'class' => 'form-control',
				'required' => true,
				'options' => [
					'label' => 'First Name'
				],

				'attributes' => [
					'id' => 'firstname',
					'placeholder' => 'Enter First Name'
				]
			]);
		}


		public function addLastName()
		{
			$this->add([
				'type' => 'text',
				'name' => 'lastname',
				'class' => 'form-control',
				'required' => true,
				'options' => [
					'label' => 'Last Name'
				],

				'attributes' => [
					'id' => 'lastname',
					'placeholder' => 'Enter Last Name'
				]
			]);
		}

		public function addBirthDate()
		{
			$this->add([
				'type' => 'date',
				'name' => 'birthdate',
				'class' => 'form-control',
				'required' => true,
				'options' => [
					'label' => 'Birth Date'
				]
			]);
		}

		
		public function addGender()
		{
			$this->add([
				'type' => 'select',
				'name' => 'gender',
				'class' => 'form-control',
				'options' => [
					'label' => 'Gender',
					'option_values' => [
						'Male' , 'Female'
					]
				],

				'attributes' => [
					'id' => 'gender',
				]
			]);
		}

		public function addAddress()
		{
			$this->add([
				'type' => 'textarea',
				'name' => 'address',
				'class' => 'form-control',
				'options' => [
					'label' => 'Address',
				],

				'attributes' => [
					'id' => 'address',
					'rows' => 1
				]
			]);
		}

		public function addPhoneNumber()
		{
			$this->add([
				'type' => 'text',
				'name' => 'mobile_number',
				'class' => 'form-control',
				'options' => [
					'label' => 'Mobile Number',
				],

				'attributes' => [
					'id' => 'mobile_number',
					'placeholder' => 'Eg. 09xxxxxxxxx'
				]
			]);
		}

		public function addEmail()
		{
			$this->add([
				'type' => 'email',
				'name' => 'email',
				'class' => 'form-control',
				'options' => [
					'label' => 'Email',
				],

				'attributes' => [
					'id' => 'email',
					'placeholder' => 'Enter Valid Email',
					'maxlength' => 50
				]
			]);
		}

		public function addUsername()
		{
			$this->add([
				'type' => 'text',
				'name' => 'username',
				'class' => 'form-control',
				'required' => '',
				'options' => [
					'label' => 'Username',
				],

				'attributes' => [
					'id' => 'username',
					'placeholder' => 'Enter Username'
				]
			]);
		}

		public function addPassword()
		{
			$this->add([
				'type' => 'password',
				'name' => 'password',
				'class' => 'form-control',
				'required' => '',
				'options' => [
					'label' => 'Password',
				],

				'attributes' => [
					'id' => 'password',
					'maxlength' => 20
				]
			]);
		}

		public function addUserType()
		{
			$this->add([
				'type' => 'select',
				'name' => 'type',
				'class' => 'form-control',
				'required' => true,
				'options' => [
					'label' => 'User Access',
					'option_values' => [
						'SUPERADMIN' => 'SUPERADMIN',
						'REGULAR_EMPLOYEE' => 'REGULAR_EMPLOYEE'
					]
				],
				'attributes' => [
					'id' => 'userType'
				]
			]);
		}

		public function addProfile()
		{
			$this->add([
				'type' => 'file',
				'name' => 'profile',
				'class' => 'form-control',
				'options' => [
					'label' => 'Profile Picture',
				],

				'attributes' => [
					'id' => 'profile'
				]
			]);
		}

		public function addSubmit()
		{
			$this->add([
				'type' => 'submit',
				'name' => 'submit',
				'class' => 'btn btn-primary',
				'attributes' => [
					'id' => 'id_submit'
				],

				'value' => 'Save user'
			]);
		}

		/**
		 * EMPLOYEE DATA
		 */
		
		public function addHireDate() {
			$this->add([
				'type' => 'date',
				'name' => 'hire_date',
				'required' => true,
				'class' => 'form-control',
				'options' => [
					'label' => 'Hire Date'
				]
			]);
		}

		public function addPosition() {
			$positionModule = model('PositionModel');
			$positions = $positionModule->all(null, 'position_name asc');

			$positionArray = arr_layout_keypair($positions, ['id', 'position_name']);
			$this->add([
				'type' => 'select',
				'name' => 'position_id',
				'required' => true,
				'class' => 'form-control',
				'options' => [
					'label' => 'Position',
					'option_values' => $positionArray
				]
			]);
		}

		public function addDepartment() {
			$branchModel = model('BranchModel');
			$branches = $branchModel->all(null, 'branch asc');
			$branchesArray = arr_layout_keypair($branches,['id', 'branch']);

			$this->add([
				'type' => 'select',
				'name' => 'department_id',
				'required' => true,
				'class' => 'form-control',
				'options' => [
					'label' => 'Department',
					'option_values' => $branchesArray
				]
			]);
		}

		public function addSchedule() {
			$adminShiftModel = model('AdminShiftModel');
			$adminShifts = $adminShiftModel->getAll();
			$adminShiftArray = arr_layout_keypair($adminShifts, ['id', 'shift_name']);

			$this->add([
				'type' => 'select',
				'name' => 'shift_id',
				'required' => true,
				'class' => 'form-control',
				'options' => [
					'label' => 'Shift',
					'option_values' => $adminShiftArray
				]
			]);
		}

		public function addSalaryComputationType() {
			$this->add([
				'type' => 'select',
				'name' => 'computation_type',
				'required' => true,
				'class' => 'form-control',
				'options' => [
					'label' => 'Salary Computation',
					'option_values' => [
						'daily' => 'Daily',
						'per_hour' => 'Per Hour',
						'monthly'  => 'Monthly'
					] 
				]
			]);
		}

		public function addMonthlyRate() {
			$this->add([
				'type' => 'text',
				'name' => 'salary_per_month',
				'required' => true,
				'class' => 'form-control',
				'options' => [
					'label' => 'Monthly Rate'
				]
			]);
		}

		public function addDailyRate() {
			$this->add([
				'type' => 'text',
				'name' => 'salary_per_day',
				'required' => true,
				'class' => 'form-control',
				'options' => [
					'label' => 'Daily Rate'
				]
			]);
		}

		public function addHourlyRate() {
			$this->add([
				'type' => 'text',
				'name' => 'salary_per_hour',
				'required' => true,
				'class' => 'form-control',
				'options' => [
					'label' => 'Hourly Rate'
				]
			]);
		}
		
		public function addGovSSS() {
			$this->add([
				'type' => 'text',
				'name' => 'sss_number',
				'required' => true,
				'class' => 'form-control',
				'options' => [
					'label' => 'SSS #'
				]
			]);
		}

		public function addGovPhilhealth() {
			$this->add([
				'type' => 'text',
				'name' => 'phil_health_number',
				'required' => true,
				'class' => 'form-control',
				'options' => [
					'label' => 'Philhealth #'
				]
			]);
		}

		public function addGovPagibig() {
			$this->add([
				'type' => 'text',
				'name' => 'pagibig_number',
				'required' => true,
				'class' => 'form-control',
				'options' => [
					'label' => 'Pagibig #'
				]
			]);
		}

		public function addAttendanceExternalID() {
			$this->add([
				'type' => 'text',
				'name' => 'external_attendance_id',
				'required' => false,
				'class' => 'form-control',
				'options' => [
					'label' => 'External Attendance ID',
				],
				'attributes' => [
					'placeholder' => 'Third party identification'
				]
			]);
		}
	}