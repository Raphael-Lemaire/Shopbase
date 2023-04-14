<?php
namespace Admin\Model;

use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\StringLength;

class ProductCart implements InputFilterAwareInterface
{
	public $id;
	public $id_product;
	public $id_cart;
	public $quantity;
	
	public $name_product;
	public $product_image;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
		$this->id = !empty($data['id']) ? $data['id'] : null;
		$this->id_product  = !empty($data['id_product']) ? $data['id_product'] : null;
		$this->id_cart  = !empty($data['id_cart']) ? $data['id_cart'] : null;
		$this->quantity  = !empty($data['quantity']) ? $data['quantity'] : null;
		
		$this->name_product = !empty($data['name_product']) ? $data['name_product'] : null;	
		$this->product_image = !empty($data['product_image']) ? $data['product_image'] : null;	
		
    }
	
	public function getArrayCopy()
    {
        return [
			'id' => $this->id,
			'id_product'  => $this->id_product,
			'id_cart'  => $this->id_cart,
			'quantity' => $this->quantity,
			
			'name_product' => $this->name_product,
			'product_image' => $this->product_image,
        ];
    }

    /* Add the following methods: */

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();
		
		$inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
		
        $inputFilter->add([
            'name' => 'id_product',
            'required' => false,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
		
		$inputFilter->add([
            'name' => 'id_cart',
            'required' => false,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
		
 		$inputFilter->add([
            'name' => 'quantity',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
		
		

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}