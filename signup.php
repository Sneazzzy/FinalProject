<?php 
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'final_project';

$signup = new Signup($host, $user, $password, $dbname);
$signup->handleRequest();


class Signup{
    
}


?>