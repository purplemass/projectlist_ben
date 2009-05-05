<?php

class CronReminder extends Controller {
    
    function CronReminder()    {
        parent::Controller();
    }
    
	function fSendEmail()
	{
		$urgent = $this->fGetProfects();
		$this->load->library('email');
		$from = 'benas.brazdziunas@imagination.com';
		$this->email->from($from, 'Project List © Imagination');
		foreach($urgent as $item)
		{
			$this->email->to($item['email']);
			$this->email->subject("Projet time is ending");
			$this->email->message("<html><head><title>Project time is ending</title></head><body><div><h2>Dear ".$item['name']." </h2><h3>Project \"".$item['projectTitle']."\" time is ending please visit ProjectList for more information:</h3><a href='".base_url()."index.php/server/fShowProject/".$item['projectId']."/show'>Click here</a><h3>Thanks !</h3></div></body></html>");
			$this->email->send();
		}
	}
	function fGetProfects()
	{
		$query = $this->db->query("SELECT person.HFName, person.HLName, person.HEmail, projects.PId, projects.PTitle,projects.PDueDate FROM person LEFT JOIN pro_per ON person.HId = pro_per.PPPerson left join projects on pro_per.PPProject = projects.PId Where DATEDIFF(PDueDate, NOW())  =  2");
		$arrReminders = array();
		foreach ($query->result_array() as $row)
		{
			$arrReminders[] = array('email' => $row['HEmail'], 'name' => $row['HFName'].' '.$row['HLName'], 'projectId' => $row['PId'], 'projectTitle' => $row['PTitle'], 'duedate' => $row['PDueDate']);
		}
		return $arrReminders;
	}
}