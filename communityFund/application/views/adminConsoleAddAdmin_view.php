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
		
		<h1>Modify Accounts</h1>
		
		<table>
			<th>uid</th>
			<th>Username</th>
			<th>Admin</th>
			<th>Modify</th>
			<th>Deactivate Account</th>
			<?php
				foreach ($admin as $row) {
					echo "<tr align='center'>";
						echo "<td>".$row->uid."</td>";
						echo "<td>".$row->username."</td>";
						if ($row->admin == 1) {
							// user is admin
							echo "<td>Yes</td>";
						} else {
							// user is not admin
							echo "<td>No</td>";
						}
						echo "<td>";
						if ($row->admin == 1) {
							// user is an admin
							echo '<button type="button" onClick="window.location.href = \'';
							echo base_url();
							echo 'adminConsole/removeAdmin/'.$row->uid.'\'">Remove Admin</button>';
						} else {
							// user is not admin
							echo '<button type="button" onClick="window.location.href = \'';
							echo base_url();
							echo 'adminConsole/makeAdmin/'.$row->uid.'\'">Make Admin</button>';
						}
						echo "</td>";
						// deactivate account
						echo "<td>";
							echo '<button type="button" onClick="window.location.href = \'';
							echo base_url();
							echo 'adminConsole/deactivate/'.$row->uid.'\'">Deactivate</button>';
						echo "</td>";
					echo "</tr>";
				}
			
			 ?>
			</tr>
		</table>
	</body>
	
</html>