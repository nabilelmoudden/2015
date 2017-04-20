<?php  
namespace Business;
class AllUsers extends \AllUsers //AbstractProduct  //AbstractProduct 
    //implements Interface_Camp
{		
	public $id;
	public $email;
	public $civility;
	public $firstName;
	public $lastName;
	public $creationDate;
	public $savToMonitor;
	public $version;
	
	public function init(){
		parent::init();
	}
	
	/**
	 * Decode les valeurs additionnels apres recuperation en DB
	 * @return boolean
	 */
	
	public function setUser_V1(User_V1 $user_V1){
        $this->user_V1 = $user_V1;
    }
    
    public function getUser_V1(){
        return $this->user_V1;
    }
    
    public function setUser(User $user){
    	$this->user = $user;
    }
    
    public function getUser(){
    	return $this->user;
    }
    
	public function search( $order = false, $pageSize = 10 , $emailUser = '',$customProfil = null){
		
		//Test if is the first loading of page customerProfile
		if($customProfil == 1)
		{
			$Provider = parent::search($customProfil);
		}
		else
		{
			$Provider = parent::search($emailUser);
		}
		
		if( $pageSize == false )
			$Provider->setPagination( false );
		else
			$Provider->pagination->pageSize = $Provider->pagination->pageSize;
		if( $order != false )
			$Provider->criteria->order = $order; 

		return $Provider;
	} 
    
}
?>  