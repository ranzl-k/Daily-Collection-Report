<?php

// Variable Declarations
$R_Rate = 80;
$I_Rate = 70;
$sc = 2;
$cess = 3;
$ET_R = 3.16;
$ET_I = 2.74;
$batta = 400;
$kfcc = 2;
$GST_R = 8.57;
$GST_I = 7.50;

// Calculate rates after taxes
$gross_tax_R = $sc + $cess + $ET_R;
$gross_tax_I = $sc + $cess + $ET_I;
$rate_R = $R_Rate - $gross_tax_R;
$rate_I = $I_Rate - $gross_tax_I;

// Get POST data
$date = htmlspecialchars($_POST['date']);
$ds_rate = (float)$_POST['ds_rate'];

// Initialize totals
$gross_yesterday = (float)$_POST['gross'];
$nett_yesterday = (float)$_POST['nett'];
$ds_yesterday = (float)$_POST['distShr'];
$es_yesterday = (float)$_POST['eShr'];

// Function to calculate gross and GST
function calculate_gross_and_gst($sold, $rate, $gst_rate)
{
    $gross = $sold * $rate;
    $gst_total = $gst_rate * $sold;
    $gst_rs = intval($gst_total);
    $gst_ps = bcdiv($gst_total - $gst_rs, 1, 2);
    return ['gross' => $gross, 'gst_rs' => $gst_rs, 'gst_ps' => $gst_ps];
}

// Store sold counts in an array
$sold_counts = [];
for ($i = 1; $i <= 8; $i++) {
    $sold_counts[$i] = (int)$_POST["sold_$i"];
}

// Gross and GST calculations for each sold count
$gross_and_gst = [];
$rates = [$rate_R, $rate_I, $rate_R, $rate_I, $rate_R, $rate_I, $rate_R, $rate_I];
$gst_rates = [$GST_R, $GST_I, $GST_R, $GST_I, $GST_R, $GST_I, $GST_R, $GST_I];

for ($i = 0; $i < 8; $i++) {
    $gross_and_gst[$i] = calculate_gross_and_gst($sold_counts[$i + 1], $rates[$i], $gst_rates[$i]);
}

// Calculate totals for today
$sold_R_total = $sold_counts[1] + $sold_counts[3] + $sold_counts[5] + $sold_counts[7];
$sold_I_total = $sold_counts[2] + $sold_counts[4] + $sold_counts[6] + $sold_counts[8];

$gst_total_R = $sold_R_total * $GST_R;
$gst_total_I = $sold_I_total * $GST_I;
$gst_total = $gst_total_R + $gst_total_I;
$gst_total_RS = intval($gst_total);
$gst_total_PS = bcdiv(($gst_total - $gst_total_RS), 1, 2);

$gross_total_R = $sold_R_total * $rate_R;
$gross_total_I = $sold_I_total * $rate_I;
$gross_today = round(($gross_total_R + $gross_total_I), 2);
$nett_today = round(($gross_today - ($gst_total + $batta + $kfcc)), 2);
$ds_today = round(($nett_today * $ds_rate), 2);
$es_today = round(($nett_today * (1 - $ds_rate)), 2);

// Cumulative totals
$gross = $gross_yesterday + $gross_today;
$nett = $nett_yesterday + $nett_today;
$distShr = $ds_yesterday + $ds_today;
$eShr = $es_yesterday + $es_today;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script>
        document.title = <?= json_encode($date, JSON_UNESCAPED_UNICODE); ?>;
    </script>
    <link rel="stylesheet" href="tablestyle.css">
</head>

