<?php 
class UserIndec extends Database_MySql
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

	//Category
	
	
	 
		
	
	
	
	
	function fetchGallery()
		{
		    		
			$qry	=	"SELECT * FROM `tbl_gallery` ";		
			$records	=	$this->fetchAll($qry);					
			return $records;
		}	
		
	function fetchClientsOngoing()
		{
		    		
			$qry	=	"SELECT * FROM `tbl_clients` where type_id=1 ";		
			$records	=	$this->fetchAll($qry);					
			return $records;
		}
	function fetchClientsclientsCompleted()
		{
		    		
			$qry	=	"SELECT * FROM `tbl_clients` where type_id=2 ";		
			$records	=	$this->fetchAll($qry);					
			return $records;
		}		
	
	
	function fetchImage($galId)
		{
		
			$qry	=	"SELECT * FROM `tbl_gallery` WHERE gal_id=?";
			$param	=	array("i",$galId);
			$records	=	$this->fetchAll($qry,$param);	
			print_r($records);exit;				
			return $records;
		} 
		
	function getPrevImage($galId)
			{			
				$query="SELECT image FROM `tbl_gallery` where gal_id=?";		
				$param	=	array("i",$galId);	
				$records	=	$this->fetchAll($query,$param);		
				return $records[0]["image"];
			}
	
	 function updateGallery($values,$galId,$imagepath2)
		{
			$result	=	false;
			
			
				if(!empty($imagepath2)){
											
							$array	=	array(								
								"caption"=>$values["caption"],								
								"image"=>$imagepath2
								
								);	
							$type	=	"ssi";						
							
				$tname	=	"tbl_gallery";
				$cond	=	"gal_id=?";
				$param	=	array($galId);
				
				$this->update($array,$tname,$cond,$param,$type);
				$result	=	true;
				}else{
						$this->setError("enter  image");
					}
				
			
			return $result;								
							
		}
		
	//clients		
		
	
		function addClients($values)
		{
		$result	=	false;
		$validator	=	new Validator(array(
												$values["Type"]=>"Category/EMPTY",
												$values["Client"]=>"SubCategory/EMPTY"
												));
		if($validator->validate())
		{
			
				
								
					$array=array(
										"type_id"=>$values["Type"],
										"client_name"=>$values["Client"]
							);							
					$type	=	"is";				
					$this->insert($array,"tbl_clients",$type);
					$result	=	true;
				
		}
		else
		{
		
			$this->setError($validator->getMessage());
		}
		return $result;
		
		}	
		
		
		
		function fetchClientsOnNull($start,$limit)	
		{
		    $qry1	=	"SELECT count(client_id) FROM `tbl_clients`";	
			$records1	=	$this->fetchAll($qry1);	
			$this->totalRecords	=	$records1[0]["count(client_id)"];		
		
			$qry	=	"SELECT * FROM `tbl_clients`  LIMIT ?,?";
			$param	=	array("ii",$start,$limit);
			$records	=	$this->fetchAll($qry,$param);					
			return $records;
		}	
		
		
		function fetchClients($start,$limit,$Type)	
		{
		    $qry1	=	"SELECT count(client_id) FROM `tbl_clients` where type_id=?";	
			$paam	=	array("i",$Type);	
			$records1	=	$this->fetchAll($qry1,$paam);	
			$this->totalRecords	=	$records1[0]["count(subcat_id)"];		
		
			$qry	=	"SELECT * FROM `tbl_clients` where type_id=? LIMIT ?,?";
			$param	=	array("iii",$Type,$start,$limit);
			$records	=	$this->fetchAll($qry,$param);					
			return $records;
		}	
		
		
		function deleteClient($clid)
			{
				$tname	=	"tbl_clients";
				$condition	=	"client_id=?";
				$param		=	array("i",$clid);
				$this->delete($tname,$condition,$param);
			}
			
			
		function fetchClientById($clid)
		{
		
			$qry	=	"SELECT * FROM `tbl_clients` WHERE client_id=?";
			$param	=	array("i",$clid);
			$records	=	$this->fetchAll($qry,$param);					
			return $records;
		} 
		
		
	function updateClients($values,$clid)
		{
			
			$result	=	false;
			$validator	=	new Validator(array(
												$values["Type"]=>"Category/EMPTY",
												$values["Client"]=>"SubCategory/EMPTY"
												));
			if($validator->validate())
			{
			
						
								
					$array=array(
										"type_id"=>$values["Type"],
										"client_name"=>$values["Client"]
							);		
				$tname	=	"tbl_clients";
				$cond	 =	"client_id=?";
				$param	=	array($clid);
				$ty		=	"isi";		
				$this->update($array,$tname,$cond,$param,$ty);
				
				$result=true;
							
		   }else
			{
				$this->setError($validator->getMessage())	;
			}
		 return $result;		
	}
	
	
}?>