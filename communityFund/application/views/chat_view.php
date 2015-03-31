<!DOCTYPE html>
<html>
<head>
	<link rel='stylesheet' href='<?php echo base_url(); ?>assets/stylesheets/navbar.css' type='text/css' />
	<link rel='stylesheet' href='<?php echo base_url(); ?>assets/stylesheets/homeBackground.css' type='text/css' />
	<script type="../views/text/javascript" src="js/jquery-2.1.3.js"></script>
	<title>Chat With <?php echo $withWhomfn->row()->firstName; ?></title>
</head>
<header>
	<?php $this->load->view('menubar'); ?>
</header>
<body>
	<h3>Chat With <?php echo $withWhomfn->row()->firstName; ?></h3>
	<?php 
		if ($history->num_rows()==0){
			$str="You never talk to ".$withWhomfn->row()->firstName.", send a message now!";
			echo $str;
		}else{
			foreach($history->result() as $row){
				$time=substr($row->time, 11,5);
				if ($row->sender==$withWhomuid){
					echo $withWhomfn->row()->firstName.": $time <br/>";
					echo $row->msg;
					echo "<br/>";
				}else{
					echo "<span style='padding-left:5em'>me: ".$time."</span><br/>";
					echo "<span style='padding-left:5em;font-family:Arial'>".$row->msg."</span>";
					echo "<br/>";
				}

			}
			
		}
	?>
	<form action='<?php echo base_url()."friends/chat/$withWhomuid"?>' method='post'>
		<textarea name='msg' rows="4" cols='50' > </textarea>	
		<br/>
		<input type="submit"  value='send'/>
		
	</form>

</body>
</html>