<body>
    <h1 align="center" style="font-size: 300%;">
        DWARAKA THEATRE, KOYILANDY<br>
        DAILY COLLECTION REPORT<br>
        GSTIN : 32BBPPC8248F1ZO<br>
    </h1>
    <br>
    <div class="container space-between">
        <h2 style="font-size: 200%;">
            Picture: <?= htmlspecialchars($_POST['picture']); ?><br>
            Distributor: <?= htmlspecialchars($_POST['distributor']); ?>
        </h2>
        <h2 style="font-size: 200%;">
            Date: <?= htmlspecialchars($date); ?><br>
            Day: <?= htmlspecialchars($_POST['day']); ?>
        </h2>
    </div>

    <?php
    // Function to render each show table
    function render_show_table($show_name, $start_index, $sold_counts, $gross_and_gst, $rates, $gst_rates)
    {
        echo "<h1>$show_name</h1>";
        echo '<table class="tg"><thead><tr><th>Class</th><th>Seat</th><th>Rate</th><th>Adm. Fee</th><th>S.C</th><th>Cess</th><th>E.T</th><th colspan="2">Ticket</th><th>Sold</th><th>Gross</th><th colspan="3">GST<br>12%</th></tr></thead><tbody>';

        for ($i = $start_index; $i < $start_index + 2; $i++) {
            $class = $i % 2 == 0 ? 'R' : 'I';
            $seat_count = $i % 2 == 0 ? 200 : 646;
            $rate = $rates[$i - 1];
            $adm_fee = $i % 2 == 0 ? 66.43 : 57.5;
            $et = $i % 2 == 0 ? 3.16 : 2.74;
            $sold_plus_one = $sold_counts[$i] + 1;
            $gross = $gross_and_gst[$i - 1]['gross'];
            $gst_rs = $gross_and_gst[$i - 1]['gst_rs'];
            $gst_ps = $gross_and_gst[$i - 1]['gst_ps'];
            $gst_rate = $gst_rates[$i - 1];

            echo "<tr><td>$class</td><td>$seat_count</td><td>{$rate}</td><td>$adm_fee</td><td>2.00</td><td>3.00</td><td>$et</td><td>1</td><td>$sold_plus_one</td><td>{$sold_counts[$i]}</td><td>$gross</td><td>$gst_rate</td><td>$gst_rs</td><td>$gst_ps</td></tr>";
        }

        echo '</tbody></table>';
    }

    // Render all shows
    render_show_table('Morning Show 10:30 AM', 1, $sold_counts, $gross_and_gst, $rates, $gst_rates);
    render_show_table('Matinee 1:30 PM', 3, $sold_counts, $gross_and_gst, $rates, $gst_rates);
    render_show_table('First Show 04:30 PM', 5, $sold_counts, $gross_and_gst, $rates, $gst_rates);
    render_show_table('Second Show 07:30 PM', 7, $sold_counts, $gross_and_gst, $rates, $gst_rates);
    ?>

    <br><br>
    <div class="container space-around">
        <div>
            <table class="tg">
                <thead>
                    <tr>
                        <th>Batta</th>
                        <th>&emsp;KFCC&emsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>400.00</td>
                        <td>2.00</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div>
            <table class="tg">
                <thead>
                    <tr>
                        <th>&emsp;&emsp;&nbsp;&nbsp;Gross&emsp;&emsp;&nbsp;&nbsp;</th>
                        <th>&emsp;&nbsp;&nbsp;Nett&emsp;&nbsp;&nbsp;</th>
                        <th>&emsp;&emsp;&emsp;DS&emsp;&emsp;&nbsp;&nbsp;&nbsp;</th>
                        <th>&emsp;&emsp;&emsp;ES&emsp;&emsp;&nbsp;&nbsp;&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="tg-kxfu"><?= htmlspecialchars($_POST['gross']); ?></td>
                        <td class="tg-kxfu"><?= htmlspecialchars($_POST['nett']); ?></td>
                        <td class="tg-kxfu"><?= htmlspecialchars($_POST['distShr']); ?></td>
                        <td class="tg-kxfu"><?= htmlspecialchars($_POST['eShr']); ?></td>
                    </tr>
                    <tr>
                        <td class="tg-kxfu"><?= $gross_today; ?></td>
                        <td class="tg-kxfu"><?= $nett_today; ?></td>
                        <td class="tg-kxfu"><?= $ds_today; ?></td>
                        <td class="tg-kxfu"><?= $es_today; ?></td>
                    </tr>
                    <tr>
                        <td class="tg-kxfu"><?= $gross; ?></td>
                        <td class="tg-kxfu"><?= $nett; ?></td>
                        <td class="tg-kxfu"><?= $distShr; ?></td>
                        <td class="tg-kxfu"><?= $eShr; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>