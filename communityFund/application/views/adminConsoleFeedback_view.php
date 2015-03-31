<!DOCTYPE html>
<html>
	<head>
		<link type='text/css' rel='stylesheet' href='<?php echo base_url()?>assets/stylesheets/navbar.css' />
		<link type='text/css' rel='stylesheet' href='<?php echo base_url()?>assets/stylesheets/catagory.css' />
		<link type='text/css' rel='stylesheet' href='<?php echo base_url() ?>assets/stylesheets/adminConsole.css' />
		<link type="text/css" rel="stylesheet" href='<?php echo base_url()?>assets/stylesheets/project.css' />
		<link type='text/css' rel='stylesheet' href='<?php echo base_url()?>assets/stylesheets/profile.css' />
		<script type="text/javascript" src="assets/js/jquery-2.1.3.js"></script>
	</head>
	
	<header>
		<!-- Include the header -->
		<?php $this->load->view('menubar'); ?>
	</header>
	
	<body>
		
		<h1>Bug Report/Feedback Form</h1>
		
		<?php echo validation_errors(); ?>
		
		<form method="post" action="<?php echo base_url();?>adminConsole/createFeedback">
			Title: <input type="text" name="title" />
			<br>
			Type: 
			<select name="type" size="1">
				<option value="Bug">Bug</option>
				<option value="Enhancement">Enhancement</option>
				<option value="Comment">Comment</option>
				<option value="Other">Other</option>
			</select>
			<br>
			<br>
			Description:
			<br>
			<textarea name="description" rows="10" cols="90"></textarea>
			<br>
			<br>
			<button type="submit">Submit</button>
		</form>
		
	</body>

</html>