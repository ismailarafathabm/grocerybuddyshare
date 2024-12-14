<?php 
    class DBConnect{
        private $cn;

        public function connectDB(){
            $this->cn = new PDO("mysql:host=localhost;dbname=grocerybuddy",'root','');
            return $this->cn;
        }
    }
?>