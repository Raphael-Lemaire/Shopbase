<?php
namespace Admin\Form;

use Laminas\Form\Form;
use Laminas\Form\Element;

class ProductForm extends Form
{
    public function __construct($authors, $years, $colors, $manufacturers, $categorys)
    {
		$this->authors = $authors;
		$this->years = $years;
		$this->colors = $colors;
		$this->manufacturers = $manufacturers;
		$this->categorys = $categorys;
        // We will ignore the name provided to the constructor
        parent::__construct('product');

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
        $select = new Element\Select('id_author');
        $select->setLabel('Auteur');
        $select->setAttributes(['class'=>'form-control']);
		
		$options = array();
		
		$options[0] = "";
		foreach($this->authors as $author) {
			$options[$author->id] = $author->name;
		}
		
        $select->setValueOptions($options);

        $this->add($select);
		
		$options[0] = "";
		$select = new Element\Select('id_year');
        $select->setLabel('Année');
        $select->setAttributes(['class'=>'form-control']);
		
		$options = array();
		
		$options[0] = "";
		foreach($this->years as $year) {
			$options[$year->id] = $year->name;
		}
		
        $select->setValueOptions($options);

        $this->add($select);
		
		$select = new Element\Select('id_color');
        $select->setLabel('Couleur');
        $select->setAttributes(['class'=>'form-control']);
		
		$options = array();
		
		$options[0] = "";
		foreach($this->colors as $color) {
			$options[$color->id] = $color->name;
		}
		
        $select->setValueOptions($options);

        $this->add($select);
		
		$select = new Element\Select('id_manufacturer');
        $select->setLabel('Manufacture');
        $select->setAttributes(['class'=>'form-control']);
		
		$options = array();
		
		$options[0] = "";
		foreach($this->manufacturers as $manufacturer) {
			$options[$manufacturer->id] = $manufacturer->name;
		}
		
        $select->setValueOptions($options);

        $this->add($select);
		
		$select = new Element\Select('id_category');
        $select->setLabel('Category');
        $select->setAttributes(['class'=>'form-control']);
		
		$options = array();
		
		foreach($this->categorys as $category) {
			$options[$category->id] = $category->name;
		}
		
        $select->setValueOptions($options);

        $this->add($select);
		
		$this->add([
            'name' => 'product_img',
            'type' => 'text',
            'options' => [
                'label' => 'Product_img',
            ],
        ]);
		
		$this->add([
            'name' => 'price',
            'type' => 'text',
            'options' => [
                'label' => 'Price',
            ],
        ]);
		
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