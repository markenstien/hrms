<?php 

    class ViewerController extends Controller
    {
        public function show() {
            $q = request()->inputs();
            if(!empty($q['file'])) {
                $data = [
                    'file' => unseal($q['file']),
                    'userId' => unseal($q['userId']),
                    'attachmentId' => $q['attachmentId'],
                    'navigationHelper' => $this->navigationHelper
                ];
                return $this->view('viewer/show', $data);
            }
        }
    }