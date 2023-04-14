<?php
namespace Admin\Form;

use Laminas\Form\Form;
use Laminas\Form\Element;

class CategoryForm extends Form
{
    public function __construct($categorys)
    {
		$this->categorys = $categorys;
        // We will ignore the name provided to the constructor
        parent::__construct('category');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'name',
            'type' => 'text',
            'options' => [
                'label' => 'Name',
            ],
        ]);
		
		$select = new Element\Select('sub_category');
        $select->setLabel('Sub category');
        $select->setAttributes(['class'=>'form-control']);
		
		$options = array();
		
		$options[0] = "";
		foreach($this->categorys as $category) {
			$options[$category->id] = $category->name;
		}
		
        $select->setValueOptions($options);

        $this->add($select);
//		$this->add([
//            'name' => 'sub_category',
//            'type' => 'text',
//            'options' => [
//                'label' => 'Sub Category',
//            ],
//        ]);
		
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
		
    }
}