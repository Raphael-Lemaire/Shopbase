<?php
namespace Admin;

use Laminas\Router\Http\Segment;

return [

 // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'admin_album' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin_album[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\AlbumController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
			'admin_product' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin_product[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ProductController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
			'admin_color' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin_color[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ColorController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
			'admin_manufacturer' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin_manufacturer[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ManufacturerController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
			'admin_year' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin_year[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\YearController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
			'admin_shoe' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin_shoe[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ShoeController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
			'admin_author' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin_author[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\AuthorController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
			
			'admin_admin' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin_admin[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
			'admin_login' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin_login[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\LoginController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
			'admin_category' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin_category[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\CategoryController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
			'admin_customer' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin_customer[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\CustomerController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
			'admin_cart' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin_cart[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\CartController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
			'admin_product_cart' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/admin_product_cart[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ProductCartController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],
];
