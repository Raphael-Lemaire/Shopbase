<?php

namespace Admin\Controller;

use Admin\Model\AdminTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Admin\Form\AdminForm;
use Admin\Model\Admin;

class AdminController extends AbstractActionController
{
	  // Add this property:
    private $adminTable;
	
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
    public function __construct(AdminTable $adminTable)
    {
        $this->adminTable = $adminTable;
    }

    public function indexAction()
    {
		$admins = $this->adminTable->fetchAll();
		return new ViewModel(compact('admins'));
    }

    public function addAction()
    {
        $form = new AdminForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $admin = new Admin();
		
		$post = array_merge_recursive($request->getPost()->toArray(),$request->getFiles()->toArray());
		$form->setData($post);
		$form->get('profil_img')->setValue($post["profil_img"]["name"]);
		
		if ($form->isValid()) 
		{
    		$validatedData = $form->getData();
		} 
		else 
		{
    		$messages = $form->getMessages();
			d($messages);
		}

        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $admin->exchangeArray($form->getData());
		$target_dir = getcwd() . '/public/admin/img/';
		$target_file = $target_dir . basename($_FILES["profil_img"]["name"]);
		if (file_exists($target_file)) {
  		echo "Sorry, file already exists.";
		}
	
		move_uploaded_file($_FILES["profil_img"]["tmp_name"], $target_file);
		$admin->profil_img = $_FILES["profil_img"]["name"];
		
        $this->adminTable->saveAdmin($admin);
        return $this->redirect()->toRoute('admin_admin');
		
	}

     public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin_admin', ['action' => 'add']);
        }

        // Retrieve the admin with the specified id. Doing so raises
        // an exception if the admin is not found, which should result
        // in redirecting to the landing page.
        try {
            $admin = $this->adminTable->getAdmin($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('admin_admin', ['action' => 'index']);
        }

        $form = new AdminForm();
        $form->bind($admin);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form, 'admin' => $admin];

        if (! $request->isPost()) {
            return $viewData;
			
        }
		 
		$post = array_merge_recursive($request->getPost()->toArray(),$request->getFiles()->toArray());
		$form->setData($post);
		//d($post);
		 
		if($post["profil_img"]["name"] != null && $post["profil_img"]["name"] !=$admin->profil_img){
				if($admin->profil_img != null){
				
					$data = $admin->profil_img;
					$dir = "./public/admin/img";
					$dirHandle = opendir($dir);
					while($file = readdir($dirHandle)){
						if ($file==$data)
						{
							unlink($dir.'/'.$file);
						}
						$target_dir = getcwd() . '/public/admin/img/';
						$target_file = $target_dir . basename($_FILES["profil_img"]["name"]);
	
						move_uploaded_file($_FILES["profil_img"]["tmp_name"], $target_file);
						$admin->profil_img = $_FILES["profil_img"]["name"];
					}		
			}
		}
        $form->setInputFilter($admin->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->adminTable->saveAdmin($admin);
        } catch (\Exception $e) {
        }

        // Redirect to admin list
        return $this->redirect()->toRoute('admin_admin', ['action' => 'index']);
		}
	 
	
     public function deleteAction()
    {
		 
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin_admin');
        }
		$admin = $this->adminTable->getAdmin($id);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
				if(!$admin->profil_img == null){
					$data = $admin->profil_img;
					$dir = "./public/admin/img";
					$dirHandle = opendir($dir);
					while($file = readdir($dirHandle)){
						if ($file==$data)
						{
							unlink($dir.'/'.$file);
						}
					}					
					$id = (int) $request->getPost('id');
                	$this->adminTable->deleteAdmin($id);

				} else {
					
					$id = (int) $request->getPost('id');
                    $this->adminTable->deleteAdmin($id);
				}
            }

            // Redirect to list of admins
            return $this->redirect()->toRoute('admin_admin');
        }

        return [
            'id'    => $id,
            'admin' => $this->adminTable->getAdmin($id),
        ];
    }
}
