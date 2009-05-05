<html>
<head>
<title>Project List</title>
<link type="text/css" href="<?php echo base_url();?>themes/ui.all.css" rel="Stylesheet" />	
<link type="text/css" href="<?php echo base_url();?>style/global.css" rel="Stylesheet" />
<link type="text/css" href="<?php echo base_url();?>style/jquery.autocomplete.css" rel="Stylesheet" />	
<script type="text/javascript" src="<?php echo base_url();?>javascript/jquery-1.3.1.js"></script>
<script type="text/javascript">
	
	var g_userName = ""; // User first name and surname
	var g_personId = ""; // User->person ID
	var g_userId = ""; //User ID
	var g_userStatus = false; //User login status
	var g_editProject = ''; //project id to edit
	var g_deleteProject = ''; //project id to delete
	var oneOpen = false; //parameter if one op popup windows is opened or not.
	var arrClientData ; // data about the client to autocompletion function.
	var arrUserData ; //user data to autocompletion function
	var blnClosed = false; //parameter to store if project is closed.
	//JQuery function after page load
	$(document).ready(function() 
	{
		fGetSession();
		$('clock_area').jclock();
		$('#new_password1,#new_password2,#old_password,#add_client_name,#project_title,#project_client,#edit_client,#edit_title,#add_user_name,#add_user_pass1,#add_user_pass2,#add_user_fname,#add_user_lname,#add_user_email').focus(function(event) 
		{ 
			if($(this).css('color') == 'red')
			{
				$(this).css('color','black');
				$(this).val("");
			}
        });
		$('#project_title').css('color','red');
		$('#project_title').val("Requared");
		$('#add_project_btn').click(function(event) 
		{ 
			$('#project_client').val('');			
			$('#project_title').val('');
			$('#project_number').val('');
			$('#project_content').val('');
			$('#project_assignedTo').val('');
			$('#project_dueDate').val('');
			fGetUsers('project_assignedTo');
			fGetClients('project_client');
			$('#addProject_form').show();
			$('#project_client').focus();
			$('#project_dueDate').datepicker({
				inline: true
			});
			oneOpen = true;
        });
		$('#all_project_btn').click(function(event) 
		{
			fGetProject('all');
        });
		$('#created_project_btn').click(function(event) 
		{
			fGetProject('created');
        });
		$('#assigned_project_btn').click(function(event) 
		{
			fGetProject('assigned');
        });
		$('#closed_project_btn').click(function(event) 
		{
			fGetProject('closed');
        });
		$('#add_project_btn,#all_project_btn,#created_project_btn,#assigned_project_btn,#login_btn,#logout_btn,#new_user_btn,#new_client_btn,#closed_project_btn,#search_btn,#change_password_btn').mouseover(function(event) 
		{
			$(this).css('cursor','pointer');
			$(this).css('text-decoration','underline');	
        });
		$('#add_project_btn,#all_project_btn,#created_project_btn,#assigned_project_btn,#login_btn,#logout_btn,#new_user_btn,#new_client_btn,#closed_project_btn,#search_btn,#change_password_btn').mouseout(function(event) 
		{
			$(this).css('text-decoration','none');	
        });
		//login logout buttons
		$('#login_btn').click(function(event) 
		{
			fLogin();
        });	
		$('#logout_btn').click(function(event) 
		{
			fLogout();
        });	
		//edit project Yes no buttons
		$('#edit_noBtn').click(function(event) 
		{
			$('#edit_project').hide();
			oneOpen = false;
        });
		$('#edit_yesBtn').click(function(event) 
		{
			fEditProjectSend(g_editProject);
			oneOpen = false;
        });
		//delete project Yes no buttons
		$('#delete_noBtn').click(function(event) 
		{
			$('#delete_project').hide();
			oneOpen = false;
        });
		$('#delete_yesBtn').click(function(event) 
		{
			fDeleteProjectSend(g_deleteProject);
			oneOpen = false;
        });
    	//add project yes no buttons
		$('#addProject_noBtn').click(function(event) 
		{
			fGetProject();
			oneOpen = false;
        });
		$('#addProject_yesBtn').click(function(event) 
		{
			fAddProject();
			oneOpen = false;
        });
		//add user section
		$('#new_user_btn').click(function(event) 
		{
			$('#add_user_name').val('');
			$('#add_user_pass1').val('');
			$('#add_user_pass2').val('');
			$('#add_user_fname').val('');
			$('#add_user_lname').val('');
			$('#add_user_email').val('');
			if(oneOpen == false)
			{
				$('#add_user').show();
				$('#add_user_name').focus();
				oneOpen = true;
			}
        });
		$('#add_user_noBtn').click(function(event) 
		{
			$('#add_user').hide();
			oneOpen = false;
        });
		$('#add_user_yesBtn').click(function(event) 
		{
			fAddUser();
			oneOpen = false;
        });
		//add client section
		$('#new_client_btn').click(function(event) 
		{
			$('#add_client_name').val('');
			$('#add_client_pass1').val('');
			$('#add_client_pass2').val('');
			$('#add_client_fname').val('');
			$('#add_client_lname').val('');
			$('#add_client_email').val('');
			if(oneOpen == false)
			{
				$('#add_client').show();
				$('#add_client_name').focus();
				oneOpen = true;
			}
        });
		$('#add_client_noBtn').click(function(event) 
		{
			$('#add_client').hide();
			oneOpen = false;
        });
		$('#add_client_yesBtn').click(function(event) 
		{
			fAddClient();
			oneOpen = false;
        });
		//search section
		$('#search_btn').click(function(event) 
		{
			if(oneOpen == false)
			{
				$('#search_project').show();
				$('#search_keyword').focus();
				oneOpen = true;
			}
        });
		$('#search_noBtn').click(function(event) 
		{
			$('#search_project').hide();
			oneOpen = false;
        });
		$('#search_yesBtn').click(function(event) 
		{
			fSearch();
			oneOpen = false;
        });
		//change password section
		$('#change_password_btn').click(function(event) 
		{
			if(oneOpen == false)
			{
				$('#change_password').show();
				$('#old_password').focus();
				oneOpen = true;
			}
        });
		$('#change_password_noBtn').click(function(event) 
		{
			$('#change_password').hide();
			oneOpen = false;
        });
		$('#change_password_yesBtn').click(function(event) 
		{
			fChangePassword();
			oneOpen = false;
        });
	});
	//function to fill projects to html table
	function fFormatTable()
	{
		var i = 0;
		var zebra = '';	
		var first = true;		
		$($($('#content_area').children().get(0)).children().get(0)).children().each(function() {
			if ( i % 2 == 1)
				zebra = 'white';
			else
				zebra = 'gainsboro';
			if(first == true)
				zebra = '#380000';
			$($(this).children().get(0)).css('width', '100px');
			$($(this).children().get(0)).css('text-wrap', 'normal');
			$($(this).children().get(0)).css('background-color', zebra);
			
			$($(this).children().get(1)).css('width', '50px');
			$($(this).children().get(1)).css('ext-wrap', 'normal');
			$($(this).children().get(1)).css('background-color', zebra);
			
			$($(this).children().get(2)).css('width', '80px');
			$($(this).children().get(2)).css('text-wrap', 'normal');
			$($(this).children().get(2)).css('background-color', zebra);
			
			//$(this).children().get(3).css('width', '500px');
			$($(this).children().get(3)).css('text-wrap', 'normal');
			$($(this).children().get(3)).css('background-color', zebra);
			
			$($(this).children().get(4)).css('width', '80px');
			$($(this).children().get(4)).css('text-wrap', 'normal');
			$($(this).children().get(4)).css('background-color', zebra);
			
			$($(this).children().get(5)).css('width', '80px');
			$($(this).children().get(5)).css('text-wrap', 'normal');
			$($(this).children().get(5)).css('background-color', zebra);
			
			$($(this).children().get(6)).css('width', '80px');
			$($(this).children().get(6)).css('text-wrap', 'normal');
			$($(this).children().get(6)).css('background-color', zebra);
			
			$($(this).children().get(7)).css('width', '80px');
			$($(this).children().get(7)).css('text-wrap', 'normal');
			$($(this).children().get(7)).css('background-color', zebra);
			
			$($(this).children().get(8)).css('width', '80px');
			$($(this).children().get(8)).css('text-wrap', 'normal');
			$($(this).children().get(8)).css('background-color', zebra);
			
			$($(this).children().get(9)).css('width', '50px');
			$($(this).children().get(9)).css('text-wrap', 'normal');
			$($(this).children().get(9)).css('background-color', zebra);
			i++;
			first = false;
		});	
	}
	// add event on edit and delete buttons if user is logged in
	function fAddEvents()
	{
		if(g_userStatus != false)
		{
			var i = 0;
			$($($('#content_area').children().get(0)).children().get(0)).children().each(function() {
				$($($(this).children().get(9)).children().get(0)).children().each(function() {
					var strId = $(this).attr('id');
					if(strId != '')
					{
						$('#' + strId).click(function() {
							if(oneOpen == false)
							{
								if(strId.split('_')[0] == 'delete')
									fDeleteProject(strId.split('_')[1]);
								if(strId.split('_')[0] == 'edit')
									fEditProject(strId.split('_')[1]);
								oneOpen = true;
							}
							
						});
						$('#' + strId).mouseover(function(event) 
						{
							$(this).css('cursor','pointer');
							$(this).css('text-decoration','underline');							
				        });
						$('#' + strId).mouseout(function(event) 
						{
							$(this).css('cursor','auto');
							$(this).css('text-decoration','none');							
				        });
					}
				});
				if(i > 0)
				{
					var cell = $(this).children().get(7);
					if(blnClosed == false)
					{
						var today = new Date()
						var targetDate = new Date($(cell).html().replace(/-/g,'/')) //use full year 
						var timeBeforeTarget = Math.floor(( targetDate.getTime() - today.getTime()) / 86400000)+1
						if(timeBeforeTarget == 0 )
						{
							$(cell).addClass('dateRed');
							$(cell).html($(cell).html() + '<br/>Today');
						}
						if(timeBeforeTarget < 0 )
						{
							$(cell).addClass('dateRed');
							$(cell).html($(cell).html() + '<br/>Late ' + timeBeforeTarget + ' day\'s');
						}	
						if(timeBeforeTarget > 0 && timeBeforeTarget <= 1)
						{
							$(cell).html($(cell).html() + '<br/>Left ' + timeBeforeTarget + ' day');
							$(cell).addClass('dateYellow');
						}
						if(timeBeforeTarget > 1 && timeBeforeTarget <= 3)
						{
							$(cell).html($(cell).html() + '<br/>Left ' + timeBeforeTarget + ' day\'s');
							$(cell).addClass('dateYellow');
						}
						if(timeBeforeTarget > 3 && timeBeforeTarget <= 6)
						{
							$(cell).addClass('dateGreen');
							$(cell).html($(cell).html() + '<br/>Left ' + timeBeforeTarget + ' day\'s');
						}
						if(timeBeforeTarget > 6)
						{
							$(cell).html($(cell).html() + '<br/>' + timeBeforeTarget + ' day\'s');
						}
					}
					else
					{
						$(cell).addClass('dateGreen');
						$(cell).html($(cell).html() + '<br/>-- Closed --');
					}					
				}
				i++;
			});
		}
	}
	//function to edit project
	function fEditProject(p_Id)
	{
		g_editProject = p_Id;
		$.ajax({
		   type: "POST",
		   url: "index.php/server/fEditProject",
		   data: "id=" + encodeURIComponent(p_Id),
		   success: function(msg){
			var dom = parseXML(decodeURIComponent(msg).replace(/\+/g,' ').replace(/&/g,'&amp;'));
			var client = $(dom).find('entry').find('PClient').text();  // "client"
			var number = $(dom).find('entry').find('PNumber').text();  // "number"
			var title = $(dom).find('entry').find('PTitle').text();  // "title"
			var content = $(dom).find('entry').find('PContent').text();  // "content"
			var assigned = $(dom).find('entry').find('PAssigned').text();  // "assigned"
			var duedate = $(dom).find('entry').find('PDueDate').text();  // "duedate"
			var status = $(dom).find('entry').find('PStatus').text();  // "duedate"
			var date = duedate.split('-')[1] + '/' + duedate.split('-')[2] + '/' + duedate.split('-')[0];
			$('#edit_project').show();
			$('#edit_dueDate').datepicker({
				inline: true
			});
			fGetUsers('edit_assignedTo');
			fGetClients('edit_client');
			$('#edit_client').val(client);
			$('#edit_title').val(title);
			$('#edit_number').val(number);
			$('#edit_content').val(content);
			$('#edit_assignedTo').val(assigned);
			$('#edit_dueDate').val(date);
			if(status == '0')
				$('#edit_check').attr('checked',false);
			else
				$('#edit_check').attr('checked',true);
			$('#edit_client').focus();
		   }
		 });
	}
	//parsing xml from xml string
	function parseXML( xml ) {
		if( window.ActiveXObject && window.GetObject ) {
            var dom = new ActiveXObject( 'Microsoft.XMLDOM' );
            dom.loadXML( xml );
            return dom;
        }
        if( window.DOMParser )
            return new DOMParser().parseFromString( xml, 'text/xml' );
        throw new Error( 'No XML parser available' );
    }        
	//function to send the request to server to delete project
	function fDeleteProjectSend(p_Id)
	{
		$.ajax({
		   type: "POST",
		   url: "index.php/server/fRemoveProject",
		   data: "id=" + encodeURIComponent(p_Id),
		   success: function(msg){
			fGetProject();
			oneOpen = false;
			$('#delete_project').hide();
		   }
		 });
	}
	//function to delete project
	function fDeleteProject(p_Id)
	{
		g_deleteProject = p_Id;
		$('#delete_project').show();
	}
	// function to check user status logged-in or not
	function fCheckUser(p_msg)
	{
		var arrSessionData = p_msg.split('/');
		if(arrSessionData[0] == '1')
		{
			$('#login_area').hide();
			$('#login_btn').hide();
			$('#logout_btn').show();
			$('#after_login').show();
			$('#add_project_btn').show();
			$('#all_project_btn').show();
			$('#created_project_btn').show();
			$('#assigned_project_btn').show();
			$('#new_user_btn').show();
			$('#new_client_btn').show();
			$('#closed_project_btn').show();
			$('#search_btn').show();
			$('#change_password_btn').show();
			$('#after_login').html('Name:</br>' + arrSessionData[1] + '</br>Login time:</br>' + arrSessionData[2].split('T')[0] + ' ' + arrSessionData[2].split('T')[1].split('+')[0]);
			g_userName = arrSessionData[1];
			g_personId = arrSessionData[4];
			g_userId = arrSessionData[3];
			g_userStatus = true;
		}
		else
		{
			$('#project_client').val('');
			$('#project_title').val('');
			$('#project_number').val('');
			$('#project_content').val('');
			$('#project_assignedTo').val('');
			$('#project_dueDate').val('');
			
			$('#login_area').show();
			$('#login_btn').show();
			$('#logout_btn').hide();
			$('#after_login').hide();
			$('#add_project_btn').hide();
			$('#all_project_btn').hide();
			$('#created_project_btn').hide();
			$('#assigned_project_btn').hide();
			$('#new_user_btn').hide();
			$('#new_client_btn').hide();
			$('#closed_project_btn').hide();
			$('#search_btn').hide();
			$('#change_password_btn').hide();
			g_userStatus = false;
		}
		fGetProject();
	}
	// function to get user data from session
	function fGetSession()
	{
		$.ajax({
		   	type: "POST",
		   	url: "index.php/server/fGetSession",
			data: "name=" + encodeURIComponent('status'),
		   	success: function(msg){
				fCheckUser(msg.toString());
			}
		 });
	}
	// function to show clock
	$(function($) {
		var options = {
		        timeNotation: '12h',
		        am_pm: true		        
		      };
	    $('#clock_area').jclock(options);
	});
	//function to get all projects
	function fGetProject(pWhere,pCount,pSort,pWhereQuery)
	{
		$(".active_button").removeClass("active_button");
		if(pWhere == 'closed')
			blnClosed = true;
		else
			blnClosed = false;
		fGetClients();		
		fGetUsers();
		var strName = g_personId;
		var strWhere = 'all';
		var intCount = 200;
		var strSort = 'PDueDate ASC';
		var strAction = 'false';
		if(pWhere != null)
			strWhere = pWhere;
		if(pCount != null)
			intCount = pCount;
		if(pSort != null)
			strSort = pSort;
		if(g_userStatus != false)
			strAction = 'true';
		if(pWhereQuery == null)
			pWhereQuery = 'null';	
		if(strWhere == 'all')
			$("#all_project_btn").addClass("active_button");
		if(strWhere == 'created')
		      	$("#created_project_btn").addClass("active_button");
		if(strWhere == 'assigned')
			  	$("#assigned_project_btn").addClass("active_button");
		if(strWhere == 'closed')
			  	$("#closed_project_btn").addClass("active_button");
		$.ajax({
		   type: "POST",
		   url: "index.php/server/fGetProject",
		   data: "name=" + encodeURIComponent(strName) + "&where=" + encodeURIComponent(strWhere) + "&count=" + encodeURIComponent(intCount) + "&sort=" + encodeURIComponent(strSort) + "&action=" + encodeURIComponent(strAction) + "&query=" + encodeURIComponent(pWhereQuery),
		   success: function(msg){
			var strTable = fFormatProjectTable(decodeURIComponent(msg).replace(/\+/g,' ').replace(/&/g,'&amp;'));
			$('#content_area').html(strTable);
			$('#addProject_form').hide();
			fAddEvents();
			fFormatTable();
		   }
		 });
	}
	//add css style and add edit and delete buttons to each project in the html table
	function fFormatProjectTable(p_strXml)
	{
			var strHTMLTable = '<table id = "project_table"><tbody>';
			strHTMLTable += '<tr>'
			strHTMLTable += '<td>Client name</td>';  // "client"		
			strHTMLTable += '<td>Project Number</td>';  // "number"
			strHTMLTable += '<td>Project Title</td>';  // "title"
			strHTMLTable += '<td>Project Content</td>';  // "content"
			strHTMLTable += '<td>Created By</td>';  // "user"
			strHTMLTable += '<td>Assigned To</td>';  // "assigned"
			strHTMLTable += '<td>Create Date</td>';  // "create date"
			strHTMLTable += '<td>Due Date</td>';  // "duedate"
			strHTMLTable += '<td>Last Update</td>';  // "last update"
			strHTMLTable += '<td>Actions</td>';  // "action"
			strHTMLTable += '</tr>'
			var objXml = parseXML(p_strXml)
			$(objXml).find("entry").each(function(){
				strHTMLTable += '<tr>'
				strHTMLTable += '<td>' + fGetClientName($(this).children('PClient').text().replace(/&lt;/g, '>').replace(/&gt;/g, '<')) + '</td>';  // "client"		
				strHTMLTable += '<td>' + $(this).children('PNumber').text().replace(/&lt;/g, '>').replace(/&gt;/g, '<') + '</td>';  // "number"
				strHTMLTable += '<td>' + $(this).children('PTitle').text().replace(/&lt;/g, '>').replace(/&gt;/g, '<') + '</td>';  // "title"
				strHTMLTable += '<td>' + $(this).children('PContent').text().replace(/&lt;/g, '>').replace(/&gt;/g, '<') + '</td>';  // "content"
				strHTMLTable += '<td>' + fGetUserName($(this).children('PUser').text().replace(/&lt;/g, '>').replace(/&gt;/g, '<')) + '</td>';  // "user"
				strHTMLTable += '<td>' + fGetUserName($(this).children('PAssigned').text().replace(/&lt;/g, '>').replace(/&gt;/g, '<')) + '</td>';  // "assigned"
				strHTMLTable += '<td>' + $(this).children('PCreated').text().replace(/&lt;/g, '>').replace(/&gt;/g, '<') + '</td>';  // "create date"
				strHTMLTable += '<td>' + $(this).children('PDueDate').text().replace(/&lt;/g, '>').replace(/&gt;/g, '<') + '</td>';  // "duedate"
				strHTMLTable += '<td>' + $(this).children('PLastUpdate').text().replace(/&lt;/g, '>').replace(/&gt;/g, '<') + '</td>';  // "last update "
				if(g_userStatus != false)
					strHTMLTable += '<td><div><div id = edit_' + $(this).children('PAction').text().replace(/&lt;/g, '>').replace(/&gt;/g, '<') + '>Edit</div><div  id = delete_' + $(this).children('PAction').text().replace(/&lt;/g, '>').replace(/&gt;/g, '<') + '>Delete</div></div></td>';  // "action"
				else
					strHTMLTable += '<td><div>&nbsp</div></td>';
				strHTMLTable += '</tr>'
			});
			strHTMLTable += '</tbody></table>';
			return strHTMLTable ;
	}
	//function to get all usernames for autocompl function
	function fGetUsers(p_Id)
	{
		$.ajax({
		   type: "GET",
		   url: "index.php/server/fGetUser",
		   success: function(msg){
			var arrUsers = msg.split('*');
			var arrNames = new Array();
			arrUserData = new Array();
			for(var i = 0 ; i < arrUsers.length - 1; i++)
			{
				var names = arrUsers[i].split('/');
				if(names[3] != "" && names[4] != "")
				{
					arrUserData.push(new Array(names[0],names[1],names[2],names[3] + ' ' + names[4]));
				}		
			}
			if(p_Id != null)
			{
				for(var i = 0 ; i < arrUserData.length; i++)
				{
					arrNames.push(arrUserData[i][3]);
				}
				$("#" + p_Id).autocompleteArray(arrNames);
			}
			
		   }
		 });
	}
	//function to get all clients for autocompl function
	function fGetClients(p_Id)
	{
		$.ajax({
		   type: "GET",
		   url: "index.php/server/fGetClients",
		   success: function(msg){
			var arrClients = msg.split('*');
			var arrNames = new Array();
			arrClientData = new Array();
			for(var i = 0 ; i < arrClients.length - 1; i++)
			{
				var names = arrClients[i].split('/');
				if(names[1] != "")
				{
					arrClientData.push(new Array(names[0],names[1].replace(/&amp;/g,'&')));
				}		
			}
			if(p_Id != null)
			{
				for(var i = 0 ; i < arrClientData.length; i++)
				{
					arrNames.push(arrClientData[i][1].replace(/&amp;/g,'&'));
				}
				$("#" + p_Id).autocompleteArray(arrNames);
			}
		   }
		 });
	}
	// user login function
	function fLogin() 
	{ 
		$.ajax({
		   type: "POST",
		   url: "index.php/server/fLogin",
		   data: "name=" + encodeURIComponent($('#login_name').val()) + "&pass=" + encodeURIComponent($.md5($('#login_pass').val())),
		   success: function(msg){
				fMessage(msg);
		   		fGetSession();
		   }
		 });
	}
	//function to show message about operation status
	function fMessage(p_msg)
	{
		var arrData = p_msg.split('/');
		if(p_msg != '')
		{
			$('.message').show();
			$('.message').html('<h3>'+ arrData[1] +'</h3>');
			var t = setTimeout("$('.message').hide();",2000);
		}
		
	}
	// user logout function
	function fLogout() 
	{ 
		$.ajax({
		   type: "POST",
		   url: "index.php/server/fLogout",
		   data: {},    //  <- set empty data  bhat/imagination/20090505: added extra data field to correct 411 error problem in FF
		   success: function(msg){
				fMessage(msg);
				fGetSession();
		   }
		 });
	}
	// add project function
	function fAddProject()
	{
		var errors = new Array();
		var client = $('#project_client').val();
		if(client == "")
		{
			errors.push('project_client');
		}
		var title = $('#project_title').val();
		if (title == "")
		{
			errors.push('project_title');
		}
		var number = $('#project_number').val();
		if (number == "")
			number = "0000/m";
		var content = $('#project_content').val();
		var assignedTo = fGetUserId($('#project_assignedTo').val());
		if (assignedTo == "")
			assignedTo = g_personId;
		var dueDate = $('#project_dueDate').val();
		if (dueDate == "")
			dueDate = "01/01/2009";
		if (errors.length < 1)
		{
			var dbClient = fGetClientId(client);
			if(dbClient == "")
			{
				$.ajax(
				{	
				   	type: "POST",
				   	url: "index.php/server/fAddClient",
				   	data: "name=" +encodeURIComponent($('#project_client').val()) + "&address=none" + "&phone=none" + "&email=none" + "&fax=none" + "&person=none",
				   	success: function(msg)
					{
						if(msg != "")
						{
							dbClient = msg;
							$.ajax(
							{
								type: "POST",
								url: "index.php/server/fAddProject",
								data: "client=" + encodeURIComponent(dbClient) + "&title=" + encodeURIComponent(title.replace(/'/g,'`')) + "&number=" + encodeURIComponent(number) + "&content=" + encodeURIComponent(content.replace(/'/g,'`')) + "&assignedTo=" + encodeURIComponent(assignedTo) + "&dueDate=" + encodeURIComponent(dueDate),
								success: function(msg)
								{
									fMessage(msg);
									fGetProject();
								}
							});
						}
					}
				});
			}
			else
			{
				$.ajax(
				{
					type: "POST",
					url: "index.php/server/fAddProject",
					data: "client=" + encodeURIComponent(dbClient) + "&title=" + encodeURIComponent(title.replace(/'/g,'`')) + "&number=" + encodeURIComponent(number) + "&content=" + encodeURIComponent(content.replace(/'/g,'`')) + "&assignedTo=" + encodeURIComponent(assignedTo) + "&dueDate=" + encodeURIComponent(dueDate),
					success: function(msg)
					{
						fMessage(msg);
						fGetProject();
					}
				});
			}
		}
		else
		{	
			fValidate(errors);
		}		
	}
	//function to validate project client and title fields
	function fValidate(p_errors)
	{
		for(var item in p_errors)
		{
			$('#'+ p_errors[item]).css('color','red');
			$('#'+ p_errors[item]).val("Requared");
		}	
	}
	//function to get client id by client name
	function fGetClientId(p_Cname)
	{
		if(arrClientData != null)
		for(var i = 0 ; i < arrClientData.length; i++)
		{
			var names = arrClientData[i];
			if(names[1] == p_Cname)
				return (names[0]);
		}	
		return "";	
	}
	//function get client name by client id
	function fGetClientName(p_CId)
	{
		if(arrClientData != null)
		for(var i = 0 ; i < arrClientData.length; i++)
		{
			var names = arrClientData[i];
			if(names[0] == p_CId)
				return (names[1]);
		}		
	}
	//function to get user id by user First name and surname
	function fGetUserId(p_UName)
	{
		var arrNames = p_UName.split(',');
		var strNames = '';
		if(arrUserData != null)
		for(var i = 0 ; i < arrNames.length; i++)
		{
			for(var y = 0 ; y < arrUserData.length; y++)
			{
				var names = arrUserData[y];
				if(names[3] == trim(arrNames[i]))
					if(strNames != '')
						strNames += ',' + names[2];
					else
						strNames += names[2];
			}
		}
		return strNames;
	}
	//function to get user First name and Surname bu user id
	function fGetUserName(p_UId)
	{
		var arrId = p_UId.split(',');
		var strNames = '';
		if(arrUserData != null)
		for(var i = 0 ; i < arrId.length; i++)
		{
			for(var y = 0 ; y < arrUserData.length; y++)
			{
				var names = arrUserData[y];
				if(names[2] == trim(arrId[i]))
					if(strNames != '')
						strNames += ', ' + names[3];
					else
						strNames += names[3];
			}
		}
		return strNames;
	}
	//function to send edited data to codeignites via ajax.
	function fEditProjectSend(p_Id)
	{
		var errors = new Array();
		var client = $('#edit_client').val();
		if(client == "")
		{
			errors.push('edit_client');
		}
		var title = $('#edit_title').val();
		if (title == "")
		{
			errors.push('edit_title');
		}
		var number =$('#edit_number').val();
		if (number == "")
			number = "0000/m";
		var content = $('#edit_content').val();
		var assignedTo = fGetUserId($('#edit_assignedTo').val());
		if (assignedTo == "")
			assignedTo = g_personId;
		var dueDate = $('#edit_dueDate').val();
		if (dueDate == "")
			dueDate = "01/01/2009";
		var closed = $('#edit_check').attr('checked').toString();
		if (errors.length < 1)
		{
			var dbClient = fGetClientId(client);
			if(dbClient == "")
			{
				$.ajax(
				{	
				   	type: "POST",
				   	url: "index.php/server/fAddClient",
				   	data: "name=" + encodeURIComponent(client) + "&address=none" + "&phone=none" + "&email=none" + "&fax=none" + "&person=none",
				   	success: function(msg)
					{
						if(msg != "")
						{
							dbClient = msg;
							$.ajax({
							   type: "POST",
							   url: "index.php/server/fEditProjectSend",
							   data: "id=" + encodeURIComponent(p_Id) + "&client=" + encodeURIComponent(dbClient) + "&title=" + encodeURIComponent(title.replace(/'/g,'`')) + "&number=" + encodeURIComponent(number) + "&content=" + encodeURIComponent(content.replace(/'/g,'`')) + "&assignedTo=" + encodeURIComponent(assignedTo) + "&dueDate=" + encodeURIComponent(dueDate)+ "&closed=" + encodeURIComponent(closed),
							   success: function(msg){
								fMessage(msg);
								$('#edit_project').hide();
								fGetProject();
							   }
							 });
						}
					}
				});
			}
			else
			{
				$.ajax({
				   type: "POST",
				   url: "index.php/server/fEditProjectSend",
				   data: "id=" + encodeURIComponent(p_Id) + "&client=" + encodeURIComponent(dbClient) + "&title=" + encodeURIComponent(title.replace(/'/g,'`')) + "&number=" + encodeURIComponent(number) + "&content=" + encodeURIComponent(content.replace(/'/g,'`')) + "&assignedTo=" + encodeURIComponent(assignedTo) + "&dueDate=" + encodeURIComponent(dueDate)+ "&closed=" + encodeURIComponent(closed),
				   success: function(msg){
					fMessage(msg);
					$('#edit_project').hide();
					fGetProject();
				   }
				 });
			}
		}
		else
		{	
			fValidate(errors);
		}
	}
	//function add new User and send requesr to server to add user data to database.
	function fAddUser()
	{
		var name = $('#add_user_name').val();
		var fname = $('#add_user_fname').val();
		var lname = $('#add_user_lname').val();
		var email = $('#add_user_email').val();
		
		var enPass1 =  $.md5($('#add_user_pass1').val());
		var enPass2 =  $.md5($('#add_user_pass2').val());
			
		$.ajax(
		{	
		   type: "POST",
		   url: "index.php/server/fAddUser",
		   data: "name=" + encodeURIComponent(name) + "&pass1=" + encodeURIComponent(enPass1) + "&pass2=" + encodeURIComponent(enPass2) + "&fname=" + encodeURIComponent(fname) + "&lname=" + encodeURIComponent(lname) + "&email=" + encodeURIComponent(email),
		   success: function(msg){
			fMessage(msg);
			$('#add_user').hide();
		   }
		 });
	}
	//function to add new client and send request to server to add client data to database.
	function fAddClient()
	{
		var name = $('#add_client_name').val();
		var address = $('#add_client_address').val();
		var phone = $('#add_client_phone').val();
		var email = $('#add_client_email').val();
		
		var fax = $('#add_client_fax').val();
		var person = $('#add_client_person').val();
			
		$.ajax(
		{	
		   type: "POST",
		   url: "index.php/server/fAddClient",
		   data: "name=" + encodeURIComponent(name) + "&address=" + encodeURIComponent(address) + "&phone=" + encodeURIComponent(phone) + "&email=" + encodeURIComponent(email) + "&fax=" + encodeURIComponent(fax) + "&person=" + encodeURIComponent(person),
		   success: function(msg){
			fMessage(msg);
			$('#add_client').hide();
		   }
		 });
	}
	//search function
	function fSearch()
	{
		var keyword = $('#search_keyword').val();
		var client = $('#client_search').attr('checked').toString();
		var number = $('#number_search').attr('checked').toString();
		var title = $('#title_search').attr('checked').toString();
		var content = $('#content_search').attr('checked').toString();
		var closed = $('#closed_search').attr('checked').toString();
		if(closed == 'false')
			closed = '0';
		else
			closed = '1';
		var strQuery = '';
		if(keyword != "")
		{
			if(client == 'true')
			{
				if(strQuery != '')
					strQuery = strQuery + "OR CName Like '%" + keyword + "%' ";
				else
					strQuery = strQuery + "(CName Like '%" + keyword + "%' ";
			}
				
			if(number == 'true')
			{
				if(strQuery != '')
					strQuery = strQuery + "OR PNumber Like '%" + keyword + "%' ";
				else
					strQuery = strQuery + "(PNumber Like '%" + keyword + "%' ";
			}
			if(title == 'true')
			{
				if(strQuery != '')
					strQuery = strQuery + "OR PTitle Like '%" + keyword + "%' ";
				else
					strQuery = strQuery + "(PTitle Like '%" + keyword + "%' ";
			}
			if(content == 'true')
			{
				if(strQuery != '')
					strQuery = strQuery + "OR PContent Like '%" + keyword + "%' ";
				else
					strQuery = strQuery + "(PContent Like '%" + keyword + "%' ";
			}
			if(strQuery != '')
				strQuery = strQuery + ") AND PStatus = '" + closed + "' ";
			else
				strQuery = strQuery + "PContent Like '%" + closed + "%' ";
		}
		if(strQuery != '')
		{
			fGetProject('all',null,null,strQuery);
			$('#search_project').hide();
		}	
		else
		{
			alert('Please enter search keyword');
		}
	}
	//function to change the password
	function fChangePassword()
	{
		var old = $.md5($('#old_password').val());
		var new1 =  $.md5($('#new_password1').val());
		var new2 =  $.md5($('#new_password2').val());
		var user = 	g_userId;
		$.ajax(
		{	
		   type: "POST",
		   url: "index.php/server/fChangePassword",
		   data: "old=" + encodeURIComponent(old) + "&new1=" + encodeURIComponent(new1) + "&new2=" + encodeURIComponent(new2) + "&user=" + encodeURIComponent(user),
		   success: function(msg){
			fMessage(msg);
			$('#change_password').hide();
		   }
		 });
	}
	// trim whitespaces from both sides
	function trim(stringToTrim) {
		return stringToTrim.replace(/^\s+|\s+$/g,"");
	} 
	// trim whitespaces from left side
	function ltrim(stringToTrim) {
		return stringToTrim.replace(/^\s+/,"");
	}
	// trim whitespaces from right side
	function rtrim(stringToTrim) {
		return stringToTrim.replace(/\s+$/,"");
	}
</script>
</head>
<body>
	<div>
		<div id = "title_area"><h1>DC-Technology Project List</h1></div></br>
		<div id = "clock_area"></div>
		<div id = "content_area"></div>
		
		<div id = "add_project_btn">Add Project</div>
		<div id = "all_project_btn">All Project</div>
		<div id = "created_project_btn">Created By Me</div>
		<div id = "assigned_project_btn">Assigned To Me</div>
		<div id = "closed_project_btn">Closed Projects</div>
		
		<div id = "new_user_btn">Add New User</div>
		<div id = "new_client_btn">Add New Client</div>
	
		<div id = "change_password_btn">Change Password</div>
	
		<div id = "search_btn">Search</div>
		
		<div id = "login_btn" class="login">Login</div>
		<div id = "logout_btn" class="login">Logout</div>
		
		<div id = "wait_msg">Please wait...</div>
		<div id = "login_area">User name:<input type="text" name="user" id = "login_name">Password:<input type="password" name="password" id = "login_pass"></div>
		<div id = "after_login"></div>
		
		<div id = "addProject_form" class="form" style="display:none">
			Add New Project:</br>
			</br>
			Client Name:</br>
			<input type="text" id="project_client" style="width:100%" value="" TABINDEX=1/></br>
			Project Number:</br>
			<input type="text" id="project_number" style="width:100%" value="" TABINDEX=2/></br>
			Project Title:</br>
			<input type="text" id="project_title" style="width:100%" value="" TABINDEX=3/></br>
			Project Content:</br>
			<textarea id="project_content" style="width:100%; height:100px" TABINDEX=4></textarea></br>
			Assigned To:</br>
			<input type="text" id="project_assignedTo" style="width:100%" value="" TABINDEX=5/></br>
			Due Date:</br>
			<input type="text" id="project_dueDate" style="width:100%" value="" TABINDEX=6/></br></br>
			<input type="button" id="addProject_yesBtn" value="Save"/><input type="button" id="addProject_noBtn" value="Cancel"/></br>			
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
		<div id = "delete_project" class="form" style="display:none;width:150px;left:600px">
			Delete Project:</br>
			</br>
			<input type="button" id="delete_yesBtn" value="Yes" TABINDEX=1/><input type="button" id="delete_noBtn" value="No" TABINDEX=2/></br>
		</div>
		<div id = "add_user"  class="form" style="display:none">
			Add New User:</br>
			</br>
			User Name:</br>
			<input type="text" id="add_user_name" style="width:100%" value="" TABINDEX=1/></br>
			Password:</br>
			<input type="password" id="add_user_pass1" style="width:100%" value="" TABINDEX=2/></br>
			Confirm Password:</br>
			<input type="password" id="add_user_pass2" style="width:100%" value="" TABINDEX=3/></br>
			First Name:</br>
			<input type="text" id="add_user_fname" style="width:100%" value="" TABINDEX=4/></br>
			Last Name:</br>
			<input type="text" id="add_user_lname" style="width:100%" value=""TABINDEX=5 /></br>
			Email:</br>
			<input type="text" id="add_user_email" style="width:100%" value="" TABINDEX=6/></br></br>
			<input type="button" id="add_user_yesBtn" value="Save" TABINDEX=7/><input type="button" id="add_user_noBtn" value="Cancel" TABINDEX=7/></br>
		</div>
		<div id = "add_client" class="form" style="display:none">
			Add New Client:</br>
			</br>
			Client Name:</br>
			<input type="text" id="add_client_name" style="width:100%" value="" TABINDEX=1/></br>
			Client Address:</br>
			<input type="text" id="add_client_address" style="width:100%" value="" TABINDEX=2/></br>
			Client Email:</br>
			<input type="text" id="add_client_email" style="width:100%" value="" TABINDEX=3/></br>
			Client Phone:</br>
			<input type="text" id="add_client_phone" style="width:100%" value="" TABINDEX=4/></br>
			Client Fax:</br>
			<input type="text" id="add_client_fax" style="width:100%" value="" TABINDEX=5/></br>
			Client Contact Person:</br>
			<input type="text" id="add_client_person" style="width:100%" value="" TABINDEX=6/></br></br>
			<input type="button" id="add_client_yesBtn" value="Save" TABINDEX=7/><input type="button" id="add_client_noBtn" value="Cancel" TABINDEX=8/></br>
		</div>
		<div id = "search_project" class="form" style="display:none">
			Search Project:</br>
			</br>
			Keyword:</br>
			<input type="text" id="search_keyword" style="width:100%" value="" TABINDEX=1/></br>
			Where:</br>
			<input type="checkbox" id="client_search" checked TABINDEX=2> Project Client</input></br>
			<input type="checkbox" id="number_search" checked TABINDEX=3> Project Number</input></br>
			<input type="checkbox" id="title_search" checked TABINDEX=4> Project Title</input></br>
			<input type="checkbox" id="content_search" checked TABINDEX=5> Project Content</input></br></br>
			<input type="checkbox" id="closed_search" TABINDEX=6> Closed Project</input></br></br>
			<input type="button" id="search_yesBtn" value="Search" TABINDEX=7/><input type="button" id="search_noBtn" value="Cancel" TABINDEX=8 /></br>
		</div>
		<div id = "change_password" class="form" style="display:none">
			Change User Password:</br>
			</br>
			Old password:</br>
			<input type="password" id="old_password" style="width:100%" value="" TABINDEX=1/></br>
			New Password:</br>
			<input type="password" id="new_password1" style="width:100%" value="" TABINDEX=2/></br>
			Confirm new password:</br>
			<input type="password" id="new_password2" style="width:100%" value="" TABINDEX=3/></br></br>
			<input type="button" id="change_password_yesBtn" value="Save" TABINDEX=4/><input type="button" id="change_password_noBtn" value="Cancel" TABINDEX=5/></br>			
		</div>
		<div class = "message" style="display:none">
		</div>
	</div>	
</body>
</html>