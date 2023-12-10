<?php 

    class Monitoring extends Controller
    {
        
        public function __construct()
        {
            $this->timelog_meta_model = model('TimelogMetaModel');
        }

        public function getLoggedUsers()
        {
            $loggedUsers = $this->timelog_meta_model->getClockedInUsers();
            ee(api_response($loggedUsers));
        }

        public function getConstruction() {
            $loggedUsers = $this->timelog_meta_model->getClockedInUsers([
                'where' => [
                    'branch_id' => 3
                ]
            ]);
            ee(api_response($loggedUsers));
        }
    }