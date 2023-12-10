<?php
    use Form\ShiftForm;
    load(['ShiftForm'], FORMS);

    class AdminShiftController extends Controller 
    {
        public $form,$model;
        public function __construct()
        {
            parent::__construct();
            $this->model = model('AdminShiftModel');
            $this->form = new ShiftForm();

            $this->data['form'] = $this->form;
        }

        public function index() {
            $shifts = $this->model->getAll();
            $this->data['shifts'] = $shifts;
            return $this->view('admin_shift/index', $this->data);
        }

        public function create() {
            if(isSubmitted()) {
                $post = request()->posts();
                $res = $this->model->addNewShifts($post, $post['day']);

                if($res) {
                    Flash::set($this->model->getMessageString());
                    return redirect(_route('admin-shift:index'));
                } else {
                    Flash::set($this->model->getErrorString(), 'danger');
                    return request()->return();
                }
            }
            $this->data['daysoftheweek'] = dayOfWeeks();
            return $this->view('admin_shift/create', $this->data);
        }
    
        public function edit($id) {
            return $this->view('admin_shift/edit', $this->data);
        }
    
        public function show() {
            return $this->view('admin_shift/edit', $this->data);
        }
    }