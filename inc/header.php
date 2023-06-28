<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Liakhovets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Quicksand', sans-serif;
        }

        .fixed-table {

            width: 100%;
            border-collapse: collapse;
            overflow: auto;
            height: 50vh;
        }

        table .fixed-column {
            position: sticky;
            left: 0;
            z-index: 1;
            background-color: #fff;

        }

        th, td {
            padding: 8px;
            word-wrap: break-word;
            text-align: left;
            border-bottom: 1px solid #ddd;
            z-index: 2;
        }

        th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
        }

        .slider {
            max-height: 59vh;
            overflow: auto;
            white-space: nowrap;
        }

        a.disabled {

            pointer-events: none;
            color: #ccc;
        }


    </style>
</head>
<body>

<header>
    <?php
    include 'config/database.php';
    session_start();
    if (isset($_SESSION['login'])): ?>
        <div class="text-center">
            <h1>Welcome,
                <div class="dropdown d-inline-block">
                    <button class="btn btn-primary dropdown-toggle"
                            style="font-size: 1.3rem; background: rgb(33, 37, 41);"
                            type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $_SESSION['name'] ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton"
                        style="min-width: 100%;">
                        <?php
                        switch ($_SESSION['role']) {
                            case 'HRI':
                            case 'HRO':
                                echo '<li><a class="dropdown-item" href="?form=1">Departments</a></li>';
                                break;

                            case 'MSEI':
                                echo '<li><a class="dropdown-item" href="?form=1">Import and export Data</a></li>';
                                break;

                            case 'Owner':
                                echo '<li><a class="dropdown-item" href="?form=1">Departments</a></li>
                                <li><a class="dropdown-item" href="?form=2">Financial indicators</a></li>';
                                break;
                        } ?>
                        <li><a class="dropdown-item" href="./config/logout.php">Logout</a></li>
                    </ul>
                </div>
            </h1>
        </div>
    <?php
    endif;
    ?>
</header>