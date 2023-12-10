<?php   

    class Security extends Controller
    {

        public function index()
        {
            allowedOrigin();
        }

    }