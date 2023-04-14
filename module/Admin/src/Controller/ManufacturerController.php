<?php

namespace Admin\Controller;

use Admin\Model\ManufacturerTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Admin\Form\ManufacturerForm;
use Admin\Model\Manufacturer;

class ManufacturerController extends AbstractActionController
{
	  // Add this property:
    private $manufacturerTable;
	
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
    public function __construct(ManufacturerTable $manufacturerTable)
    {
        $this->manufacturerTable = $manufacturerTable;
    }

    public function indexAction()
    {
		 return new ViewModel([
            'manufacturers' => $this->manufacturerTable->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new ManufacturerForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $manufacturer = new Manufacturer();
        $form->setInputFilter($manufacturer->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $manufacturer->exchangeArray($form->getData());
        $this->manufacturerTable->saveManufacturer($manufacturer);
        return $this->redirect()->toRoute('admin_manufacturer');
    }

     public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin_manufacturer', ['action' => 'add']);
        }

        // Retrieve the manufacturer with the specified id. Doing so raises
        // an exception if the manufacturer is not found, which should result
        // in redirecting to the landing page.
        try {
            $manufacturer = $this->manufacturerTable->getManufacturer($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('admin_manufacturer', ['action' => 'index']);
        }

        $form = new ManufacturerForm();
        $form->bind($manufacturer);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($manufacturer->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->manufacturerTable->saveManufacturer($manufacturer);
        } catch (\Exception $e) {
        }

        // Redirect to manufacturer list
        return $this->redirect()->toRoute('admin_manufacturer', ['action' => 'index']);
    }
	
     public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin_manufacturer');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->manufacturerTable->deleteManufacturer($id);
            }

            // Redirect to list of manufacturers
            return $this->redirect()->toRoute('admin_manufacturer');
        }

        return [
            'id'    => $id,
            'manufacturer' => $this->manufacturerTable->getManufacturer($id),
        ];
    }
}

