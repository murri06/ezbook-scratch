<?php
session_start();

session_destroy();
header('Location:/test11/index.php');