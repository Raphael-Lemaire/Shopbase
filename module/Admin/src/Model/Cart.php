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

class Cart implements InputFilterAwareInterface
{
    public $id;
    public $id_customer;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->id_customer = !empty($data['id_customer']) ? $data['id_customer'] : null;
		
		$this->name_customer = !empty($data['name_customer']) ? $data['name_customer'] : null;
		$this->cart_content = !empty($data['cart_content']) ? $data['cart_content'] : null;
		
    }
	
	public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'id_customer' => $this->id_customer,
			
			'name_customer' => $this->name_customer,
			'cart_content' => $this->cart_content,

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
            'name' => 'id_customer',
            'required' => false,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
		
        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}