<!DOCTYPE html>
<html>
<head>
	<link rel='stylesheet' href='<?php echo base_url(); ?>assets/stylesheets/navbar.css' type='text/css' />
	<link rel='stylesheet' href='<?php echo base_url(); ?>assets/stylesheets/homeBackground.css' type='text/css' />
	<script type="../views/text/javascript" src="js/jquery-2.1.3.js"></script>
	<title>My Friends</title>
</head>
<header>
	<?php $this->load->view('menubar'); ?>
	</header>
</header>
<body>
	<h2>My Communties:</h2>
	<ul>
		<?php 
		if ($communities->num_rows()===0){
			echo "You haven't joined any community yet!";
		}else{
			foreach($communities->result() as $row){
				echo "<li>";
				echo $row->name;
				echo "</li>";
			}
		}
		?>
	</ul>

	<h2>My Friends:</h2>
	<ul>
		<?php 

		//Since query result include userself, even the num_rows()===1, there's no friend
		if ($friends->num_rows()===1 or $friends->num_rows()===0){
			echo "You don't have any friend right now, initiate or fund a project !";
		}else{
	
			foreach($friends->result() as $row){
				if ($row->username==$this->session->userdata('email')){
				}else{

					echo "<li>";
					$url=base_url()."friends/chat/$row->uid";
					echo "<a href='$url'>$row->username($row->firstName   $row->lastName)</a>";
					echo "</li>";
				}	
			}
		}
		?>
	</ul>

</body>
</html>