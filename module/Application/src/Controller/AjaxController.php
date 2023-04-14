<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use \Admin\Model\ProductTable;
use \Admin\Model\ProductCartTable;
use \Admin\Form\ProductCartForm;
use \Admin\Model\CartTable;
use \Admin\Model\ProductCart;

use Laminas\Mvc\MvcEvent;

class AjaxController extends AbstractActionController
{
	public function onDispatch(MvcEvent $e) {
		
		$response = parent::onDispatch($e);
		
		$this->layout()->setTemplate('layout/layout-ajax.phtml');
		
		return $response;
		
	}
	
	public function __construct(ProductTable $productTable, CartTable $cartTable, ProductCartTable $productcartTable)
    {
        $this->productTable = $productTable;
		$this->cartTable = $cartTable;
		$this->productcartTable = $productcartTable;
    }
	
    public function indexAction()
    {
	 		//$offset = (int) $this->params()->fromRoute('id', 1);
		 $limit = $_POST['id'];
		//d($offset);
		
		$products = $this->productTable->fetchAllLimit(1, $limit, 'id');
		
		
        return new ViewModel(compact("products"));
		
    }
	
	public function cartAction(){
		    
		$customer = unserialize($_SESSION['customer']);
		$carts = $this->cartTable->getCurrentCart($customer->id)->toArray();
		$productcarts = $this->productcartTable->fetchAllByCart($carts[0]['id']);
		echo json_encode($productcarts);
	}
	
		public function smallcartAction(){
		    
	 	$customer = unserialize($_SESSION['customer']);
		$carts = $this->cartTable->getCurrentCart($customer->id);
		$productcarts = $this->productcartTable->fetchAllByCart(_array($carts)[0]->id);
		
        return new ViewModel(compact('productcarts'));
	}
	public function saveProductInCartAction()
	{
		if(!isset($_SESSION["customer"]))
		{
			echo 2;
		}
		else
		{
			//Récupération des informations pour l'insert
			$id_product = (int) $this->params()->fromRoute('id_product', 0);
			
			$customer = unserialize($_SESSION['customer']);
			$currentCart = $this->cartTable->getCurrentCart($customer->id);

			//Créationd e l'objet
			$productCart = new ProductCart();	
			$productCart->id_product = $id_product;
			$productCart->id_cart =_array($currentCart)[0]->id;
			$productCart->quantity = 1;
			$this->productcartTable->saveProductCart($productCart);
			echo 1;
		}

		exit();
	}
	
	public function removeProductInCartAction()
	{
		if(!isset($_SESSION["customer"]))
		{
			echo 2;
		}
		else
		{
			//Récupération des informations de l'utilisateur
			$customer = unserialize($_SESSION['customer']);

			//Récupération des informations pour l'insert
			$id_product = (int) $this->params()->fromRoute('id_product', 0);
			$currentCart = $this->cartTable->getCurrentCart($customer->id);

			$productCart = $this->productcartTable->getProductCart($id_product,_array($currentCart)[0]->id);
			$this->productcartTable->deleteProductCart($productCart->id);
			echo 1;
		}

		exit();
	}
}

