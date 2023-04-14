<?php
namespace Admin\Form;

use Laminas\Form\Form;
use Laminas\Form\Element;

class ProductCartForm extends Form
{
    public function __construct($products, $carts)
    {
		$this->products = $products;
		$this->carts = $carts;
        // We will ignore the name provided to the constructor
        parent::__construct('productcart');
		
		$this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
		
		
        $select = new Element\Select('id_product');
        $select->setLabel('Product');
        $select->setAttributes(['class'=>'form-control']);
		
		$options = array();
		
		$options[0] = "";
		
		foreach($this->products as $product) {
			$options[$product->id] = $product->name;
		}
		
        $select->setValueOptions($options);

        $this->add($select);
		
		$select = new Element\Select('id_cart');
        $select->setLabel('Cart');
        $select->setAttributes(['class'=>'form-control']);
		
		$options = array();
		
		$options[0] = "";
		foreach($this->carts as $cart) {
			$options[$cart->id] = $cart->id;
		}
		
        $select->setValueOptions($options);

        $this->add($select);
		
		$this->add([
            'name' => 'quantity',
            'type' => 'text',
            'options' => [
                'label' => 'Quantity',
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
