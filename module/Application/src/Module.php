<?php

declare(strict_types=1);

namespace Application;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }
	
	public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\IndexController::class => function($container) {
                    return new Controller\IndexController(
                        $container->get(\Admin\Model\ProductTable::class)
                    );
                },
				Controller\ProductController::class => function($container) {
                    return new Controller\ProductController(
                        $container->get(\Admin\Model\ProductTable::class),
						$container->get(\Admin\Model\AuthorTable::class),
						$container->get(\Admin\Model\YearTable::class),
						$container->get(\Admin\Model\ManufacturerTable::class),
						$container->get(\Admin\Model\ColorTable::class),
						$container->get(\Admin\Model\CategoryTable::class)
                    );
                },
				 Controller\AjaxController::class => function($container) {
                    return new Controller\AjaxController(
						$container->get(\Admin\Model\ProductTable::class),
						$container->get(\Admin\Model\CartTable::class),
						$container->get(\Admin\Model\ProductCartTable::class)
						
                    );
                },	
				 Controller\CartController::class => function($container) {
                    return new Controller\CartController(
						$container->get(\Admin\Model\ProductCartTable::class),
						$container->get(\Admin\Model\CartTable::class)
                    );
                },
				Controller\LoginController::class => function($container) {
                    return new Controller\LoginController(
						$container->get(\Admin\Model\CustomerTable::class),
						$container->get(\Admin\Model\CartTable::class)
                    );
                },
            ],
        ];
    }
}
