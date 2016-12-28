<?php

$total = -1;
$tip = -1;
$bill_amount = "";
$bill_error = "";
$split_tip = "";
$split_total = "";
$split = 1;
$split_error = "";

if (isset($_POST["billamount"]) && isset($_POST["tippercentage"])) {

    $bill_check = "/^(([1-9]\d{0,2}(,\d{3})*)|(([1-9]\d*)?\d))(\.\d\d)?$/";
    $bill_amount = $_POST["billamount"];
    $tip_percentage = $_POST["tippercentage"];
    $split = $_POST["splitamongst"];


    if (($bill_amount == "") || ($tip_percentage == "") ||
        !preg_match($bill_check, $_POST["billamount"])
    ) {
        $bill_error = "Please enter a valid bill amount.";
    } else if ($split <= 0) {
        $split_error = "Please enter a valid split value.";
    } else {
        $bill_error = "";
        $split_error = "";
        $bill_amount = floatval($bill_amount);
        $tip_percentage = floatval($tip_percentage) / 100;

        $tip = $bill_amount * $tip_percentage;
        $total = $bill_amount + $tip;
        $split_tip = $tip / $split;
        $split_total = $total / $split;
        $total = format($total);
        $tip = format($tip);
        $split_tip = format($split_tip);
        $split_total = format($split_total);
    }

}

function format($value) {
    if (floor($value) == ceil($value)) {
        $value = $value . ".00";
    } else if (strlen(substr(strrchr($value, "."), 1)) == 1){
        $value = $value . "0";
    } else if (strlen(substr(strrchr($value, "."), 1)) > 2) {
        $value = number_format($value, 2, ".", "");
    }
    return $value;
}
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Tip Calculator</title>
        <link type="text/css" href="tip-calculator.css" rel="stylesheet"/>
    </head>

    <body>
    <div id="header">

    </div>

    <div id="main-content">
        <h1>Tip Calculator</h1>
        <form method="post" action="tip-calculator.php">
            <div id="billamount">
                <label>
                    <strong>Bill Subtotal: $</strong>
                    <input class="text" type="text" name="billamount" value="<?php echo $bill_amount ?>" size="15" autofocus>
                </label>
                <div class="error">
                    <?php if ($bill_error != "") { ?>
                        <p><?=$bill_error?></p>
                        <?php
                    } ?>
                </div>
            </div>
            <div id="percentage">
                <strong>Tip Percentage:</strong><br />
                <?php
                for ($i = 10; $i <= 20; $i+=5) { ?>
                    <label>
                        <input type="radio" name="tippercentage" value="<?= $i ?>"
                            <?php if (isset($_POST["tippercentage"]) && $_POST["tippercentage"] == $i ||
                                !isset($_POST["tippercentage"])
                            ) { ?> checked <?php } ?> /><?= $i ?>%
                    </label>
                <?php }
                ?>
            </div>
            <div id="split">
                <label>
                    Split amongst: <input class="text" type="text" value="<?=$split?>" name="splitamongst" size="15"/> person(s).
                </label>
                <div class="error">
                    <?php if ($split_error != "") { ?>
                        <p><?=$split_error?></p>
                        <?php
                    } ?>
                </div>
            </div>
            <div id="submit">
                <input type=submit value="Calculate Tip"/>
            </div>
        </form>
        <?php if ($total != -1) { ?>
        <div id="result">
            <p><span>Tip: $</span><?= $tip ?></p>
            <p><span>Total: $</span><?= $total ?></p>
            <?php if ($split > 1) { ?>
            <div id="splitresult">
                <p><span>Tip each: $</span><?= $split_tip ?></p>
                <p><span>Total each: $</span><?= $split_total ?></p>
            </div>
            <?php } ?>
        </div>
            <?php
        }
        ?>
    </div>

    <div id="footer">
    </div>
    </body>
</html>