<?php 

    class Flash{



        const NAME = 'flash';

        

        public static function set($message = '' , $type ='success' , $key = self::NAME){

            //set flash keyname

            if(Session::check($key)){

                Session::remove($key);

            }

            //set flash classname

            if(Session::check($key.'_class')){

                Session::remove($key.'_class');

            }

    

            Session::set($key ,$message);

            Session::set($key.'_class',$type);

            

        }

        public static function get($name = self::NAME)
        {
            $message = Session::get($name);
            Session::remove($name); Session::remove($name.'_class');
            return $message;
        }

        public static function show($name = self::NAME)
        {
            if(Session::check($name) && Session::check($name.'_class')){
            $className = Session::get($name.'_class');
            $message = Session::get($name);

            Session::remove($name); Session::remove($name.'_class');

            print <<< EOF
            <div class="alert alert-{$className} alert-dismissible fade show" role="alert">
                {$message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            EOF;
        }
}





    }