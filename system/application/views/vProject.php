<html>
<head>
<title><?php echo $title;?></title>
<link type="text/css" href="<?php echo base_url();?>themes/ui.all.css" rel="Stylesheet" />	
<link type="text/css" href="<?php echo base_url();?>style/global.css" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url();?>style/jquery.autocomplete.css" rel="Stylesheet" />	
<script type="text/javascript" src="<?php echo base_url();?>javascript/jquery-1.3.1.js"></script>
<script type="text/javascript">
	
	$(document).ready(function() 
	{
		if('<?php echo $action;?>' == 'show')
		{
			$('#show_project').show();
		}
		if('<?php echo $action;?>' == 'edit')
		{
			$('#show_project').hide();
			$('#edit_project').show();
		}
	});
	function fBackClick()
	{
		window.location = "<?php echo base_url();?>"
	}
	function fEditClick()
	{
		window.location = "<?php echo base_url();?>index.php/server/fShowProject/<?php echo $p_id;?>/edit"
	}
	
</script>
</head>
<body>
	<div>
		<div id = "title_area"><h1>DC-Technology Project List</h1></div></br>
		<div id = "clock_area"></div>
		<div id = "content_area">
			<div id = "show_project" class="form" style="display:none">
				Project details:</br>
				</br>
				Client Name: <?php echo $p_client;?></br>
				Project Number: <?php echo $p_number;?></br>
				Project Title: <?php echo $p_title;?></br>
				Project Content: <?php echo $p_content?></br>
				Assigned To: <?php echo $p_person;?></br>
				Started: <?php echo $p_created;?></br>
				Due Date: <span style="color:red"><?php echo $p_duedate;?></span></br>
				Status: <?php if($p_status == "0") echo "Active"; else echo "Closed";?></br></br>
				<input type="button" id="edit_yesBtn" value="Edit" onclick = "fEditClick()"/><input type="button" id="edit_noBtn" value="Back to All Projects" onclick = "fBackClick()"/></br>
			</div>			
		</div>
		<div id = "edit_project" class="form" style="display:none">
			Edit Project:</br>
			</br>
			Client Name:</br>
			<input type="text" id="edit_client" style="width:100%" value="" TABINDEX=1/></br>
			Project Number:</br>
			<input type="text" id="edit_number" style="width:100%" value="" TABINDEX=2/></br>
			Project Title:</br>
			<input type="text" id="edit_title" style="width:100%" value="" TABINDEX=3/></br>
			Project Content:</br>
			<textarea rows="2" cols="20" id="edit_content" style="width:100%; height:100px" TABINDEX=4></textarea></br>
			Assigned To:</br>
			<input type="text" id="edit_assignedTo" style="width:100%" value="" TABINDEX=5/></br>
			Due Date:</br>
			<input type="text" id="edit_dueDate" style="width:100%" value="" TABINDEX=6/></br>
			<input type="checkbox" id="edit_check" TABINDEX=7> Close Project</input></br></br>
			<input type="button" id="edit_yesBtn" value="Save" TABINDEX=8/><input type="button" id="edit_noBtn" value="Cancel" TABINDEX=9/></br>
		</div>
		<div id = "wait_msg">Please wait...</div>
	</div>	
</body>
</html>