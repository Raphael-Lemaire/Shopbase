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

class Shoe implements InputFilterAwareInterface
{
    public $id;
	public $name;
	public $id_color;
	public $id_manufacturer;
	public $shoe_img;

	public $name_color;
	public $name_manufacturer;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->name = !empty($data['name']) ? $data['name'] : null;
		$this->id_color = !empty($data['id_color']) ? $data['id_color'] : null;
		$this->id_manufacturer = !empty($data['id_manufacturer']) ? $data['id_manufacturer'] : null;
		$this->shoe_img  = !empty($data['shoe_img']) ? $data['shoe_img'] : null;
		
		$this->name_color = !empty($data['name_color']) ? $data['name_color'] : null;
		$this->name_manufacturer = !empty($data['name_manufacturer']) ? $data['name_manufacturer'] : null;
		
    }
	
	public function getArrayCopy()
    {
        return [
            'id'     => $this->id,
            'name' => $this->name,
			'id_color' => $this->id_color,
			'id_manufacturer' => $this->id_manufacturer,
			'shoe_img' => $this->shoe_img,
			
			'name_color' => $this->name_color,
			'name_manufacturer' => $this->name_manufacturer,
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
            'name' => 'id_color',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
		
        $inputFilter->add([
            'name' => 'id_manufacturer',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
		
		$inputFilter->add([
            'name' => 'shoe_img',
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

