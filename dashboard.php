<?php

include 'inc\header.php';

if (isset($_SESSION['login'])) {
    switch ($_SESSION['role']) {

        case 'admin':
            include 'inc/admin_create.php';
            break;

        case 'HRI':
            if (isset($_GET['form'])) {
                if ($_GET['form'] == 1) {
                    include('inc/hr_dep.php');
                } else if ($_GET['form'] == 2) {
                    include('inc/hr_create.php');
                }
            } else {
                include('inc/hr_dep.php');
            }
            break;

        case 'HRO':
            include('inc/hr_dep.php');
            break;

        case 'MSEI':
            include('inc/mse_form.php');
            break;

        case 'MSEO':
            include('inc/mse_table.php');
            break;

        case 'Owner':
            if (isset($_GET['form'])) {
                if ($_GET['form'] == 1) {
                    include('inc/hr_dep.php');
                } else if ($_GET['form'] == 2) {
                    include('inc/mse_table.php');
                }
            } else
                include('inc/hr_dep.php');
            break;

    }
} else header('Location: index.php');

include 'inc\footer.php';