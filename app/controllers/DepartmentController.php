<?php 	

	class DepartmentController extends Controller
	{

		public function __construct()
		{
			parent::__construct();
			$this->model = model('BranchModel');
			$this->data['pageMainTitle'] = 'Department';
		}
		public function create()
		{
			if(isSubmitted()) {
				$post = request()->posts();
				$response = $this->model->store([
					'branch' => $post['branch']
				]);

				if($response) {
					Flash::set("Department {$post} created");
				} else {
					Flash::set("Department {$post} created");
				}
					

				return redirect(_route('department:index'));
			}
			return $this->view('department/create' , $this->data);
		}
		public function edit($id)
		{
			if(isSubmitted()) {
				$post = request()->posts();
				$response = $this->model->update([
					'branch' => $post['branch']
				] , $post['id']);
				Flash::set("Department Updated");

				redirect(_route('department:index'));
			}
			$this->data['branch'] = $this->model->get($id);
			return $this->view('department/edit' , $this->data);
		}
		public function index()
		{
			$this->data['branches'] = $this->model->all(null, 'branch asc');
			return $this->view('department/index' , $this->data);
		}
	}