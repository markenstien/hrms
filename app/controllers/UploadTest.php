<?php   
    class UploadTest extends Controller
    {

        public function index()
        {
            return $this->view('dashboard/upload');
        }

        public function upload()
        {
            $uploadFiles = upload_bullet_multiple('images' , PATH_UPLOAD.DS.'test');

            $files = $uploadFiles['uploads'];

            $sql =  "INSERT INTO images VALUES";
            foreach($files as $key => $row )
            {
                $sql .= "($row)";
            }

            echo $sql;
            dump($uploadFiles);
        }
    }