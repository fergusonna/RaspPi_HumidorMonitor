<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
 
sec_session_start();
?>

<html>
  <head>
  <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Humidor Stats</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/mobile.css" media="screen and (max-width : 568px)">
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
    
    // Load the Visualization API and the piechart package.
    google.load('visualization', '1.1', {'packages':['corechart']});
      
    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);
      
    function drawChart() {
      var jsonData = $.ajax({
        url: "getData.php",
        dataType:"json",
        async: false
        }).responseText;
          
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
     
      // assumes you have timestamps in column 0, and two data series (columns 1 and 2)
      var view = new google.visualization.DataView(data);
      view.setColumns([{
        type: 'datetime',
        label: data.getColumnLabel(0),
        calc: function (dt, row) {
          var timestamp = dt.getValue(row, 0) * 1000; // convert to milliseconds
          return new Date(timestamp);
        }
      }, 1, 2]);

      var options = {
        title: 'Temperature and Relative Humidity over Time',      	
        width: 960,
        height: 600,
        vAxis: {title: "Temperature (F) and Humidity (%)"},
        hAxis: {title: "Date and Time"},
//        chartArea: {'width': '100%', 'height': '80%'},
      }  

      chart.draw(view, options);
    }

    </script>

    <script type="text/javascript" src="jQuery.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        // First load the chart once 
        drawChart();
        // Set interval to call the drawChart again
        setInterval(drawChart, 60000);
      });
    </script>

  </head>
  <body>
  <div id="header">
		<a href="#" class="logo">
			<img src="images/logo.png" alt="" height="109" width="400">
		</a>
		<ul id="navigation">
            <li class="selected">
				<a href="stats.php">stats</a>
			</li>
			<li>
				<a href="about.php">about</a>
			</li>
			<li>
				<a href="gallery.php">gallery</a>
			</li>
            <li>
				<a href="includes/logout.php">logout</a>
			</li>
		</ul>
	</div>
    <!--Div that will hold the line chart-->
    <div id="chart_div"></div>
    <div>
<?php
/* 
	VIEW.PHP
	Displays data from 'TempHumid' table
*/

	// connect to the database
	include('connect-db.php');

	// get results from database
	$result = mysql_query("SELECT * FROM TempHumid ORDER BY ComputerTime DESC LIMIT 1") 
		or die(mysql_error());  
		
	// display data in table	
	echo "<table align='center' width='1000' border='1'>";
	echo "<tr> <th scope='col' colspan='2'><h2 align='center'>Current Humidor Readings</h2></th> <th scope='col' colspan='2'><h2 align='center'>Average Humidor Readings</h2></th></tr>";
	
	// loop through results of database query, displaying them in the table
	while($row = mysql_fetch_array( $result )) {
		
		// echo out the contents of each row into a table
		echo "<tr>";
		echo '<td><h3>Current Temperature: ' . $row['2'] . '&deg;</h3></td>';
		echo '<td><h3>Current Humidity:    ' . $row['3'] . '%</h3></td>';
	}
	
	$sql = "SELECT ROUND(AVG(Temperature),1) FROM TempHumid";
	$result = mysql_query($sql) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		echo '<td><h3>Average Temperature: ' . $row['ROUND(AVG(Temperature),1)'] . '&deg;</h3></td>';
}

	$sql = "SELECT ROUND(AVG(Humidity),1) FROM TempHumid";
	$result = mysql_query($sql) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		echo '<td><h3>Average Humidity: ' . $row['ROUND(AVG(Humidity),1)'] . '%</h3></td>';
}

	// close table>
	echo "</table>";
	
	echo "<p> <br> </p>";
	
echo "<table align='center' width='1000' border='1'>";
	echo "<tr> <th scope='col' colspan='2'><h2 align='center'>Temperature High/Low</h2></th> <th scope='col' colspan='2'><h2 align='center'>Humidity High/Low</h2></th></tr>";
	
	$sql = "SELECT MAX(Temperature) FROM TempHumid";
	$result = mysql_query($sql) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		echo '<td><h3>Temperature High: ' . $row['MAX(Temperature)'] . '&deg;</h3></td>';
}
	
		$sql = "SELECT MIN(Temperature) FROM TempHumid";
	$result = mysql_query($sql) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		echo '<td><h3>Temperature Low: ' . $row['MIN(Temperature)'] . '&deg;</h3></td>';
}

$sql = "SELECT MAX(Humidity) FROM TempHumid";
	$result = mysql_query($sql) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		echo '<td><h3>Humidity High: ' . $row['MAX(Humidity)'] . '%</h3></td>';
}
	
		$sql = "SELECT MIN(Humidity) FROM TempHumid";
	$result = mysql_query($sql) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		echo '<td><h3>Humidity Low: ' . $row['MIN(Humidity)'] . '%</h3></td>';
}

	// close table>
	echo "</table>";
	
	echo "<p></p>";
	
	$sql = 'SELECT MAX(FROM_UNIXTIME(ComputerTime, "%m-%d-%Y at %r")) FROM TempHumid';
	$result = mysql_query($sql) or die(mysql_error());

	while($row = mysql_fetch_array($result)) {
		
echo "<table align='center' width='1000' border='0'>";
	echo "<tr> <th scope='col' colspan='2'><h3 align='center'>Most Recent Update: " . $row['MAX(FROM_UNIXTIME(ComputerTime, "%m-%d-%Y at %r"))'] ."</h3></th> </tr>";
	}
	// close table>
	echo "</table>";
/*
//Temperature Email Alert	
	if ($row['2']<='59.99'){
		//Message
		$msg = "The temperature in the humidor is currently below 60ºF. Check ASAP.";
		// send email
		mail("INSERT EMAIL","Humidor Tempeature Too Low!",$msg);
	} elseif ($row['2']>='79.99') {
		//Message
		$msg = "The temperature in the humidor currently exceeds 80ºF. Check ASAP.";
		// send email
		mail("INSERT EMAIL","Humidor Tempeature Too High!",$msg);
	} else {
	}
	
	//Temperature Email Alert	
	if ($row['3']<='59.99'){
		//Message
		$msg = "The humidity level in the humidor is currently below 60%. Check ASAP and humidify if necessary.";
		// send email
		mail("INSERT EMAIL","Humidor Humidity Too Low!",$msg);
	} elseif ($row['2']>='79.99') {
		//Message
		$msg = "The humidity level in the humidor currently exceeds 80%. Check ASAP and remove humidifier if necessary.";
		// send email
		mail("INSERT EMAIL","Humidor Humidity Too High!",$msg);
	} else {
	}
*/
?>
	</div>
    <div id="footer">
		<div>
			<p>&copy; 2016 by BSP. All rights reserved.</p>
		</div>
	</div>
  </body>
</html>
