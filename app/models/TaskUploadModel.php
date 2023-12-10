<?php   

    class TaskUploadModel extends Model
    {
        public $table = 'task_uploads';

        public function uploadMultiple($logId , $filesNames , $path)
        {
            $sql = "";

            $path = str_escape($path);

            if(empty($filesNames))
                return false;
            
            foreach($filesNames as $key => $fileName)
            {
                $sql .=" INSERT INTO $this->table(log_id , file_path , file_name)
                    VALUES('$logId' , '$path' , '$fileName');";
            }

            $this->db->query($sql);

            return $this->db->execute();
        }

        public function upload($logId , $fileName , $path)
        {

            $path = str_escape($path);
            
            $this->db->query(" INSERT INTO $this->table(log_id , file_path , file_name)
            VALUES('$logId' , '$path' , '$fileName');");

            return $this->db->execute();
        }
    }