<?php
class Server extends Controller {
	function Server()
	{
		parent::Controller();
	}
	//function to get data from session and send response to view.
	function fGetSession()
	{
		$name = strtolower(urldecode($_POST["name"]));
		if ($name == 'status')
		{
			if($this->phpsession->get('logged_in') == true)
			{
				$nameName = $this->phpsession->get('userName');
				$time = $this->phpsession->get('login_time');
				$user = $this->phpsession->get('user');
				$person = $this->phpsession->get('personId');
				echo ('1/'. $nameName . '/' . $time . '/' . $user . '/' . $person);
			}
			else
				echo ('0/');
		}
			
	}
	//function to check login data and add user data to the sesion, response just errors about wrong password or user name
	function fLogin()
	{
		$user = strtolower(urldecode($_POST["name"]));
		$pass = strtolower(urldecode($_POST["pass"]));
		if ($user != "" && $pass != "")
		{
			$query = $this->db->query("SELECT user.UId, user.UName, person.HId AS UFId, person.HFName AS UFName, person.HLName AS ULName FROM user LEFT JOIN person ON user.UPerson = person.HId Where user.UName =  '" .$user. "' and user.UPass = '" .$pass. "' LIMIT 0 , 1");
			$haveEntry = false;
			foreach ($query->result_array() as $row)
			{
				$time = date(DATE_ATOM, mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));
			   	$this->phpsession->save('logged_in',true);
				$this->phpsession->save('userName',$row['UFName'] . ' ' . $row['ULName']);
				$this->phpsession->save('user',$row['UId']);
				$this->phpsession->save('login_time',$time);
				$this->phpsession->save('personId',$row['UFId']);
				$lastLogin = $this->db->query("UPDATE user SET ULastLogin = '" . $time . "' WHERE user.UName = '" .$user. "'");
				$haveEntry = true;
				echo ('1/Login succesfull');
			}
			if($haveEntry == false)
			{
				echo ('0/Wrong login name or password');
			}
		}
		else
		{
			echo ('0/Please enter your login name and password');
		}
	}
	//function to logout the system and delete user data from browser session.
	function fLogout()
	{
		$this->phpsession->clear();
		echo ('1/Thank you');
	}
	//function add project data to data base and response to viesw the status of operation or errors .
	function fAddProject()
	{
		$client = $_POST["client"];
		$title = $_POST["title"];
		$number = $_POST["number"];
		$content = $_POST["content"];
		$assignedTo = $_POST["assignedTo"];
		list($month, $day, $year) = split('/', urldecode($_POST["dueDate"]), 3);
		$data = array(
						'PNumber' => $number,
		               	'PTitle' => $title,
		               	'PContent' => $content,
					   	'PCreated' => date(DATE_ATOM, mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"))),
		               	'PUser' => $this->phpsession->get('personId'),
						'PEditPerson' => $this->phpsession->get('personId'),
		               	'PDueDate' => date(DATE_ATOM, mktime('0', '0', '0', $month, $day, $year)),
		 			   	'PLastUpdate' => date(DATE_ATOM, mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")))
		            );
		$this->db->insert('projects', $data);
		$insertId = $this->db->insert_id();
		$data = array(
		               'PAction' => $insertId
		            );
		$arrUsers = explode(",", $assignedTo);
		foreach($arrUsers as $item)
		{
			$this->db->insert('pro_per', array('PPPerson' => $item,'PPProject' => $insertId ));
		}		
		$this->db->insert('pro_cli', array('PCClient' => $client,'PCProject' => $insertId ));
		$this->db->where('PId', $insertId);
		$this->db->update('projects', $data);
		echo ('1/New project successfully added');
	}
	//function to get all information about users for autocompletion function.
	function fGetUser()
	{
		$query = $this->db->query("SELECT user.UId,user.UName,person.HId as UPId,person.HFName as UFName,person.HLName as ULName from user left join person on user.UPerson = person.HId");
		$strData = "";
		foreach ($query->result() as $row)
		{
		   	$strData .= $row->UId;
			$strData .= '/';
		   	$strData .= $row->UName;
			$strData .= '/';
			$strData .= $row->UPId;
			$strData .= '/';
		   	$strData .= $row->UFName;
			$strData .= '/';
			$strData .= $row->ULName;
			$strData .= '*';
		}
		echo ($strData);
	}
	//function to get all information about clients for autocompletion function.
	function fGetClients()
	{
		$query = $this->db->query("SELECT clients.CName,clients.CId from clients");
		$strData = "";
		foreach ($query->result() as $row)
		{
		   	$strData .= $row->CId;
			$strData .= '/';
		   	$strData .= $row->CName;
			$strData .= '*';
		}
		echo ($strData);
	}
	//get data from database about projects in database
	function fGetProject()
	{
		$name = urldecode($_POST["name"]);
		$where = urldecode($_POST["where"]);
		$count = urldecode($_POST["count"]);
		$sort = urldecode($_POST["sort"]);
		$action = urldecode($_POST["action"]);
		$query = urldecode($_POST["query"]);
		if(!$count) $count = '100';
		if(!$sort) $sort = 'PId DESC';
		$fields = "A.PId, A.PNumber, A.PTitle, A.PContent, A.PUser, A.PCreated, A.PDueDate, A.PLastUpdate, A.PAction, B.PPPerson, E.CId, E.CName FROM projects A left join pro_per B on B.PPProject = A.PId left join person C on B.PPPerson = C.HId left join pro_cli D on D.PCProject = A.PId left join clients E on D.PCClient = E.CId ";
		$whereQuery = "Where ";
		if($query == "null")
		{
			if($where == 'all')
			{
				$whereQuery .= "PStatus = '0'";
			}
			if($where == 'created')
			{
				$whereQuery .= "PStatus = '0' AND PUser Like '" . $name . "'";
			}
			if($where == 'assigned')
			{
				$whereQuery .= "PStatus = '0' AND PPPerson Like '" . $name . "'";
			}
			if($where == 'closed')
			{
				$whereQuery .= "PStatus = '1'";
			}
		}
		else
		{
			$whereQuery .= $query;
		}
		$s = "SELECT " . $fields . $whereQuery . " ORDER BY " . $sort . " LIMIT 0, " . $count;
		$query = $this->db->query($s);
		$strXml = '<?xml version="1.0" encoding="ISO-8859-1"?>';
		$strXml .= '<root>';
		$strAssigned = "";
		$lastId = "";
		foreach ($query->result() as $row)
		{
			if($lastId != $row->PId)
			{
				$strXml = str_replace('[strAssigned]', $strAssigned, $strXml);
				$strAssigned = "";
				$strXml .= '<entry>';
				$strXml .= '<PClient>'. $row->CId . '</PClient>';
				$strXml .= '<PNumber>'. $row->PNumber . '</PNumber>';
				$strXml .= '<PTitle>'. $row->PTitle . '</PTitle>';
				$strXml .= '<PContent>'. $row->PContent . '</PContent>';
				$strXml .= '<PUser>'. $row->PUser . '</PUser>';
				$strXml .= '<PAssigned>[strAssigned]</PAssigned>';
				$strXml .= '<PCreated>'. $row->PCreated . '</PCreated>';
				$strXml .= '<PDueDate>'. $row->PDueDate . '</PDueDate>';
				$strXml .= '<PLastUpdate>'. $row->PLastUpdate . '</PLastUpdate>';
				if($action == 'true')
					$strXml .= '<PAction>'. $row->PId . '</PAction>';			
				$strXml .= '</entry>';
				$lastId = $row->PId;
				$strAssigned .= $row->PPPerson;
			}
			else
			{
				if($row->PPPerson)
						$strAssigned .= ", " . $row->PPPerson;				
			}
		}
		$strXml .= '</root>';
		echo urlencode(str_replace('[strAssigned]', $strAssigned, $strXml));
	}
	// get data about the priject with you want to edit
	function fEditProject()
	{
		$id = urldecode($_POST["id"]);
		$fields = "A.PId, A.PNumber, A.PTitle, A.PContent, A.PDueDate, A.PStatus, CONCAT(C.HFName,' ', C.HLName) as PPPerson, E.CId, E.CName FROM projects A left join pro_per B on B.PPProject = A.PId left join person C on B.PPPerson = C.HId left join pro_cli D on D.PCProject = A.PId left join clients E on D.PCClient = E.CId ";
		$s = "SELECT " . $fields . "WHERE A.PId = '" . $id . "'";
		$query = $this->db->query($s);
		$strXml = '<?xml version="1.0" encoding="ISO-8859-1"?>';
		$strXml .= '<root>';
		$strFirst = true;
		$strAssigned = "";
		foreach ($query->result() as $row)
		{
			if ($strFirst != false)
			{
				$strXml .= '<entry>';
				$strXml .= '<PClient>'. $row->CName . '</PClient>';
				$strXml .= '<PNumber>'. $row->PNumber . '</PNumber>';
				$strXml .= '<PTitle>'. $row->PTitle . '</PTitle>';
				$strXml .= '<PContent>'. $row->PContent . '</PContent>';
				$strXml .= '<PAssigned>[strAssigned]</PAssigned>';
				$strXml .= '<PDueDate>'. $row->PDueDate . '</PDueDate>';
				$strXml .= '<PStatus>'. $row->PStatus . '</PStatus>';
				$strXml .= '</entry>';
				$strAssigned .= $row->PPPerson; 
				$strFirst = false;
			}
			else
			{
				if($row->PPPerson)
					$strAssigned .= ", " . $row->PPPerson; 
			}
			
		}
		$strXml .= '</root>';
		echo urlencode(str_replace('[strAssigned]', $strAssigned, $strXml));
	}
	//send data to database to edit the project.
	function fEditProjectSend()
	{
		$client = urldecode($_POST["client"]);
		$title = urldecode($_POST["title"]);
		$number = urldecode($_POST["number"]);
		$content = urldecode($_POST["content"]);
		$assignedTo = urldecode($_POST["assignedTo"]);
		list($month, $day, $year) = split('/', urldecode($_POST["dueDate"]), 3);
		$id = urldecode($_POST["id"]);
		$closed = urldecode($_POST["closed"]);
		if($closed == 'true' || $closed == '1')
			$closed = '1';
		else
			$closed = '0';
		$date = date(DATE_ATOM, mktime('0', '0', '0', $month, $day, $year));
		$update = date(DATE_ATOM, mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));
		list($DueDate) = split('[T]', (string)$date);
		list($LastUpdate) = split('[+]', (string)$update);
		
		$this->db->where('PCProject', $id);
		$this->db->delete('pro_cli');
		$this->db->where('PPProject', $id);
		$this->db->delete('pro_per');
		
		$arrUsers = explode(",", $assignedTo);
		foreach($arrUsers as $item)
		{
			$this->db->insert('pro_per', array('PPPerson' => $item,'PPProject' => $id ));
		}		
		$this->db->insert('pro_cli', array('PCClient' => $client,'PCProject' => $id ));
		
		$query = "UPDATE projects SET PNumber = '" . $number . "', PTitle = '" . $title . "', PContent = '" . $content . "', PDueDate = '" . $DueDate . "', PEditPerson = " . (int)$this->phpsession->get('personId') . ", PLastUpdate = '" . $LastUpdate . "', PStatus = " . (int)$closed . " WHERE PId = " . $id;
		$answer = $this->db->query($query);
		echo ('1/Project successfully edited');
	}
	//remove the project form database
	function fRemoveProject()
	{
		$id = urldecode($_POST["id"]);
		$this->db->where('PId', $id);
		$this->db->delete('projects');
		$this->db->where('PCProject', $id);
		$this->db->delete('pro_cli');
		$this->db->where('PPProject', $id);
		$this->db->delete('pro_per');
	}
	//function to add user and Person information to database
	function fAddUser()
	{	
		$name = urldecode($_POST["name"]);
		$pass1 = urldecode($_POST["pass1"]);
		$pass2 = urldecode($_POST["pass2"]);
		$fname = urldecode($_POST["fname"]);
		$lname = urldecode($_POST["lname"]);
		$email = urldecode($_POST["email"]);
		if($pass1 == $pass2)
		{
			$data1 = array(
							'HFName' => $fname ,
			               	'HLName' => $lname ,
			               	'HEmail' => $email 
			            );
			$this->db->insert('person', $data1);

			$data2 = array(
							'UName' => $name ,
			               	'UPass' => $pass1 ,
			               	'UPerson' => $this->db->insert_id(),
			 				'UCreated' => date(DATE_ATOM, mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"))) ,
			               	'ULastLogin' => date(DATE_ATOM, mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")))
			            );
			$this->db->insert('user', $data2);
			//$this->fSendEmail($email,'Welcome to Project List','Dear '. $fname . ' ' . $lname . '<br /><br /> Your login details: <br /> User Name: ' . $name . '<br /> Password: ' . $pass1 . '<br /> Link: http://192.168.76.95:8888/projectlist/ <br /><br /> Best regards <br /> Developer team');
			echo ('1/New User successfully added');
		}
		else
		{
			echo ('O/Sorry passwords doesn\'t mach');
		}
	}
	//function to add client data to database
	function fAddClient()
	{
		$name = urldecode($_POST["name"]);
		$address = urldecode($_POST["address"]);
		$phone = urldecode($_POST["phone"]);
		$email = urldecode($_POST["email"]);
		$fax = urldecode($_POST["fax"]);
		$person = urldecode($_POST["person"]);
		$data = array(
						'CName' => $name ,
		               	'CAddress' => $address ,
		               	'CEmail' => $email,
						'CPhone' => $phone ,
		               	'CFax' => $fax ,
		               	'CPerson' => $person 
		            );
		$this->db->insert('clients', $data);
		echo ('1/New Client successfully added');
	}
	// function to change password in database
	function fChangePassword()
	{
		$old = urldecode($_POST["old"]);
		$new1 = urldecode($_POST["new1"]);
		$new2 = urldecode($_POST["new2"]);
		$user = urldecode($_POST["user"]);
		if($new1 == $new2)
		{
			$data = array(
			               'UPass' => $new1
			            );
			$this->db->where('UId', $user);
			$this->db->update('user', $data);
			echo ('1/Password successfully changed');
		}
		else
		{
			echo ('0/Sorry passwords doesn\'t mach');
		}
	}
	//function to get data about project for reminders.
	function fShowProject($id,$action)
	{
		$query = "SELECT A.PId, A.PNumber, A.PTitle, A.PContent, A.PUser, A.PCreated, A.PDueDate, A.PLastUpdate, A.PStatus, B.PPPerson, C.HFName, C.HLName, E.CId, E.CName FROM projects A left join pro_per B on B.PPProject = A.PId left join person C on B.PPPerson = C.HId left join pro_cli D on D.PCProject = A.PId left join clients E on D.PCClient = E.CId WHERE PId = " . $id;
		$rows = $this->db->query($query);
		$data['title'] = 'Project -> Number ->'. $id;
		foreach ($rows->result() as $row)
		{
			$data['p_id'] = $row->PId;
			$data['p_title'] = $row->PTitle;
			$data['p_number'] = $row->PNumber;
			$data['p_content'] = $row->PContent;
			$data['p_user'] = $row->PUser;
			$data['p_created'] = $row->PCreated;
			$data['p_duedate'] = $row->PDueDate;
			$data['p_lastupdate'] = $row->PLastUpdate;
			$data['p_status'] = $row->PStatus;
			$data['p_person'] = $row->HFName . ' ' . $row->HLName;
			$data['p_client'] = $row->CName;
		}
		$data['action'] = $action;
		$this->load->view('vProject',$data);
	}
}
?>