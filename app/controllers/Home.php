<?php   



    class Home extends Controller

    {

        

        public function index()

        {



        }





        public function exttesting()

        {

            $datas = [

                [

                    'name' => 'mark angelo',

                    'uid'  => '12345'

                ],

                [

                    'name' => 'Vincent',

                    'uid'  => '31234'

                ],

                [

                    'name' => 'Jackman',

                    'uid'  => '98123'

                ],

                [

                    'name' => 'Wolvarine',

                    'uid'  => '61423'

                ],

            ];

            ee(api_response($datas));

        }

    }