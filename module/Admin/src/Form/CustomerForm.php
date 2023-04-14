<?php
namespace Admin\Form;

use Laminas\Form\Form;
use Laminas\Form\Element;

class CustomerForm extends Form
{
    public function __construct()
    {
        // We will ignore the name provided to the constructor
        parent::__construct('customer');

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
		
		$this->add([
            'name' => 'email',
            'type' => 'text',
            'options' => [
                'label' => 'Adresse Email',
            ],
        ]);

		$this->add([
            'name' => 'password',
            'type' => 'text',
            'options' => [
                'label' => 'Mot de Passe',
            ],
        ]);
		
		
		
		$this->add([
            'name' => 'profil_img',
            'type' => 'text',
            'options' => [
                'label' => 'Image de profil',
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