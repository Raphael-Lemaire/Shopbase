<?php

namespace Admin\Controller;

use Admin\Form\ShoeForm;
use Admin\Model\ColorTable;
use Admin\Model\ManufacturerTable;
use Admin\Model\ShoeTable;
use Admin\Model\shoe;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;


class ShoeController extends AbstractActionController
{
	  // Add this property:
    private $shoeTable;
	private $colorTable;
	private $manufacturerTable;
	
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
    public function __construct(ShoeTable $shoeTable, ColorTable $colorTable, ManufacturerTable $manufacturerTable)
    {
        $this->shoeTable = $shoeTable;
		$this->colorTable = $colorTable;
		$this->manufacturerTable = $manufacturerTable;
    }

    public function indexAction()
    {
		 $shoes = $this->shoeTable->fetchAll();
		 return new ViewModel(compact('shoes'));
		
		
    }

    public function addAction()
    {
		$colors = $this->colorTable->fetchAll();
		$manufacturers = $this->manufacturerTable->fetchAll();
        $form = new ShoeForm($colors, $manufacturers);
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $shoe = new Shoe();
		
		$post = array_merge_recursive($request->getPost()->toArray(),$request->getFiles()->toArray());
		$form->setData($post);
		$form->get('shoe_img')->setValue($post["shoe_img"]["name"]);
		//d($form);
		if ($form->isValid()) 
		{
    		$validatedData = $form->getData();
			//d("yess");
		} 
		else 
		{
    		$messages = $form->getMessages();
			d($messages);
		}

        $form->setInputFilter($shoe->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $shoe->exchangeArray($form->getData());
		
		$target_dir = getcwd() . '/public/img/';
		$target_file = $target_dir . basename($_FILES["shoe_img"]["name"]);
	
		move_uploaded_file($_FILES["shoe_img"]["tmp_name"], $target_file);
		$shoe->shoe_img = $_FILES["shoe_img"]["name"];
		
		
        $this->shoeTable->saveShoe($shoe);
        return $this->redirect()->toRoute('admin_shoe');
    }

     public function editAction()
    {
		$colors = $this->colorTable->fetchAll();
		$manufacturers = $this->manufacturerTable->fetchAll();
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('admin_shoe', ['action' => 'add']);
        }

        // Retrieve the shoe with the specified id. Doing so raises
        // an exception if the shoe is not found, which should result
        // in redirecting to the landing page.
        try {
            $shoe = $this->shoeTable->getShoe($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('admin_shoe', ['action' => 'index']);
        }
		 
		$shoe = $this->shoeTable->getShoe($id);
        $form = new ShoeForm($colors, $manufacturers);
        $form->bind($shoe);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form, 'shoe' => $shoe];

        if (! $request->isPost()) {
            return $viewData;
        }
		 
		
		$post = array_merge_recursive($request->getPost()->toArray(),$request->getFiles()->toArray());
		$form->setData($post);
		 
		if($post["shoe_img"]["name"] != null && $post["shoe_img"]["name"] !=$shoe->shoe_img){
				if($shoe->shoe_img != null){
				
					$data = $shoe->shoe_img;
					$dir = "./public/img";
					$dirHandle = opendir($dir);
					while($file = readdir($dirHandle)){
						if ($file==$data)
						{
							unlink($dir.'/'.$file);
						}
						$target_dir = getcwd() . '/public/img/';
						$target_file = $target_dir . basename($_FILES["shoe_img"]["name"]);
	
						move_uploaded_file($_FILES["shoe_img"]["tmp_name"], $target_file);
						$shoe->shoe_img = $_FILES["shoe_img"]["name"];
					}
				
				
			}
		}
		 
		

        $form->setInputFilter($shoe->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        try {
            $this->shoeTable->saveShoe($shoe);
        } catch (\Exception $e) {
        }

        // Redirect to shoe list
        return $this->redirect()->toRoute('admin_shoe', ['action' => 'index']);
    }
	
     public function deleteAction()
    {
		$colors_tmp = $this->colorTable->fetchAll()->toArray();
		$colors = array();
		foreach($colors_tmp as $color) {
			$colors[$color['id']] = $color;
		}
		$manufacturers_tmp = $this->manufacturerTable->fetchAll()->toArray();
		$manufacturers = array();
		foreach($manufacturers_tmp as $manufacturer) {
			$manufacturers[$manufacturer['id']] = $manufacturer;
		}
		 
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('admin_shoe');
        }
		 $shoe = $this->shoeTable->getShoe($id);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
				if(!$shoe->shoe_img == null){
					$data = $shoe->shoe_img;
					$dir = "./public/img";
					$dirHandle = opendir($dir);
					while($file = readdir($dirHandle)){
						if ($file==$data)
						{
							unlink($dir.'/'.$file);
						}
					}					
					$id = (int) $request->getPost('id');
                	$this->shoeTable->deleteShoe($id);

				} else {
					
					$id = (int) $request->getPost('id');
                    $this->shoeTable->deleteShoe($id);
				}
				
			}
			

            // Redirect to list of shoes
            return $this->redirect()->toRoute('admin_shoe');
        }

        return [
            'id'    => $id,
            'shoe' => $this->shoeTable->getShoe($id),
			'colors' => $colors,
			'manufacturers' => $manufacturers
        ];
    }
}
