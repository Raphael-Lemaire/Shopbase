<?php
namespace Admin\Form;

use Laminas\Form\Form;
use Laminas\Form\Element;

class CartForm extends Form
{
    public function __construct($customers)
    {
		$this->customers = $customers;
        // We will ignore the name provided to the constructor
        parent::__construct('cart');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);

        $select = new Element\Select('id_customer');
        $select->setLabel('Customer');
        $select->setAttributes(['class'=>'form-control']);
		
		$options = array();
		
		$options[0] = "";
		foreach($this->customers as $customer) {
			$options[$customer->id] = $customer->name;
		}
		
        $select->setValueOptions($options);

        $this->add($select);
		
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