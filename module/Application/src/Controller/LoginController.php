<?php

namespace Application\Controller;

use Admin\Model\CustomerTable;
use Admin\Model\CartTable;
use Admin\Form\CustomerForm;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\MvcEvent;
use Admin\Model\Customer;
use Admin\Model\Cart;

class LoginController extends AbstractActionController
{
	  // Add this property:
    private $customerTable;
	private $cartTable;
   // Add this constructor:
	
    public function __construct(CustomerTable $customerTable, CartTable $cartTable)
    {
        $this->customerTable = $customerTable;
		$this->cartTable = $cartTable;
    }

    public function indexAction()
    {
		$form = new CustomerForm();
        $form->get('submit')->setValue('Sign in');
		$request = $this->getRequest(); 

        if (! $request->isPost()) {
          return ['form' => $form];
        }

		$post = array_merge_recursive($request->getPost()->toArray());
		
		$mail = $post['email'];
		$password = md5($post['password']);
			
		if($user = $this->customerTable->getCustomerLogin($mail, $password)){
			//d($user);
			$_SESSION["customer"] = serialize($user);
			if(empty(_array(($this->cartTable->getCurrentCart($user->id)))))
				   {
						$cart = new Cart();
						$cart->id_customer = $user->id;
						$cart->id = 0; 
					   	$this->cartTable->saveCart($cart);
				   }
				return $this->redirect()->toRoute('mon-panier');
			//d(serialize($user));
			
		 } else {
		 	return ['form' => $form, 'error' => 1];
		 }
	}
	
	public function disconnectAction()
	{
		
		session_destroy();
		session_unset();
		
		return $this->redirect()->toRoute('login');
		
	}
}