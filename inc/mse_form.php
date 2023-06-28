<?php
$err = '';
if (isset($_GET["code"])) {
    switch ($_GET["code"]) {
        case 0:
            $err = 'Error: Filetype must be xlsx!';
            break;
        case 1:
            $err = 'Error: Data in form 1 is incorrect!';
            break;
        case 2:
            $err = 'Success!';
            break;
        case 3:
            $err = 'Filesize is too big!';
            break;
    }
}
?>

<main class="container">
    <div class="row">

        <h3 class="justify-content-center text-center">Import Data</h3>
        <form method="get">
            <label class="form-label" for="type">Choose import type</label>
            <div class="row">
                <div class="col-md-8">
                    <input type="hidden" name="form" id="form" value="1">
                    <select class="form-control" id="type" name="type">
                        <option value="">Choose...</option>
                        <?php
                        $options = array("quarter", "year");
                        foreach ($options as $option) {
                            echo "<option value='$option'";
                            if (isset($_GET["type"]) && $_GET["type"] == $option) {
                                echo ' selected="selected"';
                            }
                            echo ">By $option</option>";
                        }
                        ?>
                    </select>

                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-dark">Submit</button>
                </div>
            </div>
        </form>
        <form method="post" enctype="multipart/form-data" action="config/mse_import.php">
            <?php if (!empty($_GET["type"])) {
                echo '<input type="hidden" name="input-type" value="' . $_GET["type"] . '">';

                // Showing form for quarter export
                if ($_GET["type"] == "quarter"): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="year-input" class="form-label">Choose year:</label>
                            <input class="form-control" type="number" name="year-input" id="year-input" min="2000"
                                   max="<?php echo date("Y"); ?>"
                                   value="<?php echo htmlspecialchars($_POST['month-input'] ?? date("Y")); ?>"
                                   required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="quarter-input">Choose quarter:</label>
                            <select class="form-control" id="quarter-input" name="quarter-input" required>
                                <option value="">Choose...</option>
                                <?php
                                $options = array("1", "2", "3", "4");
                                foreach ($options as $option) {
                                    echo "<option value='$option'";
                                    if (isset($_POST["quarter-input"]) && $_POST["quarter-input"] == $option) {
                                        echo ' selected="selected"';
                                    }
                                    echo ">Q$option</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                <?php endif;

                // Showing form for year export
                if ($_GET["type"] == "year"): ?>
                    <label for="year-input" class="form-label">Choose year:</label>
                    <input class="form-control" type="number" name="year-input" id="year-input" min="2000"
                           max="<?php echo date("Y"); ?>"
                           value="<?php echo htmlspecialchars($_POST['year-input'] ?? date("Y")); ?>" required>

                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <label for="form1">Input for Form #1</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="form1" name="form1"
                                   value="<?php echo $_POST['form1'] ?? ''; ?>"
                                   accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                   required>
                            <label class="custom-file-label" for="form1"><?php echo $err ?></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="form1">Input for Form #2</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="form2" name="form2"
                                   accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                   required>
                        </div>
                    </div>

                    <div class="d-flex align-items-center" style="margin-top: 10px">
                        <button class="btn btn-dark mr-3" type="submit">Import</button>
                    </div>
                </div>

                <?php
            } else echo '<h3>Choose import type</h3>';
            ?>
        </form>


    </div>
</main>