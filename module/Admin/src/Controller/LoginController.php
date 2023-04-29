<?php

namespace Admin\Controller;

use Admin\Model\AdminTable;
use Admin\Form\AdminForm;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\MvcEvent;
use Admin\Model\Admin;
use Admin\Model\Login;

class LoginController extends AbstractActionController
{
	  // Add this property:
    private $adminTable;
    // Add this constructor:
	
    public function __construct(AdminTable $adminTable)
    {
        $this->adminTable = $adminTable;
    }

    public function indexAction()
    {
		$form = new AdminForm();
        $form->get('submit')->setValue('Sign in');
		$request = $this->getRequest(); 

        if (! $request->isPost()) {
          return ['form' => $form];
        }

		$post = array_merge_recursive($request->getPost()->toArray());
		
		$mail = $post['email'];
		$password = md5($post['password']);
			
		if($user = $this->adminTable->getAdminLogin($mail, $password)){
			//d($user);
			$_SESSION["admin"] = serialize($user);
			//d(serialize($user));
			return $this->redirect()->toRoute('admin_admin');
		
			
		 } else {
		 	return ['form' => $form, 'error' => 1];
		 }
	}
	
	public function disconnectAction()
	{
		
		session_destroy();
		session_unset();
		
		return $this->redirect()->toRoute('admin_login');
		
	}
}

