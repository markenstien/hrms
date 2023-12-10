<?php 

    class TaskUpload extends Controller
    {
        public function __construct()
        {
            $this->taskPhoto = model('TaskUploadModel');
        }

        function upload()
        {
            $post = request()->inputs();

            $result = $this->taskPhoto->uploadMultiple($post['log_id'] , $post['fileNames'] , GET_PATH_UPLOAD );

            if($result) {
                ee(api_response("Files uploaded"));
            }else{
                ee(api_response("Something went wrong" , 'danger'));
            }
        }
    }