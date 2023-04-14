<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use \Admin\Model\ProductTable;

class IndexController extends AbstractActionController
{
	private $productTable;

    public function __construct(ProductTable $productTable)
    {
        $this->productTable = $productTable;
    }
	
    public function indexAction()
    {
		$products = $this->productTable->getProductLimit(3);
        return new ViewModel(compact('products'));
		
    }
}
