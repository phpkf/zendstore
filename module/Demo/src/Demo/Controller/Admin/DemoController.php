<?php

namespace Demo\Controller\Admin;

use Base\Controller\Admin\AdminActionController;

class DemoController extends AdminActionController
{
	public function indexAction()
	{
		$viewModel = $this->getViewModel(__METHOD__);
		
		return $viewModel;
	}	
	
	public function viewAction()
	{
		$viewModel = $this->getViewModel(__METHOD__);
		
		return $viewModel;		
	}
}