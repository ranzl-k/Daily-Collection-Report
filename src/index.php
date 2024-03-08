<?php
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!----======== CSS ======== -->
    <link rel="stylesheet" href="formstyle.css">

    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <title>Daily Collection Report</title>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script>
        function GetDetail(str) {
            // Creates a new XMLHttpRequest object 
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {

                // Defines a function to be called when 
                // the readyState property changes 
                if (this.readyState == 4 &&
                    this.status == 200) {

                    // Typical action to be performed 
                    // when the document is ready 
                    var myObj = JSON.parse(this.responseText);

                    // Returns the response data as a 
                    // string and store this array in 
                    // a variable assign the value  
                    // received to first name input field 

                    document.getElementById("gross").value = myObj[0];
                    document.getElementById("nett").value = myObj[1];
                    document.getElementById("distShr").value = myObj[2];
                    document.getElementById("eShr").value = myObj[3];
                }
            };

            xmlhttp.open("GET", "send.php?yestDate=" + str, true);

            // Sends the request to the server 
            xmlhttp.send();
        }
    </script>
</head>

<body>
    <div class="container">
        <header>Daily Collection Report</header>

        <form method="POST" action="display.php" enctype="multipart/form-data">
            <div class="film details">
                <span class="title">Film Details</span>

                <div class="fields">
                    <div class="input-field">
                        <label for="picture">Picture</label>
                        <input type="text" name="picture" id="picture" placeholder="Picture" required>
                    </div>

                    <div class="input-field">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" placeholder="Date" required>
                    </div>

                    <div class="input-field">
                        <label for="day">Day</label>

                        <select name="day" id="day">
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>

                    <div class="input-field">
                        <label for="distributor">Distributor</label>
                        <input type="text" name="distributor" id="distributor" placeholder="Distributor" required>
                    </div>

                </div>
            </div>

            <div class="sold details">
                <span class="title">Morning Show 10:00 AM</span>

                <div class="fields">
                    <div class="input-field">
                        <input type="number" name="sold_1" id="sold_1" placeholder="Sold 1" required>
                    </div>

                    <div class="input-field">
                        <input type="number" name="sold_2" id="sold_2" placeholder="Sold 2" required>
                    </div>
                </div>

                <span class="title">Matinee 1:30 PM</span>

                <div class="fields">
                    <div class="input-field">
                        <input type="number" name="sold_3" id="sold_3" placeholder="Sold 3" required>
                    </div>

                    <div class="input-field">
                        <input type="number" name="sold_4" id="sold_4" placeholder="Sold 4" required>
                    </div>
                </div>

                <span class="title">First Show 4:30 PM</span>

                <div class="fields">
                    <div class="input-field">
                        <input type="number" name="sold_5" id="sold_5" placeholder="Sold 5" required>
                    </div>

                    <div class="input-field">
                        <input type="number" name="sold_6" id="sold_6" placeholder="Sold 6" required>
                    </div>
                </div>

                <span class="title">Second Show 7:30 PM</span>

                <div class="fields">
                    <div class="input-field">
                        <input type="number" name="sold_7" id="sold_7" placeholder="Sold 7" required>
                    </div>

                    <div class="input-field">
                        <input type="number" name="sold_8" id="sold_8" placeholder="Sold 8" required>
                    </div>
                </div>

                <button class="nextBtn" type="submit" name="submit" value="submit">
                    <span class="btnText">Submit</span>
                    <i class="uil uil-navigator"></i>
                </button>
            </div>

            <span class="title">Previous Show</span>

            <div class="fields">
                <div class="input-field">
                    <label for="yestDate">Yesterday</label>
                    <input type="date" name="yestDate" id="yestDate" placeholder="Date" onchange="GetDetail(this.value)" required>
                </div>

                <div class="input-field">
                    <label for="ds_rate">Rate</label>
                    <select name="ds_rate" id="ds_rate">
                        <option value="0.65">65%</option>
                        <option value="0.60">60%</option>
                        <option value="0.55">55%</option>
                        <option value="0.50">50%</option>
                    </select>
                </div>
            </div>

            <div class="fields">
                <div class="input-field">
                    <label for="gross">Gross</label>
                    <input type="text" name="gross" id="gross" placeholder="Gross" value="">
                </div>

                <div class="input-field">
                    <label for="nett">Nett</label>
                    <input type="text" name="nett" id="nett" placeholder="Nett" value="">
                </div>
            </div>
            <div class="fields">
                <div class="input-field">
                    <label for="distShr">DS</label>
                    <input type="text" name="distShr" id="distShr" placeholder="DS" value="">
                </div>

                <div class="input-field">
                    <label for="eShr">ES</label>
                    <input type="text" name="eShr" id="eShr" placeholder="ES" value="">
                </div>
            </div>
        </form>
    </div>
    <script src="script.js"></script>
</body>

</html>
