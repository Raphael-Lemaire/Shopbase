<?php

namespace Admin\Controller;

use Admin\Model\YearTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Admin\Form\YearForm;
use Admin\Model\Year;

class YearController extends AbstractActionController
{
	  // Add this property:
    private $yearTable;
	
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
    public function __construct(YearTable $yearTable)
    {
        $this->yearTable = $yearTable;
    }

    public function indexAction()
    {
		 return new ViewModel([
            'years' => $this->yearTable->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new YearForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $year = new Year();
        $form->setInputFilter($year->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $year->exchangeArray($form->getData());
        $this->yearTable->saveYear($year);
        return $this->redirect()->toRoute('year');
    }

     public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin_year', ['action' => 'add']);
        }

        // Retrieve the year with the specified id. Doing so raises
        // an exception if the year is not found, which should result
        // in redirecting to the landing page.
        try {
            $year = $this->yearTable->getYear($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('admin_year', ['action' => 'index']);
        }

        $form = new YearForm();
        $form->bind($year);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($year->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->yearTable->saveYear($year);
        } catch (\Exception $e) {
        }

        // Redirect to year list
        return $this->redirect()->toRoute('admin_year', ['action' => 'index']);
    }
	
     public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin_year');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->yearTable->deleteYear($id);
            }

            // Redirect to list of years
            return $this->redirect()->toRoute('admin_year');
        }

        return [
            'id'    => $id,
            'year' => $this->yearTable->getYear($id),
        ];
    }
}
