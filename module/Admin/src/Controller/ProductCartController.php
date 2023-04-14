<?php

namespace Admin\Controller;

use Admin\Model\ProductTable;
use Admin\Model\ProductCartTable;
use Admin\Model\CartTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Admin\Form\ProductCartForm;
use Admin\Model\ProductCart;

class ProductCartController extends AbstractActionController
{
	  // Add this property:
    private $productTable;
	private $productcartTable;
	private $cartTable;

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
    public function __construct(ProductCartTable $productcartTable, ProductTable $productTable, CartTable $cartTable)
    {
        $this->productTable = $productTable;
		$this->productcartTable = $productcartTable;
		$this->cartTable = $cartTable;
    }

    public function indexAction()
    {
		$carts = $this->cartTable->fetchAll();
		$productcarts = $this->productcartTable->fetchAll();
		$products = $this->productTable->fetchAll();
		return new ViewModel(compact('products', 'carts', 'productcarts'));
    }
	

    public function addAction()
    {
//		$carts = $this->cartTable->fetchAll();
//		$products = $this->productTable->fetchAll();
//        $form = new ProductCartForm($products, $carts);
//        $form->get('submit')->setValue('Add');
//
//        $request = $this->getRequest();
//
//        if (! $request->isPost()) {
//            return ['form' => $form];
//        }
//
//        $productcart = new ProductCart();
//		
//		//d($form);
//
//        $form->setData($request->getPost());
//
//        if (! $form->isValid()) {
//            return ['form' => $form];
//        }
//
//        $product->exchangeArray($form->getData());
//		
//        $this->productTable->saveProduct($productcart);
//        return $this->redirect()->toRoute('admin_product_cart');
		
		$carts = $this->cartTable->fetchAll();
		$products = $this->productTable->fetchAll();
		$form = new ProductCartForm($products, $carts);
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
		//d($request); 

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $productcart = new ProductCart();
        $form->setInputFilter($productcart->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $productcart->exchangeArray($form->getData());
		
        $this->productcartTable->saveProductCart($productcart);
        return $this->redirect()->toRoute('admin_product_cart');
		
	}

     public function editAction()
    {
		 $products = $this->productTable->fetchAll();
		 $carts = $this->cartTable->fetchAll();
		$id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin_product_cart', ['action' => 'add']);
        }

        // Retrieve the productcart with the specified id. Doing so raises
        // an exception if the productcart is not found, which should result
        // in redirecting to the landing page.
        try {
            $productcart = $this->productcartTable->getProductCart($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('admin_product_cart', ['action' => 'index']);
        }

        $form = new productCartForm($products, $carts);
        $form->bind($productcart);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
			
            return $viewData;
        }

        $form->setInputFilter($productcart->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
			//d($form);
            return $viewData;
        }

        try {
            $this->productcartTable->saveProductCart($productcart);
        } catch (\Exception $e) {
			
        }

        // Redirect to productcart list
        return $this->redirect()->toRoute('admin_product_cart', ['action' => 'index']);
    }
	 
	
     public function deleteAction()
    {
		$product_tmp = $this->productTable->fetchAll()->toArray();
		$products = array();
		foreach($product_tmp as $product) {
			$products[$product['id']] = $product;
		}
		$cart_tmp = $this->cartTable->fetchAll()->toArray();
		$carts = array();
		foreach($cart_tmp as $cart) {
			$carts[$cart['id']] = $cart;
		}
		 
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin_product_cart');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->productcartTable->deleteProductCart($id);
            }

            // Redirect to list of products
            return $this->redirect()->toRoute('admin_product_cart');
        }

        return [
            'productcart' => $this->productcartTable->getProductCart($id),
			'carts' => $carts,
			'products' => $products,
			"id" => $id,
        ];
    }
}
