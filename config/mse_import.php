<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

require_once '../vendor/autoload.php';
include_once 'database.php';

function input_data($cellX1, $cellX2, $cellX3, $cellY, $spreadsheet, $tableName, $date, $type): int|float
{
    global $conn;
    if ($type == 'year')
        $newDate = $date - 1;
    if ($type == 'quarter') {
        $newDate = $date - .1;
        if ($newDate == intval($newDate))
            $newDate -= .6;
    }
    $sql = "SELECT * FROM $tableName WHERE date LIKE '$date'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0)
        $id1 = $result->fetch_assoc()["id"];

    $sql = "SELECT * FROM $tableName WHERE date LIKE '$newDate'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0)
        $id2 = $result->fetch_assoc()["id"];


    $sql = "INSERT INTO $tableName(`";
    $keys = [];
    $arrStart = array();
    $arrEnd = array();
    $sql1 = "UPDATE $tableName SET ";
    $sql2 = "UPDATE $tableName SET ";
    while ($spreadsheet->getCell("A$cellY")->getDataType() === "s") {
        $tmp1 = $spreadsheet->getCell("$cellX1$cellY")->getValue();
        $tmp2 = $spreadsheet->getCell("$cellX2$cellY")->getValue();
        $tmp3 = $spreadsheet->getCell("$cellX3$cellY")->getValue();

        if (!empty($tmp1)) {
            $keys[] += $tmp1;
            if (empty($tmp2) || !is_numeric($tmp2))
                $tmp2 = null;

            if (empty($tmp3) || !is_numeric($tmp3))
                $tmp3 = null;

            $arrStart[] += $tmp2;
            $arrEnd[] += $tmp3;

            if (isset($id1))
                $sql1 .= "`$tmp1` = '$tmp2',";

            if (isset($id2))
                $sql2 .= "`$tmp1` = '$tmp3',";

        }
        $cellY++;
    }
    $sql .= implode('`, `', $keys) . "`,`date`) VALUES (";
    if (!isset($id1))
        $sql1 = $sql . "'" . implode("','", $arrStart) . "','$date')";
    else $sql1 = substr($sql1, 0, -1) . " WHERE `id` = '$id1'";


    if (!isset($id2))
        $sql2 = $sql . "'" . implode("','", $arrEnd) . "','$newDate')";
    else $sql2 = substr($sql2, 0, -1) . " WHERE `id` = '$id2'";


    if ($conn->query($sql1)) {
        echo 'Success!';
    } else echo 'Error...';
    if ($conn->query($sql2)) {
        echo 'Success!';
    } else echo 'Error...';

    return $newDate;
}

