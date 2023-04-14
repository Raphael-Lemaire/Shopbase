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

class Product implements InputFilterAwareInterface
{
    public $id;
    public $id_author;
    public $name;
	public $id_year;
	public $id_color;
	public $id_manufacturer;
	public $id_category;
	public $price;
	public $product_img;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->id_author = !empty($data['id_author']) ? $data['id_author'] : null;
        $this->name  = !empty($data['name']) ? $data['name'] : null;
		$this->id_year  = !empty($data['id_year']) ? $data['id_year'] : null;
		$this->id_color  = !empty($data['id_color']) ? $data['id_color'] : null;
		$this->id_manufacturer  = !empty($data['id_manufacturer']) ? $data['id_manufacturer'] : null;
		$this->id_category  = !empty($data['id_category']) ? $data['id_category'] : null;
		$this->price  = !empty($data['price']) ? $data['price'] : null;
		$this->product_img  = !empty($data['product_img']) ? $data['product_img'] : null;
		
		$this->name_author = !empty($data['name_author']) ? $data['name_author'] : null;
		$this->name_year = !empty($data['name_year']) ? $data['name_year'] : null;
		$this->name_color = !empty($data['name_color']) ? $data['name_color'] : null;		
		$this->name_manufacturer = !empty($data['name_manufacturer']) ? $data['name_manufacturer'] : null;
		$this->name_category = !empty($data['name_category']) ? $data['name_category'] : null;
		
    }
	
	public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'id_author' => $this->id_author,
            'name'  => $this->name,
			'id_year'  => $this->id_year,
			'id_color'  => $this->id_color,
			'id_manufacturer'  => $this->id_manufacturer,
			'id_category'  => $this->id_category,
			'price'  => $this->price,
			'product_img' => $this->product_img,
			
			'name_author' => $this->name_author,
			'name_year' => $this->name_year,
			'name_color' => $this->name_color,
			'name_manufacturer' => $this->name_manufacturer,
			'name_category' => $this->name_category,
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
            'name' => 'id_author',
            'required' => false,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
		+
        $inputFilter->add([
            'name' => 'id_year',
            'required' => false,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
		
		$inputFilter->add([
            'name' => 'id_color',
            'required' => false,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
		$inputFilter->add([
            'name' => 'id_manufacturer',
            'required' => false,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
		
		$inputFilter->add([
            'name' => 'id_category',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'name',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
		
		$inputFilter->add([
            'name' => 'price',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
		
		$inputFilter->add([
            'name' => 'product_img',
            'required' => false,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 255,
                    ],
                ],
            ],
        ]);
		

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}