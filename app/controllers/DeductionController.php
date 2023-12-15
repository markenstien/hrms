<?php 

	class DeductionController extends Controller
	{
		public $deductionItemModel,
		$model;

		public function __construct() {
			parent::__construct();
			$this->model = model('DeductionModel');
			$this->deductionItemModel = model('DeductionItemModel');
		}

		public function index() {
			$this->deductions();
		}

		public function deductions() {
			$deductions = $this->deductionItemModel->getAll();
			$this->data['deductions'] = $deductions;
			
			return $this->view('deduction/deductions', $this->data);
		}

		public function create() {
			
			if(isSubmitted()) {
				$post = request()->posts();
				$isOkay = $this->model->create($post);

				if(!$isOkay) {
					Flash::set($this->model->getErrorString(), 'danger');
				} else {
					Flash::set($this->model->getMessageString());
				}

				return redirect('DeductionController/create');
			}

			$deductionCategories = $this->model->getCategories();
			$deductionCategoryArr = arr_layout_keypair($deductionCategories, ['id','category_name']);

			$this->data['deductions'] = $this->model->all();
			$this->data['deductionCategoryArr'] = $deductionCategoryArr;
			
			return $this->view('deduction/create', $this->data);
		}

		public function applyDeduction($deductionId) {
			
			if(isSubmitted()) {
				$post = request()->posts();
				$isOkay = $this->deductionItemModel->create($post);

				if(!$isOkay) {
					Flash::set($this->deductionItemModel->getErrorString(), 'danger');
					return request()->return();
				} else {
					Flash::set("Deduction Added to user");
					return redirect('DeductionController/deductions');
				}
			}

			$this->data['deduction'] = $this->model->get($deductionId);
			return $this->view('deduction/apply', $this->data);
		}

		public function deleteItem($id) {

			$this->deductionItemModel->delete($id);
			return redirect('DeductionController/deductions');
		}

		public function delete($id) {
			$this->model->delete($id);
			return redirect('DeductionController/create');
		}
	}