function output_data($type, $date, $tableName): void
{
    global $conn;
    $sum = array();
    $formatted = array();

    if ($type == 'year')
        $newDate = $date - 1;
    if ($type == 'quarter') {
        $newDate = $date - .1;
        if ($newDate == intval($newDate))
            $newDate -= .6;
    }

    $sql = "SELECT * FROM $tableName WHERE date LIKE '$date'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0)
        $id = $result->fetch_assoc()["id"];

    if ($type == 'quarter')
        $sql = "SELECT *
FROM mse_quarter_active mqa
JOIN mse_quarter_passive mqp ON ROUND(mqa.date, 1) = ROUND(mqp.date, 1)
JOIN mse_quarter_second mqs ON ROUND(mqp.date, 1) = ROUND(mqs.date, 1)
WHERE ROUND(mqa.date, 1) = $date OR ROUND(mqa.date, 1) = $newDate
ORDER BY mqa.date DESC
";
    elseif ($type == 'year')
        $sql = "SELECT * FROM mse_year_active JOIN mse_year_passive myp on mse_year_active.date = myp.date JOIN mse_year_second mys on myp.date = mys.date WHERE myp.date = '$date' OR myp.date = '$newDate' ORDER BY myp.date DESC ";

    $result = $conn->query($sql);
    for ($set = array(); $row = $result->fetch_assoc(); $set[] = $row) ;

    $curr = $set[0];
    if (isset($set[1]))
        $prev = $set[1];

    $result->free_result();

    // first block
    $sum['choz'] = $curr[1010] / $curr[1300];
    $sum['ksoz'] = $curr[1012] / $curr[1011];

    if (!empty($prev))
        $sum['kon'] = ($curr[1011] - $prev[1011]) / $prev[1011];
    else $sum['kon'] = 0;

    $sum['chdfi'] = $curr[1030] / $curr[1300];
    $sum['chovf'] = $curr[1100] / $curr[1095];
    $sum['chova'] = $curr[1100] / $curr[1300];
    $sum['kmob'] = $curr[1195] / $curr[1095];

    // second block
    $sum['a1'] = $curr[1160] + $curr[1165];
    $sum['a2'] = $curr[1125] + $curr[1130] + $curr[1155];
    $sum['a3'] = $curr[1100] + $curr[1040] + $curr[1170];
    $sum['a4'] = $curr[1095] - $curr[1040];
    $sum['p1'] = $curr[1610];
    $sum['p2'] = $curr[1695] - ($curr[1600] + $curr[1610]);
    $sum['p3'] = $curr[1595];
    $sum['p4'] = $curr[1495];
    $sum['kkd'] = $curr[1615] / $curr[1125];
    $sum['kla'] = $sum['a1'] / ($sum['p1'] + $sum['p2']);
    $sum['klsh'] = ($sum['a1'] + $sum['a2']) / ($sum['p1'] + $sum['p2']);
    $sum['klp'] = ($sum['a1'] + $sum['a2'] + $sum['a3']) / ($sum['p1'] + $sum['p2']);

    // third block
    $sum['vok'] = $curr[1495] - $curr[1095];
    $sum['vd'] = $sum['vok'] + $curr[1595];
    $sum['zd'] = $sum['vd'] + $curr[1600] + $curr[1610];
    $sum['dvok'] = $sum['vd'] - $curr[1100];
    $sum['dvd'] = $sum['vd'] - $curr[1100];
    $sum['dzd'] = $sum['zd'] - $curr[1100];
    $sum['kzvok'] = $sum['vok'] / $curr[1195];
    $sum['kzap'] = $sum['vok'] / $curr[1100];
    $sum['mrk'] = $sum['vok'] / $curr[1495];
    $sum['kavt'] = $curr[1495] / $curr[1300];
    $sum['kfz'] = $curr[1300] / $curr[1495];
    $sum['kfc'] = $curr[1495] / ($curr[1900] - $curr[1495]);
    $sum['kfv'] = ($curr[1900] - $curr[1495]) / $curr[1495];
    $sum['kpk'] = ($curr[1900] - $curr[1495]) / $curr[1900];
    $sum['kdz'] = $curr[1595] / ($curr[1900] - $curr[1495]);
    $sum['kpz'] = $curr[1695] / ($curr[1900] - $curr[1495]);
    $sum['kcb'] = $curr[1415] / $curr[1900];
    $sum['kcf'] = ($curr[1495] + $curr[1595]) / $curr[1900];

    // fourth block
    $sum['kt'] = $curr[2000] / $curr[1300];
    $sum['fof'] = $curr[2000] / $curr[1010];
    $sum['ko'] = $curr[2000] / $curr[1195];
    $sum['cho'] = 365 / $sum['ko'];
    $sum['koz'] = $curr[2050] / $curr[1100];
    $sum['chz'] = 365 / $sum['koz'];
    $sum['kodz'] = $curr[2000] / ($curr[1125] + $curr[1130] + $curr[1155]);
    $sum['chodz'] = 365 / $sum['kodz'];
    $sum['kkz'] = $curr[2050] / $curr[1610];
    $sum['chktz'] = ($curr[1610] * 365) / $curr[2050];
    $sum['chod'] = $sum['chodz'] + (365 / ($curr[2050] / $curr[1100]));
    $sum['chfc'] = $sum['chod'] - $sum['chktz'];
    $sum['kvk'] = $curr[2000] / $curr[1495];


    $keys = array_keys($sum);
    $sql = "INSERT INTO $tableName(`" . implode('`, `', $keys) . "`, `date`) VALUES(";
    foreach ($keys as $key) {
        $formatted["$key"] = number_format($sum["$key"], 3, '.', '');
    }
    $sql .= "'" . implode("', '", $formatted) . "', '$date')";

    if (isset($id)) {
        $sql = "UPDATE $tableName SET ";
        foreach ($keys as $key) {
            $formatted["$key"] = number_format($sum["$key"], 3, '.', '');
            $sql .= "`$key` = '" . $formatted["$key"] . "',";
        }
        $sql = substr($sql, 0, -1) . " WHERE id = '$id' ";
    }

    if ($conn->query($sql))
        echo 'Success!';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $type = $_POST["input-type"];
    if (isset($_FILES['form1']['error']) && $_FILES['form1']['error'] === UPLOAD_ERR_OK) {

        if ($_FILES['form1']['type'] !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $_FILES['form2']['type'] !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            header("Location: ../dashboard.php?form=1&type=$type&code=0");
            exit();
        }
        $maxFileSize = 1024 * 1024;
        if ($_FILES['form1']['size'] > $maxFileSize && $_FILES['form2']['size'] > $maxFileSize) {
            header("Location: ../dashboard.php?form=1&type=$type&code=3");
            exit();
        }
        // Get the uploaded file's temporary location
        $file1TmpPath = $_FILES['form1']['tmp_name'];
        $file2TmpPath = $_FILES['form2']['tmp_name'];

        $spreadSheetFile1 = IOFactory::load("$file1TmpPath")->getActiveSheet();
        $spreadSheetFile2 = IOFactory::load("$file2TmpPath")->getActiveSheet();
        if ($spreadSheetFile1->getCell("AY56")->getValue() !== $spreadSheetFile1->getCell("AY92")->getValue()
            || $spreadSheetFile1->getCell("BH56")->getValue() !== $spreadSheetFile1->getCell("BH92")->getValue()) {
            header("Location: ../dashboard.php?form=1&type=$type&code=1");
            exit();
        }

    }

    switch ($type) {
        case 'quarter':
            $year = $_POST["year-input"] + ($_POST["quarter-input"]) / 10;
            input_data("AU", "BH", "AY", 24, $spreadSheetFile1, "mse_quarter_active", $year, $type);
            input_data("AU", "BH", "AY", 61, $spreadSheetFile1, "mse_quarter_passive", $year, $type);
            input_data("BB", "BG", "BZ", 16, $spreadSheetFile2, "mse_quarter_second", $year, $type);
            input_data("BB", "BG", "BZ", 43, $spreadSheetFile2, "mse_quarter_second", $year, $type);
            input_data("BB", "BG", "BZ", 56, $spreadSheetFile2, "mse_quarter_second", $year, $type);
            $newDate = input_data("BB", "BG", "BZ", 66, $spreadSheetFile2, "mse_quarter_second", $year, $type);

            output_data($type, $year, "mse_quarter_summary");
            output_data($type, $newDate, "mse_quarter_summary");
            break;

        case 'year':
            $year = $_POST["year-input"];
            input_data("AU", "BH", "AY", 24, $spreadSheetFile1, "mse_year_active", $year, $type);
            input_data("AU", "BH", "AY", 61, $spreadSheetFile1, "mse_year_passive", $year, $type);
            input_data("BB", "BG", "BZ", 16, $spreadSheetFile2, "mse_year_second", $year, $type);
            input_data("BB", "BG", "BZ", 43, $spreadSheetFile2, "mse_year_second", $year, $type);
            input_data("BB", "BG", "BZ", 56, $spreadSheetFile2, "mse_year_second", $year, $type);
            $newDate = input_data("BB", "BG", "BZ", 66, $spreadSheetFile2, "mse_year_second", $year, $type);

            output_data($type, $year, "mse_year_summary");
            output_data($type, $newDate, "mse_year_summary");

            break;
    }

    header("Location: ../dashboard.php?form=1&type=$type&code=2");
}

$conn->close();