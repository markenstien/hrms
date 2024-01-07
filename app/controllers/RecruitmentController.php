<?php

    use Form\RecruitmentForm;
    use Form\UserForm;

    load(['RecruitmentForm', 'UserForm'], FORMS);

    class RecruitmentController extends Controller
    {
        private $recruitmentModel, $recruitmentInterviewModel;
        private $form;
        private $userForm;

        const SERIES_OF_INTERVIEW = [
            [
                'name' => 'Initial Interview',
                'number' => 1,
            ],
            [
                'name' => 'Technical Interview',
                'number' => 2,
            ],
            [
                'name'   => 'Final Interview',
                'number' => 3,
            ]
        ];
        public function __construct()
        {
            parent::__construct();
            $this->recruitmentModel = model('RecruitmentModel');
            $this->recruitmentInterviewModel = model('RecruitmentInterviewModel');
            $this->form = new RecruitmentForm();
            $this->data['form'] = $this->form;
            $this->data['seriesOfInterview'] = self::SERIES_OF_INTERVIEW;
            $this->userForm = new UserForm();
        }

        public function index() {
            $this->data['recruits'] = $this->recruitmentModel->getAll([
                'order' => 'recruit.id desc'
            ]);
            return $this->view('recruitment/index', $this->data);
        }

        public function create() {
            if(isSubmitted()) {
                $post = request()->posts();
                $post['sample'] = 'test';
                $response = $this->recruitmentModel->addNew($post);

                if(!$response) {
                    Flash::set($this->recruitmentModel->getErrorString(), 'danger');
                    //returns to form and save the input data
                    return request()->return();
                } else {
                    Flash::set("Candidate has been created");
                    return redirect(_route('recruitment:show', $this->recruitmentModel->_getRetval('candidateId')));
                }
            }
            return $this->view('recruitment/create', $this->data);
        }

        public function show($id) {
            $candidate = $this->recruitmentModel->get($id);
            $interviews = $this->recruitmentInterviewModel->all([
                'recruitment_id' => $id
            ]);

            $this->data['candidate'] = $candidate;
            $this->data['interviews'] = $interviews;

            return $this->view('recruitment/show', $this->data);
        }

        public function createEmployeeAccount($id) {
            $candidate = $this->recruitmentModel->get($id);
            $this->data['candidate'] = $candidate;

            $this->data['userForm'] = $this->userForm;
            return $this->view('recruitment/create_employee', $this->data);
        }
        public function edit() {

        }
    }