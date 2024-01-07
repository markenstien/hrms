<?php 

    class AttachmentController extends Controller
    {
        public function __construct()
        {
            parent::__construct();
        }

        public function create() {
            if(isSubmitted()) {
                $post = request()->posts();

                if(!upload_empty('file')) {
                    $path = unseal($post['path']);
                    $url = unseal($post['g_url']);
                    

                    if(!file_exists($path)) {
                        mkdir($path);
                    }

                    $this->_attachmentModel->path = $path;
                    $this->_attachmentModel->url  = $url;

                    $upload = $this->_attachmentModel->upload([
                        'display_name' => $post['display_name'],
                        'global_key' => $post['g_key'],
                        'global_id'  => $post['user_id']
                    ], 'file');

                    if($upload) {
                        Flash::set("File Uploaded");
                    }
                    if(!empty($post['route'])) {
                        return redirect(unseal($post['route']));
                    }else{
                        return request()->return();
                    }
                }
            }
        }

        public function delete() {
            $req = request()->inputs();
            $this->_attachmentModel->deleteWithFile($req['id']);
            Flash::set('file removed');
            return request()->return();
        }

        public function edit($id) {
            if(isSubmitted()) {
                $post = request()->posts();
                $res = $this->_attachmentModel->update([
                    'display_name' => $post['display_name']
                ], $post['id']);

                if($res) {
                    Flash::set('File Updated');
                    return redirect(_route('user:show', $post['user_id']));
                }
            }
            $attachment = $this->_attachmentModel->get($id);
            $this->_attachmentForm->setValueObject($attachment);

            $this->data['_attachmentForm'] = $this->_attachmentForm;
            $this->data['id'] = $id;
            $this->data['userId'] = $attachment->global_id;
            
            return $this->view('attachment/edit', $this->data);
        }

        public function updateVisibility() {
            $req = request()->inputs();
            $id = unseal($req['id']);

            $attachment = $this->_attachmentModel->get($id);

            if(is_null($attachment->is_visible)) {
                $this->_attachmentModel->update([
                    'is_visible' => true
                ], $id);

                Flash::set('File Updated');

                return request()->return();
            }
        }
    }