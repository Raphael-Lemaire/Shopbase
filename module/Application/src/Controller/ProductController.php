<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use \Admin\Model\ProductTable;
use Admin\Model\AuthorTable;
use Admin\Model\YearTable;
use Admin\Model\ManufacturerTable;
use Admin\Model\ColorTable;
use Admin\Model\CategoryTable;

class ProductController extends AbstractActionController
{
	private $productTable;
	private $authorTable;
	private $yearTable;
	private $manufacturerTable;
	private $colorTable;
	private $cateoryTable;

    public function __construct(ProductTable $productTable, AuthorTable $authorTable, YearTable $yearTable, ManufacturerTable $manufacturerTable, ColorTable $colorTable, categoryTable $categoryTable)
    {
        $this->productTable = $productTable;
		$this->authorTable = $authorTable;
		$this->yearTable = $yearTable;
		$this->manufacturerTable = $manufacturerTable;
		$this->colorTable = $colorTable;
		$this->categoryTable = $categoryTable;
    }
	
    public function indexAction()
    {
		$products = $this->productTable->fetchAll(1);
		$categories = $this->categoryTable->fetchAll();
		$colors = $this->colorTable->fetchAll();
		$years = $this->yearTable->fetchAll();
        return new ViewModel(compact('products','categories','colors','years'));
		
    }
	
	public function viewAction(){
		
		$id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('nos-produits');
        }
		
		$product = $this->productTable->getProduct($id);
		
		return new ViewModel(compact('product'));
		
	}
}

