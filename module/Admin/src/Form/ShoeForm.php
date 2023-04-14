<?php
namespace Admin\Form;

use Laminas\Form\Form;
use Laminas\Form\Element;

class ShoeForm extends Form
{
    public function __construct($colors, $manufacturers)
    {
			$this->colors = $colors;
			$this->manufacturers = $manufacturers;
        // We will ignore the name provided to the constructor
        parent::__construct('shoe');

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
        $select = new Element\Select('id_color');
        $select->setLabel('Couleur');
        $select->setAttributes(['class'=>'form-control']);
		
		$options = array();
		
		foreach($this->colors as $color) {
			$options[$color->id] = $color->name;
		}
		
        $select->setValueOptions($options);

        $this->add($select);
		
		$select = new Element\Select('id_manufacturer');
        $select->setLabel('Manufacture');
        $select->setAttributes(['class'=>'form-control']);
		
		$options = array();
		
		foreach($this->manufacturers as $manufacturer) {
			$options[$manufacturer->id] = $manufacturer->name;
		}
		
        $select->setValueOptions($options);

        $this->add($select);
		
		$this->add([
            'name' => 'shoe_img',
            'type' => 'text',
            'options' => [
                'label' => 'Shoe_img',
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
