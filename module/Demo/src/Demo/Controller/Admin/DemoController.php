<?php

namespace Demo\Controller\Admin;

use ZendStore\Controller\AdminActionController;

class DemoController extends AdminActionController
{
	public function indexAction()
	{
		$viewModel = $this->getViewModel();
		
		return $viewModel;
	}	
	
	public function viewAction()
	{
		$viewModel = $this->getViewModel();
		
		return $viewModel;		
	}
}
