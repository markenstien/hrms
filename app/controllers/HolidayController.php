<?php

    use Form\HolidayForm;
    load(['HolidayForm'], FORMS);
    
    class HolidayController extends Controller
    {
        public $form;
        public $model;

        public function __construct()
        {
            parent::__construct();
            $this->model = model('HolidayModel');
            $this->form = new HolidayForm();
        }

        public function index() {
            $this->data['holidays'] = $this->model->getAll();
            $this->data['form'] = $this->form;
            return $this->view('holiday/index', $this->data);
        }

        public function create() {
            if(isSubmitted()) {
                $post = request()->posts();
                $response = $this->model->addNew($post);

                if(!$response) {
                    Flash::set($this->model->getErroString(), 'danger');
                    return request()->return();
                } else {
                    Flash::set("Holiday {$post['holiday_name']} has been created.");
                }
                
                return redirect(_route('holiday:index'));
            }

            $this->data['form'] = $this->form;
            return $this->view('holiday/create', $this->data);
        }

        public function edit($id) {
            if(isSubmitted()) {
                $post = request()->posts();
                $response = $this->model->updateComplete($post, $post['id']);

                if(!$response) {
                    Flash::set($this->model->getErrorString(), 'danger');
                    return request()->return();
                } else {
                    Flash::set("Holiday has been updated.");
                    return redirect(_route('holiday:index'));
                }
            }

            $holiday = $this->model->get($id);
            $this->form->setValueObject($holiday);
            $this->form->addId($id);

            $this->data['form'] = $this->form;
            return $this->view('holiday/edit', $this->data);
        }

        public function show() {

        }
    }