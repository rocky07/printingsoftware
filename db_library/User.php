<?php 
class User extends Database_MySql
{
	public $errorMessage;
	public $userId;
	private $utilObject;

	function __construct()
	{
		parent::__construct();
		$this->connect();
		$this->userId	=	null;
		$this->utilObject	=	new Utilities();
		
	}
	

		
	/*
	 * to set user_id for each page
	 * author: Rekha
	 */
	function setUserId($id)
	{
		 $this->userId	=	$id;
	}
	
	
	
	function updateAdminAccount($values,$userId)
	{
		$result	=	false;
		$validator	=	new Validator(array(
												$values["txtUsername"]=>"/EMPTY",											
												$values["txtFullname"]=>"/EMPTY"));
		if($validator->validate())
		{
			
				$val	=	array("staff_name"=>$values["txtFullname"],"staff_username"=>$values["txtUsername"]);
				$tname	=	"tbl_staff";
				$cond	=	"staff_id=?";
				$param	=	array($userId);
				$ty		=	"ssi";
				$this->update($val,$tname,$cond,$param,$ty);
					
		}
		else
		{
			$this->setError($validator->getMessage())	;
		}
		return $result;
	
	}
	
	function updatePassword($values,$userId)
	{
		$result	=	false;
		$validator	=	new Validator(array(
												$values["txtCurrentPassword"]=>"/EMPTY",												
												$values["txtNewPassword"]=>"/EMPTY"));
		if($validator->validate())
		{
			$oldpassword	=	md5($values["txtCurrentPassword"]);		
			$newpassword	=	md5($values["txtNewPassword"]);					
			
				$val	=	array("staff_password"=>$newpassword);
				$tname	=	"tbl_staff";
				$cond	=	"staff_id=? and staff_password=?";
				$param	=	array($userId,$oldpassword);
				$ty		=	"sis";
				if(!$this->update($val,$tname,$cond,$param,$ty)){
					$this->setError("Old Password doesn't match");
				}
		}
		else
		{
			$this->setError($validator->getMessage())	;
		}
		return $result;
	
	}
	
	//Staff
	
	function addStaff($values)
		{
		$result	=	false;
		$util	=	new Utilities();
		$validator	=	new Validator(array(
												$values["txtName"]=>"Title/EMPTY",							
												$values["txtEmail"]=>"Title/EMPTY|EMAIL",
												$values["txtusername"]=>"Title/EMPTY",
												$values["txtPass"]=>"Title/EMPTY"
												));
		if($validator->validate())
		{
			
				if(!$this->userExist($values["txtusername"])){				
					$password	=	md5($values["txtPass"]);				
					$array=array(
							"staff_name"=>$values["txtName"],
							"staff_ofc_id"=>$values["txtofcid"],
							"staff_email"=>$values["txtEmail"],		
							"staff_username"=>$values["txtusername"],
							"staff_password"=>$password,						
							"staff_role_id"=>2							
							);							
					$type	=	"sssssi";				
					$this->insert($array,"tbl_staff",$type);
					$result	=	true;
				}else{
					$this->setError("User Already Exist")	;
				}
		}
		else
		{
			$this->setError($validator->getMessage())	;
		}
		return $result;
		
		}
		
		
	function userExist($username)
		{
			$qry	=	"SELECT * FROM `tbl_staff`  where staff_username=?";
			$param	=	array("s",$username);
			$records	=	$this->fetchAll($qry,$param);
			if(count($records)>0){
				$result	=	true;
			}			
			return $result;
		}
	
	
		
			
		function fetchStaff($start,$limit)
		{
		    $qry	=	"SELECT count(staff_id) FROM `tbl_staff` where staff_role_id=2";		
			$records	=	$this->fetchAll($qry);	
			$this->totalRecords	=	$records[0]["count(staff_id)"];		
		
			$qry	=	"SELECT * FROM `tbl_staff` where staff_role_id=2 LIMIT ?,?";
			$param	=	array("ii",$start,$limit);
			$records	=	$this->fetchAll($qry,$param);					
			return $records;
		}	
		
		 function delStaff($id)
			{
				$tname	=	"tbl_staff";
				$condition	=	"staff_id=?";
				$param		=	array("i",$id);
				$this->delete($tname,$condition,$param);
			}	
			
