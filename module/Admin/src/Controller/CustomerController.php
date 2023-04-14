<?php

namespace Admin\Controller;

use Admin\Model\CustomerTable;
use Admin\Model\CartTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Admin\Form\CustomerForm;
use Admin\Model\Customer;

class CustomerController extends AbstractActionController
{
	  // Add this property:
    private $customerTable;
	
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
    public function __construct(CustomerTable $customerTable, CartTable $cartTable)
    {
        $this->customerTable = $customerTable;
		$this->cartTable = $cartTable;
    }

    public function indexAction()
    {
		$customers = $this->customerTable->fetchAll();
		return new ViewModel(compact('customers'));
    }

    public function addAction()
    {
        $form = new CustomerForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $customer = new Customer();
		
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

        $customer->exchangeArray($form->getData());
		$target_dir = getcwd() . '/public/admin/img/';
		$target_file = $target_dir . basename($_FILES["profil_img"]["name"]);
		if (file_exists($target_file)) {
  		echo "Sorry, file already exists.";
		}
	
		move_uploaded_file($_FILES["profil_img"]["tmp_name"], $target_file);
		$customer->profil_img = $_FILES["profil_img"]["name"];
		
        $this->customerTable->saveCustomer($customer);
        return $this->redirect()->toRoute('admin_customer');
		
	}

     public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin_customer', ['action' => 'add']);
        }

        // Retrieve the customer with the specified id. Doing so raises
        // an exception if the customer is not found, which should result
        // in redirecting to the landing page.
        try {
            $customer = $this->customerTable->getCustomer($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('admin_customer', ['action' => 'index']);
        }

        $form = new CustomerForm();
        $form->bind($customer);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form, 'customer' => $customer];

        if (! $request->isPost()) {
            return $viewData;
			
        }
		 
		$post = array_merge_recursive($request->getPost()->toArray(),$request->getFiles()->toArray());
		$form->setData($post);
		//d($post);
		 
		if($post["profil_img"]["name"] != null && $post["profil_img"]["name"] !=$customer->profil_img){
				if($customer->profil_img != null){
				
					$data = $customer->profil_img;
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
						$customer->profil_img = $_FILES["profil_img"]["name"];
					}		
			}
		}
        $form->setInputFilter($customer->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->customerTable->saveCustomer($customer);
        } catch (\Exception $e) {
        }

        // Redirect to customer list
        return $this->redirect()->toRoute('admin_customer', ['action' => 'index']);
		}
	 
	
     public function deleteAction()
    {
		 
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin_customer');
        }
		$customer = $this->customerTable->getCustomer($id);
		$cart = $this->cartTable->getCartCustomer($id);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
				if(!$customer->profil_img == null){
					$data = $customer->profil_img;
					$dir = "./public/admin/img";
					$dirHandle = opendir($dir);
					while($file = readdir($dirHandle)){
						if ($file==$data)
						{
							unlink($dir.'/'.$file);
						}
					}					
					$id = (int) $request->getPost('id');
                	$this->customerTable->deleteCustomer($id);
					$this->cartTable->deleteCartCustomer($id);

				} else {
					
					$id = (int) $request->getPost('id');
                    $this->customerTable->deleteCustomer($id);
					$this->cartTable->deleteCartCustomer($id);

				}
            }

            // Redirect to list of customers
            return $this->redirect()->toRoute('admin_customer');
        }

        return [
            'id'    => $id,
            'customer' => $this->customerTable->getCustomer($id),
			'cart' => $this->cartTable->getCartCustomer($id),
        ];
    }
}

