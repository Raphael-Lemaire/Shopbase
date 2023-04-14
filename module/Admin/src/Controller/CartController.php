<?php

namespace Admin\Controller;

use Admin\Model\CartTable;
use Admin\Model\ProductCartTable;
use Admin\Model\CustomerTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Admin\Form\CartForm;
use Admin\Model\Cart;

class CartController extends AbstractActionController
{
	  // Add this property:
    private $cartTable;
	private $customerTable;
	private $productcartTable;
	
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
    public function __construct(CartTable $cartTable, CustomerTable $customerTable, ProductCartTable $productcartTable)
    {
        $this->cartTable = $cartTable;
		$this->customerTable = $customerTable;
		$this->productcartTable = $productcartTable;
    }

    public function indexAction()
    {
		$id = (int) $this->params()->fromRoute('id', 0);
		$carts = $this->cartTable->fetchAll();
		$customers = $this->customerTable->fetchAll();
		$productcarts = $this->productcartTable->getProductCart($id);
		return new ViewModel(compact('customers', 'carts', 'productcarts'));
    }

    public function addAction()
    {
		$customers = $this->customerTable->fetchAll();
        $form = new CartForm($customers);
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $cart = new Cart();
        $form->setInputFilter($cart->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $cart->exchangeArray($form->getData());
        $this->cartTable->saveCart($cart);
        return $this->redirect()->toRoute('admin_cart');
    }

     public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin_cart', ['action' => 'add']);
        }

        // Retrieve the cart with the specified id. Doing so raises
        // an exception if the cart is not found, which should result
        // in redirecting to the landing page.
        try {
            $cart = $this->cartTable->getCart($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('admin_cart', ['action' => 'index']);
        }

        $form = new CartForm();
        $form->bind($cart);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($cart->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->cartTable->saveCart($cart);
        } catch (\Exception $e) {
        }

        // Redirect to cart list
        return $this->redirect()->toRoute('admin_cart', ['action' => 'index']);
    }
	
     public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('cart');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->cartTable->deleteCart($id);
            }

            // Redirect to list of carts
            return $this->redirect()->toRoute('admin_cart');
        }

        return [
            'id'    => $id,
            'cart' => $this->cartTable->getCart($id),
        ];
    }
}
