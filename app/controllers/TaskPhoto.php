<?php   

    class TaskPhoto extends Controller
    {
        public function __construct()
        {
            $this->taskPhoto = model('TaskUploadModel');
        }

        public function store()
        {
            $logId = $_POST['log_id'];
            
            $uploadMultiple = upload_bullet_multiple('images' , PATH_UPLOAD);
            
            if(!$uploadMultiple['status'])
            {
                Flash::set( implode(',' , $uploadMultiple['errors']) , 'danger');
                return request()->return();
            }
            
            $uploadedNames = $uploadMultiple['uploads'];

            $this->taskPhoto->uploadMultiple($logId , $uploadedNames , GET_PATH_UPLOAD );

            Flash::set("Photo Uploaded");

            return redirect('dashboard');
        }
    }