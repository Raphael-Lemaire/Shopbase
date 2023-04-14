<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\MvcEvent;
use \Admin\Model\ProductCartTable;
use \Admin\Model\CartTable;

class CartController extends AbstractActionController
{
	private $productTable;
	private $cartTable;
	
	public function onDispatch(MvcEvent $e) {
		
		if(isset($_SESSION['customer']) && !empty($_SESSION['customer'])) {
		
			$this->customer = unserialize($_SESSION['customer']);
			
		} else {
			
			return $this->redirect()->toRoute('login');
			
		}
		
		$response = parent::onDispatch($e);
		
		$this->layout()->setTemplate('layout/layout.phtml');
		
		return $response;
		
	}

    public function __construct(ProductCartTable $productcartTable, CartTable $cartTable)
    {
        $this->productcartTable = $productcartTable;
		$this->cartTable = $cartTable;
    }
	
    public function indexAction()
    {
		$customer = unserialize($_SESSION['customer']);
		
		$carts = $this->cartTable->getCurrentCart($customer->id);
		$productcarts = $this->productcartTable->fetchAllByCart(_array($carts)[0]->id);
		
		
        return new ViewModel(compact('productcarts'));
		
    }
	
	
}
