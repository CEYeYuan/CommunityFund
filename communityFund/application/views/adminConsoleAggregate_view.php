<!DOCTYPE html>
<html>
	<head>
		<link type='text/css' rel='stylesheet' href='<?php echo base_url()?>assets/stylesheets/navbar.css' />
		<link type='text/css' rel='stylesheet' href='<?php echo base_url()?>assets/stylesheets/catagory.css' />
		<link type="text/css" rel="stylesheet" href='<?php echo base_url()?>assets/stylesheets/project.css' />
		<link type='text/css' rel='stylesheet' href='<?php echo base_url()?>assets/stylesheets/profile.css' />
		<script type="text/javascript" src="assets/js/jquery-2.1.3.js"></script>
		
		<!-- WEBSITE CODE -->
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
			/*
		  * This code was taken and modified from the following
		  * Google Developers Charts API website tutorial.
		  *
		  * Link: https://google-developers.appspot.com/chart/interactive/docs/quick_start
		  *
		  * Note: This will only work if you have an internet connection
		  * because the google API is online
		  *
		  **/
	
	      // Load the Visualization API and the piechart package.
	      google.load('visualization', '1.0', {'packages':['corechart']});
	
	      // Set a callback to run when the Google Visualization API is loaded.
	      google.setOnLoadCallback(drawChart);
	
	      // Callback that creates and populates a data table,
	      // instantiates the pie chart, passes in the data and
	      // draws it.
	      function drawChart() {
	
	        // Create the data table.
	        var data = new google.visualization.DataTable();
	        
	        data.addColumn('string', 'Category');
	        data.addColumn('number', 'Total');
	        data.addRows([<?php
					// only get the first item
					for ($i = 0; $i < 1; $i++) {
						echo "['".$graph[0]->cname."', ".$graph[0]->total."]";
					}
					
					$count = 0;
					
					foreach ($graph as $row) {
						if (!($count == 0)) {
							// if it is not the first row
							// do nothing for the first row as it was
							// already handled above
							
							echo ", ";
							echo "['".$row->cname."', ".$row->total."]";
						}
						$count = $count + 1;
					}
				 ?>]);
	
	        // Set chart options
	        var options = {'title':'Project Category Breakdown',
							'width':800,
							'height':300};
	        
	        // Instantiate and draw our chart, passing in some options.
	        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
	        chart.draw(data, options);
	      }
		</script>
	</head>
	
	
	
	<header>
		<!-- Include the header -->
		<?php $this->load->view('menubar'); ?>
	</header>
	
	<body>
		
		<h1>Aggregate Data</h1>
		
		<h2>Useful Info</h2>
		
		<table>
			<tr>
				<td>Projects Completely Funded:</td>
				<td><?php echo $fullFunded; ?></td>
			</tr>
			<tr>
				<td>Projects Partially Funded:</td>
				<td><?php echo $partFunded; ?></td>
			</tr>
			<tr>
				<td>Total Number of Categories:</td>
				<td><?php echo $numCategories; ?></td>
			</tr>
		</table>
		
		<h2>Project Category Breakdown</h2>
		
		<!--Div that will hold the pie chart-->
	    <div id="chart_div"></div>
		
		
	</body>
	
</html>