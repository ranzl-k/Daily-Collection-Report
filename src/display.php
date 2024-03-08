<?php
$username = "root"; // Replace with your MySQL username
$password = "root"; // Replace with your MySQL password
$dbname = "dcr";
$servername = "mysql_db";  // Use the hostname set in the docker-compose.yml
$port = 3306;  // MySQL port number

// Create a connection to the MySQL server without specifying a database
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

$gross_tax_R = $sc + $cess + $ET_R;
$gross_tax_I = $sc + $cess + $ET_I;
$rate_R = $R_Rate - $gross_tax_R;
$rate_I = $I_Rate - $gross_tax_I;

$date = htmlspecialchars($_POST['date']);

$sold_1 = htmlspecialchars($_POST['sold_1']);
$sold_2 = htmlspecialchars($_POST['sold_2']);
$sold_3 = htmlspecialchars($_POST['sold_3']);
$sold_4 = htmlspecialchars($_POST['sold_4']);
$sold_5 = htmlspecialchars($_POST['sold_5']);
$sold_6 = htmlspecialchars($_POST['sold_6']);
$sold_7 = htmlspecialchars($_POST['sold_7']);
$sold_8 = htmlspecialchars($_POST['sold_8']);

$gross_yesterday = htmlspecialchars($_POST['gross']);
$nett_yesterday = htmlspecialchars($_POST['nett']);
$ds_yesterday = htmlspecialchars($_POST['distShr']);
$es_yesterday = htmlspecialchars($_POST['eShr']);

$ds_rate = htmlspecialchars($_POST['ds_rate']);

$gross_1 = $sold_1 * $rate_R;
$gross_2 = $sold_2 * $rate_I;
$gross_3 = $sold_3 * $rate_R;
$gross_4 = $sold_4 * $rate_I;
$gross_5 = $sold_5 * $rate_R;
$gross_6 = $sold_6 * $rate_I;
$gross_7 = $sold_7 * $rate_R;
$gross_8 = $sold_8 * $rate_I;

$GST_1_RS = $GST_R * $sold_1;
$GST_1_PS = bcdiv($GST_1_RS - intval($GST_1_RS),1,2);
$GST_2_RS = $GST_I * $sold_2;
$GST_2_PS = $GST_2_RS - intval($GST_2_RS);
$GST_3_RS = $GST_R * $sold_3;
$GST_3_PS = $GST_3_RS - intval($GST_3_RS);
$GST_4_RS = $GST_I * $sold_4;
$GST_4_PS = $GST_4_RS - intval($GST_4_RS);
$GST_5_RS = $GST_R * $sold_5;
$GST_5_PS = $GST_5_RS - intval($GST_5_RS);
$GST_6_RS = $GST_I * $sold_6;
$GST_6_PS = $GST_6_RS - intval($GST_6_RS);
$GST_7_RS = $GST_R * $sold_7;
$GST_7_PS = $GST_7_RS - intval($GST_7_RS);
$GST_8_RS = $GST_I * $sold_8;
$GST_8_PS = $GST_8_RS - intval($GST_8_RS);

$sold_R_total = $sold_1 + $sold_3 + $sold_5 + $sold_7;
$sold_I_total = $sold_2 + $sold_4 + $sold_6 + $sold_8;

$gst_total_R = $sold_R_total * $GST_R;
$gst_total_I = $sold_I_total * $GST_I;
$gst_total =  $gst_total_R + $gst_total_I;
$gst_total_RS = intval($gst_total);
$gst_total_PS = $gst_total - $gst_total_RS;

$gross_total_R = $sold_R_total * $rate_R;
$gross_total_I = $sold_I_total * $rate_I;
$gross_today = $gross_total_R + $gross_total_I;
$nett_today = $gross_today - ($gst_total_RS + $batta + $kfcc);
$ds_today = $nett_today * $ds_rate;
$es_today = $nett_today * (1 - $ds_rate);

$gross = $gross_yesterday + $gross_today;
$nett = $nett_yesterday + $nett_today;
$distShr = $ds_yesterday + $ds_today;
$eShr = $es_yesterday + $es_today;

