<?php

namespace Admin\Controller;

use Admin\Model\ColorTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Admin\Form\ColorForm;
use Admin\Model\Color;

class ColorController extends AbstractActionController
{
	  // Add this property:
    private $colorTable;
	
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
    public function __construct(ColorTable $colorTable)
    {
        $this->colorTable = $colorTable;
    }

    public function indexAction()
    {
		 return new ViewModel([
            'colors' => $this->colorTable->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new ColorForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $color = new Color();
        $form->setInputFilter($color->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $color->exchangeArray($form->getData());
        $this->colorTable->saveColor($color);
        return $this->redirect()->toRoute('admin_color');
    }

     public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin_color', ['action' => 'add']);
        }

        // Retrieve the color with the specified id. Doing so raises
        // an exception if the color is not found, which should result
        // in redirecting to the landing page.
        try {
            $color = $this->colorTable->getColor($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('admin_color', ['action' => 'index']);
        }

        $form = new ColorForm();
        $form->bind($color);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($color->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->colorTable->saveColor($color);
        } catch (\Exception $e) {
        }

        // Redirect to color list
        return $this->redirect()->toRoute('admin_color', ['action' => 'index']);
    }
	
     public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('color');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->colorTable->deleteColor($id);
            }

            // Redirect to list of colors
            return $this->redirect()->toRoute('admin_color');
        }

        return [
            'id'    => $id,
            'color' => $this->colorTable->getColor($id),
        ];
    }
}

