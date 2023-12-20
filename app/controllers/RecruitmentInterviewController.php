<?php
    use Form\RecruitmentInterviewForm;
    load(['RecruitmentInterviewForm'], FORMS);

    class RecruitmentInterviewController extends Controller
    {
        private $model;
        private $form;

        public function __construct()
        {
            parent::__construct();
            $this->model = model('RecruitmentInterviewModel');
            $this->form = new RecruitmentInterviewForm();
            $this->data['form'] = $this->form;
        }

        public function index() {
            
        }

        public function create($candidateId) {
            $req = request()->inputs();

            if(isSubmitted()) {
                $post = request()->posts();
                $response = $this->model->addNew($post);

                if(!$response) {
                    Flash::set('failed to save interview');
                    return request()->return();
                }else{
                    Flash::set('Interview saved');
                    return redirect(_route('recruitment:show', $candidateId));
                }
            }

            $this->form->setValue('recruitment_id', $candidateId);
            $this->form->setValue('interview_title', $req['title']);
            $this->form->setValue('interview_number', $req['number']);

            $this->data['form'] = $this->form;
            $this->data['req'] = $req;
            $this->data['candidateId'] = $candidateId;

            return $this->view('recruitment_interview/create', $this->data);
        }

        public function edit() {

        }

        public function show($id) {
            $interviewResult = $this->model->get($id);
            $candidateId = $interviewResult->recruitment_id;

            $this->form->setValueObject($interviewResult);
            $this->data['interviewResult'] = $interviewResult;
            $this->data['candidateId'] = $candidateId;
            $this->data['form'] = $this->form;
            return $this->view('recruitment_interview/show', $this->data); 
        }
    }