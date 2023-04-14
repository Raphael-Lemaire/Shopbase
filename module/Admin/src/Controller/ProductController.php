<?php

namespace Admin\Controller;

use Admin\Model\ProductTable;
use Admin\Model\AuthorTable;
use Admin\Model\YearTable;
use Admin\Model\ManufacturerTable;
use Admin\Model\ColorTable;
use Admin\Model\CategoryTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Admin\Form\ProductForm;
use Admin\Model\Product;

class ProductController extends AbstractActionController
{
	  // Add this property:
    private $productTable;
	private $authorTable;
	private $yearTable;
	private $manufacturerTable;
	private $colorTable;
	private $cateoryTable;
	
	private $admin;

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
		$categories = $this->categoryTable->fetchAll();
		$products = $this->productTable->fetchAll();
		return new ViewModel(compact('products', 'categories'));
    }
	

    public function addAction()
    {
		$authors = $this->authorTable->fetchAll();
		$years = $this->yearTable->fetchAll();
		$colors = $this->colorTable->fetchAll();
		$manufacturers = $this->manufacturerTable->fetchAll();
		$categorys = $this->categoryTable->fetchAll();
        $form = new ProductForm($authors, $years, $colors, $manufacturers, $categorys);
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $product = new Product();
		
		$post = array_merge_recursive($request->getPost()->toArray(),$request->getFiles()->toArray());
		$form->setData($post);
		$form->get('product_img')->setValue($post["product_img"]["name"]);
		//d($form);
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

        $product->exchangeArray($form->getData());
		
		$target_dir = getcwd() . '/public/admin/img/';
		$target_file = $target_dir . basename($_FILES["product_img"]["name"]);
		if (file_exists($target_file)) {
  		echo "Sorry, file already exists.";
		}
	
		move_uploaded_file($_FILES["product_img"]["tmp_name"], $target_file);
		$product->product_img = $_FILES["product_img"]["name"];
		
        $this->productTable->saveProduct($product);
        return $this->redirect()->toRoute('admin_product');
		
	}

     public function editAction()
    {
		$authors = $this->authorTable->fetchAll();
		$years = $this->yearTable->fetchAll();
		$colors = $this->colorTable->fetchAll();
		$manufacturers = $this->manufacturerTable->fetchAll();
		$categorys = $this->categoryTable->fetchAll();
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin_product', ['action' => 'add']);
        }

        // Retrieve the product with the specified id. Doing so raises
        // an exception if the product is not found, which should result
        // in redirecting to the landing page.
        try {
            $product = $this->productTable->getProduct($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('admin_product', ['action' => 'index']);
        }

        $form = new ProductForm($authors, $years, $colors, $manufacturers, $categorys);
        $form->bind($product);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form, 'product' => $product];

        if (! $request->isPost()) {
            return $viewData;
			
        }
		 
		$post = array_merge_recursive($request->getPost()->toArray(),$request->getFiles()->toArray());
		$form->setData($post);
		//d($post);
		 
		if($post["product_img"]["name"] != null && $post["product_img"]["name"] !=$product->product_img){
				if($product->product_img != null){
				
					$data = $product->product_img;
					$dir = "./public/admin/img";
					$dirHandle = opendir($dir);
					while($file = readdir($dirHandle)){
						if ($file==$data)
						{
							unlink($dir.'/'.$file);
						}
						$target_dir = getcwd() . '/public/admin/img/';
						$target_file = $target_dir . basename($_FILES["product_img"]["name"]);
	
						move_uploaded_file($_FILES["product_img"]["tmp_name"], $target_file);
						$product->product_img = $_FILES["product_img"]["name"];
					}		
			}
		}
        $form->setInputFilter($product->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->productTable->saveProduct($product);
        } catch (\Exception $e) {
        }

        // Redirect to product list
        return $this->redirect()->toRoute('admin_product', ['action' => 'index']);
		}
	 
	
     public function deleteAction()
    {
		$author_tmp = $this->authorTable->fetchAll()->toArray();
		$authors = array();
		foreach($author_tmp as $author) {
			$authors[$author['id']] = $author;
		}
		$year_tmp = $this->yearTable->fetchAll()->toArray();
		$years = array();
		foreach($year_tmp as $year) {
			$years[$year['id']] = $year;
		}
		$color_tmp = $this->colorTable->fetchAll()->toArray();
		$colors = array();
		foreach($color_tmp as $color) {
			$colors[$color['id']] = $color;
		}
		$manufacturer_tmp = $this->manufacturerTable->fetchAll()->toArray();
		$manufacturers = array();
		foreach($manufacturer_tmp as $manufacturer) {
			$manufacturers[$manufacturer['id']] = $manufacturer;
		}
		$category_tmp = $this->categoryTable->fetchAll()->toArray();
		$categorys = array();
		foreach($category_tmp as $category) {
			$categorys[$category['id']] = $category;
		}
		 
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('product');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->productTable->deleteProduct($id);
            }

            // Redirect to list of products
            return $this->redirect()->toRoute('admin_product');
        }

        return [
            'id'    => $id,
            'product' => $this->productTable->getProduct($id),
			'years' => $years,
			'authors' => $authors,
			'colors' => $colors,
			'manufacturers' => $manufacturers,
			'categorys' => $categorys,
        ];
    }
}
