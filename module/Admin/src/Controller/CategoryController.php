<?php

namespace Admin\Controller;

use Admin\Model\CategoryTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Admin\Form\CategoryForm;
use Admin\Model\Category;

class CategoryController extends AbstractActionController
{
	  // Add this property:
    private $categoryTable;
	
	public function onDispatch(MvcEvent $e) {
		
		if(isset($_SESSION['admin']) && !empty($_SESSION['admin'])) {
		
			$this->admin = unserialize($_SESSION['admin']);
			
		} else {
			
			return $this->redirect()->toRoute('admin_login');
			
		}
		
		$response = parent::onDispatch($e);
		
		$this->layout()->setTemplate('layout/layout-admin.phtml');
		
		return $response;
		
	}

    // Add this constructor:
    public function __construct(CategoryTable $categoryTable)
    {
        $this->categoryTable = $categoryTable;
    }

    public function indexAction()
    {
		 return new ViewModel([
            'categorys' => $this->categoryTable->fetchAll(),
        ]);
    }

    public function addAction()
    {
		$categorys = $this->categoryTable->fetchAll();
        $form = new CategoryForm($categorys);
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $category = new Category();
        $form->setInputFilter($category->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $category->exchangeArray($form->getData());
        $this->categoryTable->saveCategory($category);
        return $this->redirect()->toRoute('admin_category');
    }

     public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin_category', ['action' => 'add']);
        }

        // Retrieve the category with the specified id. Doing so raises
        // an exception if the category is not found, which should result
        // in redirecting to the landing page.
        try {
            $category = $this->categoryTable->getCategory($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('admin_category', ['action' => 'index']);
        }

        $form = new CategoryForm();
        $form->bind($category);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($category->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->categoryTable->saveCategory($category);
        } catch (\Exception $e) {
        }

        // Redirect to category list
        return $this->redirect()->toRoute('admin_category', ['action' => 'index']);
    }
	
     public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin_category');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->categoryTable->deleteCategory($id);
            }

            // Redirect to list of categorys
            return $this->redirect()->toRoute('admin_category');
        }

        return [
            'id'    => $id,
            'category' => $this->categoryTable->getCategory($id),
        ];
    }
}