$query = "INSERT INTO collection (date, gross, nett, distShr, eShr) VALUES ('$date', '$gross','$nett', '$distShr', '$eShr')";
$conn->query($query);

// Close the database connection
$conn->close();
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
            Picture :
            <?php
            echo htmlspecialchars($_POST['picture']);
            ?>
            <br>
            Distributor :
            <?php
            echo htmlspecialchars($_POST['distributor']);
            ?>
        </h2>

        <h2 style="font-size: 200%;">
            Date :
            <?php
            echo htmlspecialchars($_POST['date']);
            ?>
            <br>
            Day :
            <?php
            echo htmlspecialchars($_POST['day']);
            ?>
        </h2>
    </div>

    <h1>Morning Show 10:30 AM</h1>
    <table class="tg">
        <thead>
            <tr>
                <th class="tg-0lax">Class</th>
                <th class="tg-0lax">Seat</th>
                <th class="tg-0lax">Rate</th>
                <th class="tg-0lax">Adm. Fee</th>
                <th class="tg-0lax">S.C</th>
                <th class="tg-0lax">Cess</th>
                <th class="tg-0lax">E.T</th>
                <th class="tg-0lax" colspan="2">Ticket</th>
                <th class="tg-0lax">Sold</th>
                <th class="tg-0lax">Gross</th>
                <th class="tg-0lax" colspan="3">GST<br>12%</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="tg-0lax">R</td>
                <td class="tg-0lax">200</td>
                <td class="tg-0lax">80</td>
                <td class="tg-0lax">66.43</td>
                <td class="tg-0lax">2.00</td>
                <td class="tg-0lax">3.00</td>
                <td class="tg-0lax">
                    <?php
                    echo $ET_R;
                    ?>
                </td>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax">
                    <?php
                    echo htmlspecialchars($_POST['sold_1']) + 1;
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo htmlspecialchars($_POST['sold_1']);
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo $gross_1;
                    ?>
                </td>
                <td class="tg-0lax">8.57</td>
                <td class="tg-0lax">
                    <?php
                    echo intval($GST_1_RS);
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo $GST_1_PS;
                    ?>
                </td>
            </tr>
            <tr>
                <td class="tg-0lax">I</td>
                <td class="tg-0lax">646</td>
                <td class="tg-0lax">70</td>
                <td class="tg-0lax">57.5</td>
                <td class="tg-0lax">2.00</td>
                <td class="tg-0lax">3.00</td>
                <td class="tg-0lax">
                    <?php
                    echo $ET_I;
                    ?>
                </td>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax">
                    <?php
                    echo htmlspecialchars($_POST['sold_2']) + 1;
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo htmlspecialchars($_POST['sold_2']);
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo $gross_2;
                    ?>
                </td>
                <td class="tg-0lax">7.50</td>
                <td class="tg-0lax">
                    <?php
                    echo intval($GST_2_RS);
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo $GST_2_PS;
                    ?>
                </td>
            </tr>
        </tbody>
    </table>


    <h1>Matinee 1:30 PM</h1>
    <table class="tg">
        <thead>
            <tr>
                <th class="tg-0lax">Class</th>
                <th class="tg-0lax">Seat</th>
                <th class="tg-0lax">Rate</th>
                <th class="tg-0lax">Adm. Fee</th>
                <th class="tg-0lax">S.C</th>
                <th class="tg-0lax">Cess</th>
                <th class="tg-0lax">E.T</th>
                <th class="tg-0lax" colspan="2">Ticket</th>
                <th class="tg-0lax">Sold</th>
                <th class="tg-0lax">Gross</th>
                <th class="tg-0lax" colspan="3">GST<br>12%</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="tg-0lax">R</td>
                <td class="tg-0lax">200</td>
                <td class="tg-0lax">80</td>
                <td class="tg-0lax">66.43</td>
                <td class="tg-0lax">2.00</td>
                <td class="tg-0lax">3.00</td>
                <td class="tg-0lax">
                    <?php
                    echo $ET_R;
                    ?>
                </td>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax">
                    <?php
                    echo htmlspecialchars($_POST['sold_3']) + 1;
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo htmlspecialchars($_POST['sold_3']);
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo $gross_3;
                    ?>
                </td>
                <td class="tg-0lax">8.57</td>
                <td class="tg-0lax">
                    <?php
                    echo intval($GST_3_RS);
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo $GST_3_PS;
                    ?>
                </td>
            </tr>
            <tr>
                <td class="tg-0lax">I</td>
                <td class="tg-0lax">646</td>
                <td class="tg-0lax">70</td>
                <td class="tg-0lax">57.5</td>
                <td class="tg-0lax">2.00</td>
                <td class="tg-0lax">3.00</td>
                <td class="tg-0lax">
                    <?php
                    echo $ET_I;
                    ?>
                </td>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax">
                    <?php
                    echo htmlspecialchars($_POST['sold_4']) + 1;
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo htmlspecialchars($_POST['sold_4']);
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo $gross_4;
                    ?>
                </td>
                <td class="tg-0lax">7.50</td>
                <td class="tg-0lax">
                    <?php
                    echo intval($GST_4_RS);
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo $GST_4_PS;
                    ?>
                </td>
            </tr>
        </tbody>
    </table>


    <h1>First Show 04:30 PM</h1>
    <table class="tg">
        <thead>
            <tr>
                <th class="tg-0lax">Class</th>
                <th class="tg-0lax">Seat</th>
                <th class="tg-0lax">Rate</th>
                <th class="tg-0lax">Adm. Fee</th>
                <th class="tg-0lax">S.C</th>
                <th class="tg-0lax">Cess</th>
                <th class="tg-0lax">E.T</th>
                <th class="tg-0lax" colspan="2">Ticket</th>
                <th class="tg-0lax">Sold</th>
                <th class="tg-0lax">Gross</th>
                <th class="tg-0lax" colspan="3">GST<br>12%</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="tg-0lax">R</td>
                <td class="tg-0lax">200</td>
                <td class="tg-0lax">80</td>
                <td class="tg-0lax">66.43</td>
                <td class="tg-0lax">2.00</td>
                <td class="tg-0lax">3.00</td>
                <td class="tg-0lax">
                    <?php
                    echo $ET_R;
                    ?>
                </td>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax">
                    <?php
                    echo htmlspecialchars($_POST['sold_5']) + 1;
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo htmlspecialchars($_POST['sold_5']);
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo $gross_5;
                    ?>
                </td>
                <td class="tg-0lax">8.57</td>
                <td class="tg-0lax">
                    <?php
                    echo intval($GST_5_RS);
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo $GST_5_PS;
                    ?>
                </td>
            </tr>
            <tr>
                <td class="tg-0lax">I</td>
                <td class="tg-0lax">646</td>
                <td class="tg-0lax">70</td>
                <td class="tg-0lax">57.5</td>
                <td class="tg-0lax">2.00</td>
                <td class="tg-0lax">3.00</td>
                <td class="tg-0lax">
                    <?php
                    echo $ET_I;
                    ?>
                </td>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax">
                    <?php
                    echo htmlspecialchars($_POST['sold_6']) + 1;
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo htmlspecialchars($_POST['sold_6']);
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo $gross_6;
                    ?>
                </td>
                <td class="tg-0lax">7.50</td>
                <td class="tg-0lax">
                    <?php
                    echo intval($GST_6_RS);
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo $GST_6_PS;
                    ?>
                </td>
            </tr>
        </tbody>
    </table>


    <h1>Second Show 07:30 PM</h1>
    <table class="tg">
        <thead>
            <tr>
                <th class="tg-0lax">Class</th>
                <th class="tg-0lax">Seat</th>
                <th class="tg-0lax">Rate</th>
                <th class="tg-0lax">Adm. Fee</th>
                <th class="tg-0lax">S.C</th>
                <th class="tg-0lax">Cess</th>
                <th class="tg-0lax">E.T</th>
                <th class="tg-0lax" colspan="2">Ticket</th>
                <th class="tg-0lax">Sold</th>
                <th class="tg-0lax">Gross</th>
                <th class="tg-0lax" colspan="3">GST<br>12%</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="tg-0lax">R</td>
                <td class="tg-0lax">200</td>
                <td class="tg-0lax">80</td>
                <td class="tg-0lax">66.43</td>
                <td class="tg-0lax">2.00</td>
                <td class="tg-0lax">3.00</td>
                <td class="tg-0lax">
                    <?php
                    echo $ET_R;
                    ?>
                </td>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax">
                    <?php
                    echo htmlspecialchars($_POST['sold_7']) + 1;
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo htmlspecialchars($_POST['sold_7']);
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo $gross_7;
                    ?>
                </td>
                <td class="tg-0lax">8.57</td>
                <td class="tg-0lax">
                    <?php
                    echo intval($GST_7_RS);
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo $GST_7_PS;
                    ?>
                </td>
            </tr>
            <tr>
                <td class="tg-0lax">I</td>
                <td class="tg-0lax">646</td>
                <td class="tg-0lax">70</td>
                <td class="tg-0lax">57.5</td>
                <td class="tg-0lax">2.00</td>
                <td class="tg-0lax">3.00</td>
                <td class="tg-0lax">
                    <?php
                    echo $ET_I;
                    ?>
                </td>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax">
                    <?php
                    echo htmlspecialchars($_POST['sold_8']) + 1;
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo htmlspecialchars($_POST['sold_8']);
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo $gross_8;
                    ?>
                </td>
                <td class="tg-0lax">7.50</td>
                <td class="tg-0lax">
                    <?php
                    echo intval($GST_8_RS);
                    ?>
                </td>
                <td class="tg-0lax">
                    <?php
                    echo $GST_8_PS;
                    ?>
                </td>
            </tr>

            <tr>
                <td class="tg-kxfu" colspan="14">&emsp;</td>
            </tr>
            <tr>
                <td class="tg-kxfu" colspan="10"><span style="font-weight:bold">Total</span></td>
                <td class="tg-kxfu" colspan="2"><span style="font-weight:bold">
                        <?php
                        echo $gross_today;
                        ?>
                    </span>
                </td>

                <td class="tg-kxfu"><span style="font-weight:bold">
                        <?php
                        echo intval($gst_total_RS);
                        ?>
                    </span>
                </td>
                <td class="tg-kxfu"><span style="font-weight:bold">
                        <?php
                        echo $gst_total_PS;
                        ?>
                    </span>
                </td>
            </tr>

        </tbody>
    </table>
    <br><br>
    <div class="container space-around">

        <div>
            <table class="tg">
                <thead>
                    <tr>
                        <th class="tg-0lax"> Batta</th>
                        <th class="tg-0lax">&emsp;KFCC&emsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="tg-0lax">400.00</td>
                        <td class="tg-0lax">2.00</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div>
            <table class="tg">
                <thead>
                    <tr>
                        <th class="tg-0lax">&emsp;&emsp;&nbsp;&nbsp;Gross&emsp;&emsp;&nbsp;&nbsp;</th>
                        <th class="tg-0lax">&emsp;&nbsp;&nbsp;Nett&emsp;&nbsp;&nbsp;</th>
                        <th class="tg-0lax">&emsp;&emsp;&emsp;DS&emsp;&emsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="tg-0lax">&emsp;&emsp;&emsp;ES&emsp;&emsp;&nbsp;&nbsp;&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <?php
                                echo htmlspecialchars($_POST['gross']);
                                ?>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <?php
                                echo htmlspecialchars($_POST['nett']);
                                ?>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <?php
                                echo htmlspecialchars($_POST['distShr']);
                                ?>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <?php
                                echo htmlspecialchars($_POST['eShr']);
                                ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <?php
                                echo $gross_today;
                                ?>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <?php
                                echo $nett_today;
                                ?>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <?php
                                echo $ds_today;
                                ?>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <?php
                                echo $es_today;
                                ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <?php
                                echo $gross;
                                ?>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <?php
                                echo $nett;
                                ?>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <?php
                                echo $distShr;
                                ?>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <?php
                                echo $eShr;
                                ?>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
