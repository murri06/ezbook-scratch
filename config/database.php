<?php
const DB_HOST = 'localhost';
const DB_USER = 'php_rus';
const DB_PASSWORD = 'Freedom06';
const DB_NAME = 'php_diploma';

//create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//check connection
if ($conn->connect_error) {
    die('Connection error' . $conn->connect_error);
}
