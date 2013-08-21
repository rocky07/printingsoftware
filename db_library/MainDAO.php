<?php 
class MainDAO extends Database_MySql
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

	function fetchAllTemplates()
	{
		$qry	=	"SELECT * FROM `templates`";
		//$param	=	array("i",$id);
		$records	=	$this->fetchAll($qry);
		return $records;
	}
	
	function fetchTemplates($id)
	{
		$qry	=	"SELECT * FROM `templates` where id=$id";
		$param	=	array("i",$id);
		$records	=	$this->fetchAll($qry);
		return $records;
	}
	function deleteTemplate($id)
			{
				$tname	=	"template";
				$condition	=	"id=?";
				$param		=	array("i",$id);
				$this->delete($tname,$condition,$param);
			}
	
	function fetchAllPosts($start,$limit)
	{
		$qry1	=	"SELECT count(*) FROM `posts`";
		$records1	=	$this->fetchAll($qry1);
		$this->totalRecords	=	$records1[0]["count(*)"];
	
		$qry	=	"SELECT * FROM `posts`  LIMIT ?,?";
		$param	=	array("ii",$start,$limit);
		$records	=	$this->fetchAll($qry,$param);
		return $records;
	}
//saves the user posts...
	function saveAsTemplates($values)
	{
		$result	=	false;
		$validator	=	new Validator(array(
				$values["name"]=>"Name/EMPTY",
				$values["template"]=>"Name/EMPTY"
		));
		if($validator->validate())
		{
				
			$array=array(
					"name"=>$values["name"],
					"templates"=>$values["template"]
	
			);
			$type	=	"ss";
			$this->insert($array,"templates",$type);
			$result	=	true;
	
		}
		else
		{
	
			$this->setError($validator->getMessage());
		}
		return $result;
	}
	
