<?php

if (isset($_GET['type'])) {
    if ($_GET['type'] == 'quarter') {
        $sql = "SELECT * FROM mse_quarter_active JOIN mse_quarter_passive mqp on mse_quarter_active.date = mqp.date JOIN mse_quarter_second mqs on mqp.date = mqs.date ORDER BY mqp.date DESC";
        $result = $conn->query($sql);
        $sql = "SELECT * FROM mse_quarter_summary ORDER BY date DESC";
        $output = $conn->query($sql);
    } elseif ($_GET['type'] == 'year') {
        $sql = "SELECT * FROM mse_year_active JOIN mse_year_passive myp on mse_year_active.date = myp.date JOIN mse_year_second mys on myp.date = mys.date ORDER BY myp.date DESC";
        $result = $conn->query($sql);
        $sql = "SELECT * FROM mse_year_summary ORDER BY date DESC";
        $output = $conn->query($sql);
    }
    $sql = "SELECT * FROM mse_statements WHERE id <= 214";
    $statements = $conn->query($sql);
}

if (isset($_GET["group-select"])) {
    $sql = match ($_GET["group-select"]) {
        'group_1' => "SELECT * FROM mse_statements WHERE id BETWEEN 215 AND 271",
        'group_2' => "SELECT * FROM mse_statements WHERE id BETWEEN 272 AND 283",
        'group_3' => "SELECT * FROM mse_statements WHERE id BETWEEN 284 AND 301",
        'group_4' => "SELECT * FROM mse_statements WHERE id > 301 ",
        default => "SELECT * FROM mse_statements WHERE id > 214",
    };

} else
    $sql = "SELECT * FROM mse_statements WHERE id > 214";

