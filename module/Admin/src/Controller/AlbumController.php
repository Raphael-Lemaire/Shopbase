<?php

namespace Admin\Controller;

use Admin\Model\AlbumTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Admin\Form\AlbumForm;
use Admin\Model\Album;

class AlbumController extends AbstractActionController
{
	  // Add this property:
    private $albumTable;

	public function onDispatch(MvcEvent $e) {
		
		if(empty($_SESSION["user"]))        
        {
            return $this->redirect()->toRoute('admin_login');
        }
		
		$response = parent::onDispatch($e);
		
		$this->layout()->setTemplate('layout/layout-admin.phtml');
		
		return $response;
		
		
	}
	
    // Add this constructor:
    public function __construct(AlbumTable $albumTable)
    {
        $this->albumTable = $albumTable;
    }

    public function indexAction()
    {
       return new ViewModel([
            'albums' => $this->albumTable->fetchAll(),
			]);
    }

    public function addAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $album = new Album();
        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $album->exchangeArray($form->getData());
        $this->albumTable->saveAlbum($album);
        return $this->redirect()->toRoute('admin_album');
    }

     public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin_album', ['action' => 'add']);
        }

        // Retrieve the album with the specified id. Doing so raises
        // an exception if the album is not found, which should result
        // in redirecting to the landing page.
        try {
            $album = $this->albumTable->getAlbum($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('admin_album', ['action' => 'index']);
        }

        $form = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->albumTable->saveAlbum($album);
        } catch (\Exception $e) {
        }

        // Redirect to album list
        return $this->redirect()->toRoute('admin_album', ['action' => 'index']);
    }
	
     public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->albumTable->deleteAlbum($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('admin_album');
        }

        return [
            'id'    => $id,
            'album' => $this->albumTable->getAlbum($id),
        ];
    }
}