		function getUserInfo($id)
			{
				$qry	=	"SELECT * FROM `tbl_staff` where staff_id=?";		
				$param	=	array("i",$id);
			    $records	=	$this->fetchAll($qry,$param);			
				return $records;
			}	
			
			
		function editStaff($values,$id)
			{
			
			$result= false;
			
			$validator	=	new Validator(array(
												$values["txtName"]=>"Title/EMPTY",							
												$values["txtEmail"]=>"Title/EMPTY|EMAIL",
												$values["txtusername"]=>"Title/EMPTY",
												$values["txtPass"]=>"Title/EMPTY"
												));
			if($validator->validate())
		{
			
				if(!$this->userExist2($values["txtusername"],$id)){				
				   $password	=	md5($values["txtPass"]);		
					$array=array(
							"staff_name"=>$values["txtName"],
							"staff_ofc_id"=>$values["txtofcid"],
							"staff_email"=>$values["txtEmail"],		
							"staff_username"=>$values["txtusername"],
							"staff_password"=>$password,						
							"staff_role_id"=>2							
							);							
				$tname	=	"tbl_staff";
				$cond	 =	"staff_id=?";
				$param	=	array($id);
				$ty		=	"sssssii";		
				$this->update($array,$tname,$cond,$param,$ty);
				
				$result=true;
				}else{
					$this->setError("User Already Exist")	;
				}				
		   }else
			{
				$this->setError($validator->getMessage())	;
			}
		 return $result;		
	}
	
	function userExist2($username,$id)
		{
		
			$qry	=	"SELECT * FROM `tbl_staff` where staff_username=? and staff_id !=?";
			$param	=	array("si",$username,$id);
			$records	=	$this->fetchAll($qry,$param);
			if(count($records)>0){
				$result	=	true;
			
			}			
			return $result;
		}
	
	function checkUserLogin($values)
	{
	
		$result		=	false;
		$validator	=	new Validator(array($values["txtUser"]=>"/EMPTY",$values["txtPassword"]=>"/EMPTY"));
		if($validator->validate())
		{
			$records		=	"";
			$username		=	$values["txtUser"];
			$password		=	md5($values["txtPassword"]);
			$querry			=	"SELECT staff_id FROM `tbl_staff`  WHERE `staff_username`=?";//check user name exist
			$param			=	array("s",$username);
			$records		=	$this->fetchAll($querry,$param);
									
			if(!empty($records))
			{
				$det_rec	=	array();
			    $querry			=	"SELECT * FROM `tbl_staff`  WHERE `staff_id`=? and staff_password=?";//check username and password
				$param			=	array("is",$records[0]["staff_id"],$password);
				$det_rec		=	$this->fetchAll($querry,$param);
							
				if(!empty($det_rec))
				{
					$result		=	true;
					$this->userId	=	$det_rec[0]["staff_id"];
					$this->userName	=	$det_rec[0]["staff_name"];
					$this->userRoleId    =   $det_rec[0]["staff_role_id"];
					
				}					
			}
			if(!$result)
			{
				$this->setError("Invalid username or password ");	
			}
		}
		else
		{
			$this->setError("Enter mandatory fields !");
		}		
		return $result;
	}
	
	
	/*function updatePassword($values,$userId)
	{
		$result	=	false;
		$validator	=	new Validator(array(
												$values["txtCurrentPassword"]=>"/EMPTY",												
												$values["txtNewPassword"]=>"/EMPTY"));
		if($validator->validate())
		{
			 $oldpassword	=	md5($values["txtCurrentPassword"]);		
			 $newpassword	=	md5($values["txtNewPassword"]);					
				$val	=	array("password"=>$newpassword);
				$tname	=	"tbl_users";
				$cond	=	"user_id=? and password=?";
				$param	=	array($userId,$oldpassword);
				$ty		=	"sis";
				if(!$this->update($val,$tname,$cond,$param,$ty)){
					$this->setError("Old Password doesn't match");
				}
			$result = true;
		}
		else
		{
			$this->setError($validator->getMessage())	;
		}
		return $result;
	
	}*/

		
}?>