$statementsOutput = $conn->query($sql);
?>

    <div class="row w-100">
        <div class="col-md-2 h-75">
            <div style="padding-left:1vw;">
                <a style="width:100%; margin-bottom: 5px;" class="btn <?php if (isset($_GET["table"])) {
                    if ($_GET["table"] == 1) echo 'btn-secondary disabled';
                    else echo 'btn-dark';
                } else echo 'btn-dark' ?>"
                   href="?form=2&table=1<?php if (isset($_GET["type"])) echo '&type=' . $_GET["type"] ?>">Input
                    Data</a>
                <a style="width:100%; margin-bottom: 1vh;" class="btn <?php if (isset($_GET["table"])) {
                    if ($_GET["table"] == 2) echo 'btn-secondary disabled';
                    else echo 'btn-dark';
                } else echo 'btn-dark' ?>"
                   href="?form=2&table=2<?php if (isset($_GET["type"])) echo '&type=' . $_GET["type"] ?>">Output
                    Data</a>

                <label for="">Filter by...</label><br>


                <div>
                    <a class="btn
                        <?php if (isset($_GET["type"])) {
                        if ($_GET["type"] == 'quarter') echo 'btn-secondary disabled';
                        else echo 'btn-dark';
                    } else echo 'btn-dark' ?>"
                       href="?form=2&type=quarter<?php if (isset($_GET["table"])) echo '&table=' . $_GET["table"] ?>">Filter
                        by quarter</a>


                    <a class="btn
                        <?php if (isset($_GET["type"])) {
                        if ($_GET["type"] == 'year') echo 'btn-secondary disabled';
                        else echo 'btn-dark';
                    } else echo 'btn-dark' ?>"
                       href="?form=2&type=year<?php if (isset($_GET["table"])) echo '&table=' . $_GET["table"] ?>">Filter
                        by year</a>
                </div>

            </div>

        </div>

        <div class="col-md-10 justify-content-center"
             style="padding-left: 50px; padding-right: 50px;padding-bottom: 9vh">

            <?php
            if (isset($_GET["table"])):
                if ($_GET["table"] == 1):
                    ?>
                    <div class="row">
                        <div class="col-md-11">
                            <h2>Input Data</h2>
                        </div>
                        <div class="col-md-1 d-flex justify-content-end">
                            <a class="btn btn-dark" style="max-height: 30px"
                               href="?form=2&table=1<?php if (isset($_GET["type"])) echo '&type=' . $_GET["type"] ?>"><i class="bi bi-arrow-repeat"></i>
                            </a>
                        </div>
                    </div>
                    <div class="slider" style="height: 800px">
                        <table class="table  fixed-table table-striped">

                            <?php
                            // Виведення рядків таблиці
                            if (isset($result)) {
                                if ($result->num_rows > 0) {
                                    echo "<thead class='table-dark'><tr>
                          <th class='col-3'>Description</th>
                          <th class='col-2' style='position:sticky;left:0; z-index:3;'>Code name</th>";
                                    $res = array();

                                    for ($set = array(); $row = $result->fetch_assoc(); $set[] = $row) ;


                                    foreach ($set as $row) {
                                        $res1[] = $row['date'];
                                        echo "<th>" . $row['date'] . "</th>";
                                    }

                                    echo "</tr></thead><tbody>";


                                    while ($state = $statements->fetch_assoc()) {
                                        $firstChar = substr($state['Statement'], 0, 1);
                                        $code = $state["code"];

                                        echo "<tr";
                                        if ($firstChar == 'I')
                                            echo "class='table-info'";
                                        echo "><td>" . $state["Statement"] . "</td><td class='fixed-column'>" . $code . "</td>";


                                        foreach ($set as $row) {
                                            echo "<td>" . $row[$code] . "</td>";
                                        }
                                        echo "</tr>";
                                    }

                                    echo "</tbody>";
                                } else
                                    echo "<h3 class='text-secondary'>No data for display</h3>";
                            } else
                                echo "<h3 class='text-secondary'>No data for display</h3>";
                            ?>


                        </table>
                    </div>


                <?php
                elseif ($_GET["table"] == '2'): ?>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <h2>Output Data</h2>
                        </div>
                        <div class="col-md-6 col-sm-12" style=" display: flex; justify-content: flex-end;">
                            <form METHOD="get" action="">
                                <input type="hidden" name="form" value="2">
                                <input type="hidden" name="type"
                                       value="<?php if (isset($_GET["type"])) echo $_GET["type"] ?>">
                                <input type="hidden" name="table"
                                       value="<?php if (isset($_GET["type"])) echo $_GET["table"] ?>">

                                <div class="row" style=" display: flex; justify-content: flex-end;">
                                    <div class="col-md-9 col-9">
                                        <select class="form-control" name="group-select" id="group-select"
                                                style="width: auto; max-width: 50vw;">
                                            <option value="">All</option>
                                            <option value="group_1" <?php if (isset($_GET['group-select']) && $_GET['group-select'] == 'group_1') {
                                                echo ' selected="selected"';
                                            } ?>>Оцінка майна організації та джерел його
                                                утворення
                                            </option>
                                            <option value="group_2" <?php if (isset($_GET['group-select']) && $_GET['group-select'] == 'group_2') {
                                                echo ' selected="selected"';
                                            } ?> >Оцінка ліквідності та платоспроможності організації
                                            </option>
                                            <option value="group_3" <?php if (isset($_GET['group-select']) && $_GET['group-select'] == 'group_3') {
                                                echo ' selected="selected"';
                                            } ?>>Оцінка фінансової стійкості організації
                                            </option>
                                            <option value="group_4" <?php if (isset($_GET['group-select']) && $_GET['group-select'] == 'group_4') {
                                                echo ' selected="selected"';
                                            } ?>>Оцінка ділової активності організації
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-3" style=" display: flex; justify-content: flex-end;">
                                        <button type="submit" class="btn btn-dark">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="slider" style="height: 800px">
                        <table class="table table-fixed fixed-table">

                            <?php
                            // Виведення рядків таблиці
                            if (isset($output)) {
                                if ($output->num_rows > 0) {
                                    echo "<thead class='table-dark'><tr><th class='col-4'>Description</th>";
                                    $res = array();

                                    for ($set = array(); $row = $output->fetch_assoc(); $set[] = $row) ;

                                    foreach ($set as $row) {
                                        $res1[] = $row['date'];
                                        echo "<th >" . $row['date'] . "</th>";
                                    }

                                    echo "</tr></thead><tbody>";

                                    while ($state = $statementsOutput->fetch_assoc()) {
                                        $code = $state["code"];
                                        echo "<tr><td>" . $state["Statement"] . "</td>";
                                        foreach ($set as $row) {
                                            echo "<td>" . $row[$code] . "</td>";

                                        }
                                        echo "</tr>";
                                    }

                                    echo "</tbody>";
                                } else
                                    echo "<h3 class='text-secondary'>No data for display</h3>";
                            } else
                                echo "<h3 class='text-secondary'>No data for display</h3>";
                            ?>
                        </table>
                    </div>
                <?php
                endif;
            else:
                echo '<h3 class="text-secondary">Please choose type of table!</h3>';
            endif;
            ?>
        </div>
    </div>

<?php
if (isset($result))
    $result->free_result();
if (isset($statements))
    $statements->free_result();
if (isset($output))
    $output->free_result();

?>