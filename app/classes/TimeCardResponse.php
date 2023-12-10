<?php 

    class TimecardResponse
    {
        private $message,$user,
        $action,$punchDateTime,
        $punchTime,$punchDate;

        public function addMessage($message)
        {
            if( !isset($this->message) )
                $this->message = [];
            array_push($this->message , $message);

            return $this;
        }

        public function getMessage()
        {
            return $this->message;
        }

        public function getMessageStringFormat()
        {
            $retVal = '';
            if (isset($this->message))
            {
                foreach($this->message as $key => $row) {
                    if($key > 0)
                        $retVal .= ",";
                    $retVal .= $row;
                }
            }
            return $retVal;
        }

        public function setUser($user){
            $this->user = $user;
            return $this;
        }

        public function setAction($action)
        {
            $this->action = $action;
            return $this;
        }

        public function setPunchDateTime($punchDateTime) 
        {
            $this->punchDateTime = $punchDateTime;
            return $this;
        }

        public function setPunchTime($punchTime)
        {
            $this->punchTime = $punchTime;
            return $this;
        }

        public function setPunchDate($punchData)
        {
            $this->punchDate = $punchData;
            return $this;
        }

        public function getResponse()
        {
            return [
                'user'    => $this->user,
                'action'  => $this->action,
                'punchDateTime' => $this->punchDateTime,
                'punchTime'  => $this->punchTime,
                'punchDate'   => $this->punchDate,
                'message' => $this->getMessageStringFormat(),
            ];
        }
    }