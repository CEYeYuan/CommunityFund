<!DOCTYPE html>
<html>
	<head>
		<link type='text/css' rel='stylesheet' href='<?php echo base_url()?>assets/stylesheets/navbar.css' />
		<link type='text/css' rel='stylesheet' href='<?php echo base_url()?>assets/stylesheets/catagory.css' />
		<link type="text/css" rel="stylesheet" href='<?php echo base_url()?>assets/stylesheets/project.css' />
		<link type='text/css' rel='stylesheet' href='<?php echo base_url()?>assets/stylesheets/profile.css' />
		<script type="text/javascript" src="assets/js/jquery-2.1.3.js"></script>
	</head>
	
	<header>
		<!-- Include the header -->
		<?php $this->load->view('menubar'); ?>
	</header>
	
	<body>
		
		<h1>Modify Categories</h1>
		
		<?php echo validation_errors(); ?>
		
		<table>
			<th>cid</th>
			<th>Name</th>
			<th>Description</th>
			<th>Active</th>
			<th>Modify</th>
			
			<?php
				
				foreach ($catInfo as $row) {
					echo "<tr align='center'>";
						echo "<td>".$row->cid."</td>";
						echo "<td>".$row->name."</td>";
						echo "<td>".$row->description."</td>";
						if ($row->active == 1) {
							// the Category is enabled
							echo "<td>Yes</td>";
						} else {
							// the Category is disabled
							echo "<td>No</td>";
						}
						
						echo "<td>";
						
						if ($row->active == 1) {
							// Category enabled
							
							echo '<button type="button" onClick="window.location.href = \'';
							echo base_url();
							echo 'adminConsole/disableCategory/'.$row->cid.'\'">Disable</button>';
							
						} else {
							// Category disabled
							
							echo '<button type="button" onClick="window.location.href = \'';
							echo base_url();
							echo 'adminConsole/enableCategory/'.$row->cid.'\'">Enable</button>';
						}
						
						echo "</td>";
						
					echo "<tr>";
				}
			
			 ?>
			
		</table>
		
		<br>
		
		** Disabling a Category will only prevent future Projects from being created within that Category.
		
		<br>
		
		<h2>Create New Category</h2>
		
		<form method="post" action="<?php echo base_url(); ?>adminConsole/createCategory">
			Name: <input type="text" name="name" />
			<br>
			Description: <input type="text" name="description" />
			<br>
			<br>
			<button type="submit">Create</button>
		</form>
		
	</body>
	
</html>