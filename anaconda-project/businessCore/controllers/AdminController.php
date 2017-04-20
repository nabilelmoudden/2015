<?php

class AdminController extends Controller
{
	public function actionIndex()
	{
		$AP	= \Business\User::load(1);
		$AP2	= new  \Business\User( 'search' );  
		
		$this->render( '//admin/index', array( 'AP' => $AP ,'AP2' => $AP2 ) );
		
	}

	public function actionSession()
	{
		$this->render('session');
	}

	public function actionTest()
	{ 
		$this->render('test');
	}

	
}