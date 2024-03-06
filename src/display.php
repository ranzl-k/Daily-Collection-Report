<?php
$username = "root"; // Replace with your MySQL username
$password = "root"; // Replace with your MySQL password
$dbname = "dcr";
$servername = "mysql_db";  // Use the hostname set in the docker-compose.yml
$port = 3306;  // MySQL port number

// Create a connection to the MySQL server without specifying a database
$conn = new mysqli($servername, $username, $password, '', $port);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "CREATE DATABASE IF NOT EXISTS dcr";

$conn->select_db($dbname);

$query = "CREATE TABLE IF NOT EXISTS collection (
        date DATE PRIMARY KEY,
        gross VARCHAR(255) NOT NULL,
        nett VARCHAR(255) NOT NULL,
        distShr VARCHAR(255) NOT NULL,
        eShr VARCHAR(255) NOT NULL
    )";

$dt = $_POST['date'];
$grs = $_POST['gross_yesterday'];
$ntt = htmlspecialchars($_POST['nett_yesterday']);
$dist = htmlspecialchars($_POST['ds']);
$est = htmlspecialchars($_POST['es']);

$query = "INSERT INTO collection (date, gross, nett, distShr, eShr) VALUES ('$dt', '$grs','$ntt', '$dist', '$est')";

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="tablestyle.css">
</head>