/***bellow mentioned are previous files **********/	
	//Category
	
	function addCategories($values)
		{
		$result	=	false;
		$validator	=	new Validator(array(
												$values["catName"]=>"Name/EMPTY"
												));
		if($validator->validate())
		{
							
					$array=array(
										"cat_name"=>$values["catName"]
										
							);							
					$type	=	"s";				
					$this->insert($array,"tbl_category",$type);
					$result	=	true;
				
		}
		else
		{
		
			$this->setError($validator->getMessage());
		}
		return $result;
		
		}	
		
		
		
		
		
		function fetchCategories($start,$limit)
		{
		    $qry	=	"SELECT count(cat_id) FROM `tbl_category`";		
			$records	=	$this->fetchAll($qry);	
			$this->totalRecords	=	$records[0]["count(cat_id)"];		
		
			$qry	=	"SELECT * FROM `tbl_category` LIMIT ?,?";
			$param	=	array("ii",$start,$limit);
			$records	=	$this->fetchAll($qry,$param);					
			return $records;
		}	
		
		function deleteCategoryFromList($id)
			{
				$tname	=	"tbl_category";
				$condition	=	"cat_id=?";
				$param		=	array("i",$id);
				$this->delete($tname,$condition,$param);
			}
			
			
			
   	function fetchCategoryById($CatId)
		{
		
			$qry	=	"SELECT * FROM `tbl_category` WHERE cat_id=?";
			$param	=	array("i",$CatId);
			$records	=	$this->fetchAll($qry,$param);					
			return $records;
		}  	
		
		function updateCategories($values,$CatId)
		{
			
			$result	=	false;
			$validator	=	new Validator(array(
												$values["catName"]=>"Name/EMPTY"
												));
			if($validator->validate()){
			
					$array=array(
										"cat_name"=>$values["catName"]
										
							);							
											
				$tname	=	"tbl_category";
				$cond	 =	"cat_id=?";
				$param	=	array($CatId);
				$ty		=	"si";		
				$this->update($array,$tname,$cond,$param,$ty);
				
				$result=true;
								
		   }else
			{
				$this->setError($validator->getMessage())	;
			}
		 return $result;		
	}
	
	 function getAllCategories()
		{
		
			$qry	=	"SELECT * FROM `tbl_category` ";
			
			$records	=	$this->fetchAll($qry);					
			return $records;
		}  
	
	
	// SubCategory
	
				
		function addSubCategory($values)
		{
		$result	=	false;
		$validator	=	new Validator(array(
												$values["Category"]=>"Category/EMPTY",
												$values["SubCategory"]=>"SubCategory/EMPTY"
												));
		if($validator->validate())
		{
			
				
								
					$array=array(
										"cat_id"=>$values["Category"],
										"subcat_name"=>$values["SubCategory"]
							);							
					$type	=	"is";				
					$this->insert($array,"tbl_subcategory",$type);
					$result	=	true;
				
		}
		else
		{
		
			$this->setError($validator->getMessage());
		}
		return $result;
		
		}	
		
		function fetchSubCategories($start,$limit,$cat)	
		{
		    $qry1	=	"SELECT count(subcat_id) FROM `tbl_subcategory` where cat_id=?";	
			$paam	=	array("i",$cat);	
			$records1	=	$this->fetchAll($qry1,$paam);	
			$this->totalRecords	=	$records1[0]["count(subcat_id)"];		
		
			$qry	=	"SELECT * FROM `tbl_subcategory` where cat_id=? LIMIT ?,?";
			$param	=	array("iii",$cat,$start,$limit);
			$records	=	$this->fetchAll($qry,$param);					
			return $records;
		}	
		function fetchSubCategoriesOnNull($start,$limit)	
		{
		    $qry1	=	"SELECT count(subcat_id) FROM `tbl_subcategory`";	
			$records1	=	$this->fetchAll($qry1);	
			$this->totalRecords	=	$records1[0]["count(subcat_id)"];		
		
			$qry	=	"SELECT * FROM `tbl_subcategory`  LIMIT ?,?";
			$param	=	array("ii",$start,$limit);
			$records	=	$this->fetchAll($qry,$param);					
			return $records;
		}	
		
		
		function deleteSubCategory($scid)
			{
				$tname	=	"tbl_subcategory";
				$condition	=	"subcat_id=?";
				$param		=	array("i",$scid);
				$this->delete($tname,$condition,$param);
			}
			
		function fetchSubCategoryById($scid)
		{
		
			$qry	=	"SELECT * FROM `tbl_subcategory` WHERE subcat_id=?";
			$param	=	array("i",$scid);
			$records	=	$this->fetchAll($qry,$param);					
			return $records;
		} 
		
		function updateSubCategory($values,$scid)
		{
			
			$result	=	false;
			$validator	=	new Validator(array(
												$values["Category"]=>"Category/EMPTY",
												$values["SubCategory"]=>"SubCategory/EMPTY"
												));
			if($validator->validate())
			{
			
						
								
					$array=array(
										"cat_id"=>$values["Category"],
										"subcat_name"=>$values["SubCategory"]
							);		
				$tname	=	"tbl_subcategory";
				$cond	 =	"subcat_id=?";
				$param	=	array($scid);
				$ty		=	"isi";		
				$this->update($array,$tname,$cond,$param,$ty);
				
				$result=true;
							
		   }else
			{
				$this->setError($validator->getMessage())	;
			}
		 return $result;		
	}
	
	
	
	
	function addSubCategoryGallery($values,$imagepath)
		{
			$result	=	false;
			 
				if(!empty($imagepath)){
						$array	=	array(								
								"subcat_id"=>$values["scid"],								
								"image"=>$imagepath,
								"caption"=>$values["caption"]
							
								);	
						 $type	=	"iss";				
					     $this->insert($array,"tbl_gallery",$type);						
						$result	=	true;}else
			{
				$this->setError("enter image");
			}
				
			
			
			return $result;
		}
	
	
	
	
	
	function fetchGallery($start,$limit,$scid)
		{
		    $qry1	=	"SELECT count(gal_id) FROM `tbl_gallery` where subcat_id=?";	
			$paam	=	array("i",$scid);	
			$records	=	$this->fetchAll($qry1,$paam);	
			$this->totalRecords	=	$records[0]["count(gal_id)"];		
		
			$qry	=	"SELECT * FROM `tbl_gallery` where subcat_id=? LIMIT ?,? ";
			
			$param	=	array("iii",$scid,$start,$limit);
			$records	=	$this->fetchAll($qry,$param);					
			return $records;
		}	
		
	
	function deleteGallery($galId)
			{
			$query="SELECT image FROM `tbl_gallery` where gal_id=?";	
			$param	=	array("i",$galId);		
			$total	=	$this->fetchAll($query,$param);			
				
				$tname	=	"tbl_gallery";
				$condition	=	"gal_id=?";
				$paam		=	array("i",$galId);
				$this->delete($tname,$condition,$paam);	
			
			return $total[0]["image"];	
			
			}
	
	function fetchGalleryById($galId)
		{
		
			$qry	=	"SELECT * FROM `tbl_gallery` WHERE gal_id=?";
			$param	=	array("i",$galId);
			$records	=	$this->fetchAll($qry,$param);					
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
