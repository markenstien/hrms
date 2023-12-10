<?php   



    class FileUpload

    {



        public function upload()
        {

            // (B) INVALID UPLOAD

            if (empty($_FILES) || $_FILES['file']['error']) {

                $this->verbose(0, "Failed to move uploaded file.");

            }



            // (C) UPLOAD DESTINATION

            // ! CHANGE FOLDER IF REQUIRED !

            

            // $filePath = __DIR__ . DIRECTORY_SEPARATOR . "uploads";



            $filePath = PATH_UPLOAD.DIRECTORY_SEPARATOR.'uploads';



            if (!file_exists($filePath)) { 
                if (!mkdir($filePath, 0777, true)) {
--
                    $this->verbose(0, "Failed to create $filePath");
                }
            }

            $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : $_FILES["file"]["name"];

            $filePath = $filePath . DIRECTORY_SEPARATOR . $fileName;



            // (D) DEAL WITH CHUNKS

            $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;

            $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

            $out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");

            

            if ($out) 

            {

                // $newName = uniqid('IMG-' , true).$_FILES['file']['tmp_name'];\



                $in = @fopen($_FILES['file']['tmp_name'], "rb");

                

                if ($in) {

                    while ($buff = fread($in, 4096)) { fwrite($out, $buff); }

                } else {

                    $this->verbose(0, "Failed to open input stream");

                }

                @fclose($in);

                @fclose($out);



                @unlink($_FILES['file']['tmp_name']);

            } else {

                $this->verbose(0, "Failed to open output stream");

            }



            // (E) CHECK IF FILE HAS BEEN UPLOADED

            if (!$chunks || $chunk == $chunks - 1) {

                rename("{$filePath}.part", $filePath);

            }

            $this->verbose(1, "Upload OK");

        }

        // (A) FUNCTION TO FORMULATE SERVER RESPONSE

        private function verbose($ok=1,$info=""){

            // THROW A 400 ERROR ON FAILURE

            if ($ok==0) { http_response_code(400); }

            die(json_encode(["ok"=>$ok, "info"=>$info]));

        }

    }