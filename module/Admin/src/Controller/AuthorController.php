<?php

namespace Admin\Controller;

use Admin\Model\AuthorTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\MvcEvent;
use Admin\Form\AuthorForm;
use Admin\Model\Author;

class AuthorController extends AbstractActionController
{
	  // Add this property:
    private $authorTable;
	
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
    public function __construct(AuthorTable $authorTable)
    {
        $this->authorTable = $authorTable;
    }

    public function indexAction()
    {
		 return new ViewModel([
            'authors' => $this->authorTable->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new AuthorForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $author = new Author();
        $form->setInputFilter($author->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $author->exchangeArray($form->getData());
        $this->authorTable->saveAuthor($author);
        return $this->redirect()->toRoute('admin_author');
    }

     public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin_author', ['action' => 'add']);
        }

        // Retrieve the author with the specified id. Doing so raises
        // an exception if the author is not found, which should result
        // in redirecting to the landing page.
        try {
            $author = $this->authorTable->getAuthor($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('admin_author', ['action' => 'index']);
        }

        $form = new AuthorForm();
        $form->bind($author);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($author->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->authorTable->saveAuthor($author);
        } catch (\Exception $e) {
        }

        // Redirect to author list
        return $this->redirect()->toRoute('admin_author', ['action' => 'index']);
    }
	
     public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin_author');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->authorTable->deleteAuthor($id);
            }

            // Redirect to list of authors
            return $this->redirect()->toRoute('admin_author');
        }

        return [
            'id'    => $id,
            'author' => $this->authorTable->getAuthor($id),
        ];
    }
}