<body>
    <script>
        let picture = htmlspecialchars($_POST['picture']);
        let date = htmlspecialchars($_POST['date']);
        document.title = date;
        let day = htmlspecialchars($_POST['day']);
        let distributor = htmlspecialchars($_POST['distributor']);

        let sold_1 = htmlspecialchars($_POST['sold_1']);
        let sold_2 = htmlspecialchars($_POST['sold_2']);
        let sold_3 = htmlspecialchars($_POST['sold_3']);
        let sold_4 = htmlspecialchars($_POST['sold_4']);
        let sold_5 = htmlspecialchars($_POST['sold_5']);
        let sold_6 = htmlspecialchars($_POST['sold_6']);
        let sold_7 = htmlspecialchars($_POST['sold_7']);
        let sold_8 = htmlspecialchars($_POST['sold_8']);

        let to_1 = sold_1 + 1;
        let to_2 = sold_2 + 1;
        let to_3 = sold_3 + 1;
        let to_4 = sold_4 + 1;
        let to_5 = sold_5 + 1;
        let to_6 = sold_6 + 1;
        let to_7 = sold_7 + 1;
        let to_8 = sold_8 + 1;


        const sc = 2;
        const cess = 3;
        const batta = 400;
        const kfcc = 2;
        const ET_R = 3.16;
        const ET_I = 2.74;
        const gross_tax_R = sc + cess + ET_R;
        const gross_tax_I = sc + cess + ET_I;
        const rate_R = 80 - gross_tax_R;
        const rate_I = 70 - gross_tax_I;

        const format = (num) => num.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

        let gross_1 = format(sold_1 * rate_R);
        let gross_2 = format(sold_2 * rate_I);
        let gross_3 = format(sold_3 * rate_R);
        let gross_4 = format(sold_4 * rate_I);
        let gross_5 = format(sold_5 * rate_R);
        let gross_6 = format(sold_6 * rate_I);
        let gross_7 = format(sold_7 * rate_R);
        let gross_8 = format(sold_8 * rate_I);

        const GST_R = 8.57;
        const GST_I = 7.50;
        let GST_1_RS = Math.trunc(GST_R * sold_1);
        let GST_1_PS = format(GST_R * sold_1 - Math.trunc(GST_R * sold_1));
        let GST_2_RS = Math.trunc(GST_I * sold_2);
        let GST_2_PS = format(GST_I * sold_2 - Math.trunc(GST_I * sold_2));
        let GST_3_RS = Math.trunc(GST_R * sold_3);
        let GST_3_PS = format(GST_R * sold_3 - Math.trunc(GST_R * sold_3));
        let GST_4_RS = Math.trunc(GST_I * sold_4);
        let GST_4_PS = format(GST_I * sold_4 - Math.trunc(GST_I * sold_4));
        let GST_5_RS = Math.trunc(GST_R * sold_5);
        let GST_5_PS = format(GST_R * sold_5 - Math.trunc(GST_R * sold_5));
        let GST_6_RS = Math.trunc(GST_I * sold_6);
        let GST_6_PS = format(GST_I * sold_6 - Math.trunc(GST_I * sold_6));
        let GST_7_RS = Math.trunc(GST_R * sold_7);
        let GST_7_PS = format(GST_R * sold_7 - Math.trunc(GST_R * sold_7));
        let GST_8_RS = Math.trunc(GST_I * sold_8);
        let GST_8_PS = format(GST_I * sold_8 - Math.trunc(GST_I * sold_8));

        const ds_rate = htmlspecialchars($_POST['ds_rate']);

        const sold_R = sold_1 + sold_3 + sold_5 + sold_7;
        const sold_I = sold_2 + sold_4 + sold_6 + sold_8;

        const gst_total_R = sold_R * GST_R;
        const gst_total_I = sold_I * GST_I;
        const gst_total_RS = Math.trunc(gst_total_R + gst_total_I);
        const gst_total_PS = gst_total_R + gst_total_I;
        const gst_total_PS_formatted = format(gst_total_PS - Math.trunc(gst_total_PS));

        const gross_total_R = sold_R * rate_R;
        const gross_total_I = sold_I * rate_I;
        const gross_total_copy = (gross_total_R + gross_total_I);
        const gross_total = format(gross_total_copy);

        const nett_total_copy = (gross_total_copy - (gst_total_R + gst_total_I + batta + kfcc));
        const nett_total = format(nett_total_copy);

        const ds_today_copy = nett_total_copy * ds_rate;
        const ds_today = format(ds_today_copy);

        const es_today_copy = nett_total_copy * (1 - ds_rate);
        const es_today = format(es_today_copy);

        const gross_uptodate = format(gross_yesterday + gross_total_copy);
        const nett_uptodate = format(nett_yesterday + nett_total_copy);
        const ds_uptodate = format(ds_yesterday + ds_today_copy);
        const es_uptodate = format(es_yesterday + es_today_copy);
        gross_yesterday = format(gross_yesterday);
        nett_yesterday = format(nett_yesterday);
        ds_yesterday = format(ds_yesterday);
        es_yesterday = format(es_yesterday);
    </script>
    <h1 align="center" style="font-size: 300%;">
        DWARAKA THEATRE, KOYILANDY<br>
        DAILY COLLECTION REPORT<br>
        GSTIN : 32BBPPC8248F1ZO<br>
    </h1>
    <br>
    <div class="container space-between">
        <h2 style="font-size: 200%;">
            Picture :
            <script>
                document.write(picture)
            </script>
            <br>
            Distributor :
            <script>
                document.write(distributor)
            </script>
        </h2>

        <h2 style="font-size: 200%;">
            Date :
            <script>
                document.write(date)
            </script>
            <br>
            Day :
            <script>
                document.write(day)
            </script>
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
                    <script>
                        document.write(ET_R)
                    </script>
                </td>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax">
                    <script>
                        document.write(to_1)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(sold_1)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(gross_1)
                    </script>
                </td>
                <td class="tg-0lax">8.57</td>
                <td class="tg-0lax">
                    <script>
                        document.write(GST_1_RS)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(GST_1_PS)
                    </script>
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
                    <script>
                        document.write(ET_I)
                    </script>
                </td>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax">
                    <script>
                        document.write(to_2)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(sold_2)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(gross_2)
                    </script>
                </td>
                <td class="tg-0lax">7.50</td>
                <td class="tg-0lax">
                    <script>
                        document.write(GST_2_RS)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(GST_2_PS)
                    </script>
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
                    <script>
                        document.write(ET_R)
                    </script>
                </td>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax">
                    <script>
                        document.write(to_3)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(sold_3)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(gross_3)
                    </script>
                </td>
                <td class="tg-0lax">8.57</td>
                <td class="tg-0lax">
                    <script>
                        document.write(GST_3_RS)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(GST_3_PS)
                    </script>
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
                    <script>
                        document.write(ET_I)
                    </script>
                </td>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax">
                    <script>
                        document.write(to_4)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(sold_4)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(gross_4)
                    </script>
                </td>
                <td class="tg-0lax">7.50</td>
                <td class="tg-0lax">
                    <script>
                        document.write(GST_4_RS)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(GST_4_PS)
                    </script>
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
                    <script>
                        document.write(ET_R)
                    </script>
                </td>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax">
                    <script>
                        document.write(to_5)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(sold_5)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(gross_5)
                    </script>
                </td>
                <td class="tg-0lax">8.57</td>
                <td class="tg-0lax">
                    <script>
                        document.write(GST_5_RS)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(GST_5_PS)
                    </script>
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
                    <script>
                        document.write(ET_I)
                    </script>
                </td>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax">
                    <script>
                        document.write(to_6)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(sold_6)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(gross_6)
                    </script>
                </td>
                <td class="tg-0lax">7.50</td>
                <td class="tg-0lax">
                    <script>
                        document.write(GST_6_RS)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(GST_6_PS)
                    </script>
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
                    <script>
                        document.write(ET_R)
                    </script>
                </td>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax">
                    <script>
                        document.write(to_7)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(sold_7)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(gross_7)
                    </script>
                </td>
                <td class="tg-0lax">8.57</td>
                <td class="tg-0lax">
                    <script>
                        document.write(GST_7_RS)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(GST_7_PS)
                    </script>
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
                    <script>
                        document.write(ET_I)
                    </script>
                </td>
                <td class="tg-0lax">1</td>
                <td class="tg-0lax">
                    <script>
                        document.write(to_8)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(sold_8)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(gross_8)
                    </script>
                </td>
                <td class="tg-0lax">7.50</td>
                <td class="tg-0lax">
                    <script>
                        document.write(GST_8_RS)
                    </script>
                </td>
                <td class="tg-0lax">
                    <script>
                        document.write(GST_8_PS)
                    </script>
                </td>
            </tr>

            <tr>
                <td class="tg-kxfu" colspan="14">&emsp;</td>
            </tr>
            <tr>
                <td class="tg-kxfu" colspan="10"><span style="font-weight:bold">Total</span></td>
                <td class="tg-kxfu" colspan="2"><span style="font-weight:bold">
                        <script>
                            document.write(gross_total)
                        </script>
                    </span>
                </td>

                <td class="tg-kxfu"><span style="font-weight:bold">
                        <script>
                            document.write(gst_total_RS)
                        </script>
                    </span>
                </td>
                <td class="tg-kxfu"><span style="font-weight:bold">
                        <script>
                            document.write(gst_total_PS_formatted)
                        </script>
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
                                <script>
                                    document.write(gross_yesterday)
                                </script>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <script>
                                    document.write(nett_yesterday)
                                </script>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <script>
                                    document.write(ds_yesterday)
                                </script>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <script>
                                    document.write(es_yesterday)
                                </script>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <script>
                                    document.write(gross_total)
                                </script>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <script>
                                    document.write(nett_total)
                                </script>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <script>
                                    document.write(ds_today)
                                </script>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <script>
                                    document.write(es_today)
                                </script>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <script>
                                    document.write(gross_uptodate)
                                </script>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <script>
                                    document.write(nett_uptodate)
                                </script>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <script>
                                    document.write(ds_uptodate)
                                </script>
                            </span>
                        </td>
                        <td class="tg-kxfu"><span style="font-weight:bold">
                                <script>
                                    document.write(es_uptodate)
                                </script>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>