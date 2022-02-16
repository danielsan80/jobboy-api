<?php
declare(strict_types=1);

namespace JobBoy\Process\Api\DependencyInjection\Helper;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class ServiceLoader
{
    public function load(ContainerBuilder $container)
    {
        $this->ensureSecurityComponentIsAvailable();

        $locator = new FileLocator(__DIR__ . '/../config');
        $loader = new DirectoryLoader($container, $locator);
        $resolver = new LoaderResolver([
            new YamlFileLoader($container, $locator),
            $loader,
        ]);
        $loader->setResolver($resolver);

        $loader->load('services');

        return $container;
    }

    private function ensureSecurityComponentIsAvailable(): void
    {
        if (!class_exists(AccessDeniedException::class)) {
            throw new \LogicException('The Security component is not available. Try running "composer require symfony/security-bundle".');
        }
    }
}
