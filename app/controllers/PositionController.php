<?php

    use Form\PositionForm;
    load(['PositionForm'], FORMS);
    
    class PositionController extends Controller
    {
        public $model, $form;
        public function __construct()
        {
            parent::__construct();
            $this->model = model('PositionModel');
            $this->form  = new PositionForm();
            $this->data['pageMainTitle'] = 'Position Management';
            $this->data['form'] = $this->form;
        }

        public function index() {
            $this->data['positions'] = $this->model->all();
            return $this->view('position/index', $this->data);
        }

        public function create() {
            if(isSubmitted()) {
                $post = request()->posts();
                $res = $this->model->addNew($post);

                if($res) {
                    Flash::set($post['position_name'] . ' Has been created');
                    return redirect(_route('position:index'));
                }
            }
            return $this->view('position/create', $this->data);
        }

        public function show() {

        }

        public function edit($id) {
            $position = $this->model->get($id);
            if(isSubmitted()) {
                $post = request()->posts();
                $isOkay = $this->model->update(
                    $this->model->getFillablesOnly($post),
                    $id
                );

                if($isOkay) {
                    Flash::set("Position Updated");
                    return redirect(_route('position:index'));
                }
            }
            if(!$position) return;
            $this->data['position'] = $position;
            $this->form->setValueObject($position);

            $this->form->add([
                'type' => 'hidden',
                'name' => 'id',
                'value' => $id
            ]);

            $this->data['form'] = $this->form;
            
            return $this->view('position/edit', $this->data);
        }

        public function delete() {

        }
    }