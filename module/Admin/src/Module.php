<?php

namespace Admin;

use Laminas\Db\Adapter\Driver\DriverInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;



class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
	
	public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\AlbumTable::class => function($container) {
                    $tableGateway = $container->get(Model\AlbumTableGateway::class);
                    return new Model\AlbumTable($tableGateway);
                },
                Model\AlbumTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Album());
                    return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
                },
				Model\ProductTable::class => function($container) {
                    $tableGateway = $container->get(Model\ProductTableGateway::class);
                    return new Model\ProductTable($tableGateway);
                },
                Model\ProductTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Product());
                    return new TableGateway('product', $dbAdapter, null, $resultSetPrototype);
                },
				Model\ColorTable::class => function($container) {
                    $tableGateway = $container->get(Model\ColorTableGateway::class);
                    return new Model\ColorTable($tableGateway);
                },
                Model\ColorTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Color());
                    return new TableGateway('color', $dbAdapter, null, $resultSetPrototype);
                },
				Model\ManufacturerTable::class => function($container) {
                    $tableGateway = $container->get(Model\ManufacturerTableGateway::class);
                    return new Model\ManufacturerTable($tableGateway);
                },
                Model\ManufacturerTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Manufacturer());
                    return new TableGateway('manufacturer', $dbAdapter, null, $resultSetPrototype);
                },
				Model\YearTable::class => function($container) {
                    $tableGateway = $container->get(Model\YearTableGateway::class);
                    return new Model\YearTable($tableGateway);
                },
                Model\YearTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Year());
                    return new TableGateway('year', $dbAdapter, null, $resultSetPrototype);
                },
				Model\ShoeTable::class => function($container) {
                    $tableGateway = $container->get(Model\ShoeTableGateway::class);
                    return new Model\ShoeTable($tableGateway);
                },
                Model\ShoeTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Shoe());
                    return new TableGateway('shoe', $dbAdapter, null, $resultSetPrototype);
                },
				Model\AuthorTable::class => function($container) {
                    $tableGateway = $container->get(Model\AuthorTableGateway::class);
                    return new Model\AuthorTable($tableGateway);
                },
                Model\AuthorTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Author());
                    return new TableGateway('author', $dbAdapter, null, $resultSetPrototype);
                },
				Model\AdminTable::class => function($container) {
                    $tableGateway = $container->get(Model\AdminTableGateway::class);
                    return new Model\AdminTable($tableGateway);
                },
                Model\AdminTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Admin());
                    return new TableGateway('admin', $dbAdapter, null, $resultSetPrototype);
                },
				Model\CategoryTable::class => function($container) {
                    $tableGateway = $container->get(Model\CategoryTableGateway::class);
                    return new Model\CategoryTable($tableGateway);
                },
                Model\CategoryTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Category());
                    return new TableGateway('category', $dbAdapter, null, $resultSetPrototype);
                },
				Model\CustomerTable::class => function($container) {
                    $tableGateway = $container->get(Model\CustomerTableGateway::class);
                    return new Model\CustomerTable($tableGateway);
                },
                Model\CustomerTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Customer());
                    return new TableGateway('customer', $dbAdapter, null, $resultSetPrototype);
                },
				Model\CartTable::class => function($container) {
                    $tableGateway = $container->get(Model\CartTableGateway::class);
                    return new Model\CartTable($tableGateway);
                },
                Model\CartTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Cart());
                    return new TableGateway('cart', $dbAdapter, null, $resultSetPrototype);
                },
				Model\ProductCartTable::class => function($container) {
                    $tableGateway = $container->get(Model\ProductCartTableGateway::class);
                    return new Model\ProductCartTable($tableGateway);
                },
                Model\ProductCartTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\ProductCart());
                    return new TableGateway('product_cart', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
	
	public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\AlbumController::class => function($container) {
                    return new Controller\AlbumController(
                        $container->get(Model\AlbumTable::class)
                    );
                },
				
				Controller\ProductController::class => function($container) {
                    return new Controller\ProductController(
                        $container->get(Model\ProductTable::class),
							$container->get(Model\AuthorTable::class),
								$container->get(Model\YearTable::class),
									$container->get(Model\ManufacturerTable::class),
										$container->get(Model\ColorTable::class),
											$container->get(Model\CategoryTable::class)
                    );
                },
				
				Controller\ColorController::class => function($container) {
                    return new Controller\ColorController(
                        $container->get(Model\ColorTable::class)
                    );
                },
				
				Controller\ManufacturerController::class => function($container) {
                    return new Controller\ManufacturerController(
                        $container->get(Model\ManufacturerTable::class)
                    );
                },
				Controller\YearController::class => function($container) {
                    return new Controller\YearController(
                        $container->get(Model\YearTable::class)
                    );
                },
				Controller\ShoeController::class => function($container) {
                    return new Controller\ShoeController(
                        $container->get(Model\ShoeTable::class),
							$container->get(Model\ColorTable::class),
								$container->get(Model\ManufacturerTable::class)
                    );
                },
				Controller\AuthorController::class => function($container) {
                    return new Controller\AuthorController(
                        $container->get(Model\AuthorTable::class)
                    );
                },
				Controller\AdminController::class => function($container) {
                    return new Controller\AdminController(
                        $container->get(Model\AdminTable::class)
                    );
                },
				Controller\LoginController::class => function($container) {
                    return new Controller\LoginController(
                        $container->get(Model\AdminTable::class)
                    );
                },
				Controller\CategoryController::class => function($container) {
                    return new Controller\CategoryController(
                        $container->get(Model\CategoryTable::class)
                    );
                },
				Controller\CustomerController::class => function($container) {
                    return new Controller\CustomerController(
                        $container->get(Model\CustomerTable::class),
						$container->get(Model\CartTable::class)
                    );
                },
				Controller\CartController::class => function($container) {
                    return new Controller\CartController(
                        $container->get(Model\CartTable::class),
							$container->get(Model\CustomerTable::class),
								$container->get(Model\ProductCartTable::class)
                    );
                },
				Controller\ProductCartController::class => function($container) {
                    return new Controller\ProductCartController(
                        $container->get(Model\ProductCartTable::class),
							$container->get(Model\ProductTable::class),
								$container->get(Model\CartTable::class)
                    );
                },
            ],
        ];
    }
}
