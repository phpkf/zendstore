<?php

namespace Catalog\Controller\Admin;

use ZendStore\Controller\AbstractAdminActionController;
use Catalog\Model\CategoryTable;
use Catalog\Form\CategoryForm;

class CategoryController extends AbstractAdminActionController
{	
	/**
	 * @var CategoryTable
	 */
	protected $categoryTable;
	
	public function indexAction()
	{
		$viewModel = $this->getViewModel();
		
		return $viewModel;
	}	
	
// 	public function addAction()
// 	{
// 		return $this->forward()->dispatch('catalog-admin-category', array(
// 			'action' => 'edit',
// 			'forwardedRouteName' => 'catalog-admin-category'));		
// 	}
	
	public function editAction()
	{
		$viewModel = $this->getViewModel();
		$viewModel->setTerminal(true);
		
		$form = new CategoryForm();
		
		$viewVars = array(
			'form' => $form,	
		);	
		$viewModel->setVariables($viewVars);
			
		return $viewModel;		
	}
	
	/**
	 * List children and encode it with json
	 */
	public function listAction()
	{
		$request = $this->getRequest();
		$id = $_REQUEST['id'];
		$result = array();
		try {
			$categories = $this->getCategoryTable()->getCategoryChildren($id);
			foreach ($categories as $category) {
				switch ($category->type) {
					case 1:
						$type = 'folder';
						break;
					default:
						$type = 'folder';
						break;
				}
				$result[] = array(
					'attr' => array('id' => "node_{$category->category_id}", 'rel' => $type),
					'data' => $category->category_name,	
					'state' => 'closed',
				);
			}
		} catch (\Catalog\Model\Exception\UnexpectedValueException $e) {}
		
		header("HTTP/1.0 200 OK");
		header('Content-type: application/json; charset=utf-8');
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Pragma: no-cache");
		echo json_encode($result);
		exit;
	}	
	
	/**
	 * @return CategoryTable
	 */
	public function getCategoryTable()
	{
		if (!$this->categoryTable) {
			$sm = $this->getServiceLocator();
			$this->categoryTable = $sm->get('category-table');
		}
		return $this->categoryTable;
	}

}