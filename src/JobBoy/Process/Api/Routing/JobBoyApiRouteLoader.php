<?php
declare(strict_types=1);

namespace JobBoy\Process\Api\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class JobBoyApiRouteLoader extends Loader
{
    private $isLoaded = false;

    public function load($resource, $type = null)
    {
        if (true === $this->isLoaded) {
            throw new \RuntimeException('Do not add the "jobboy" loader twice');
        }

        $routes = new RouteCollection();

        $resource = __DIR__ . '/../Resources/config/routing.yaml';
        $type = 'yaml';

        $importedRoutes = $this->import($resource, $type);

        $routes->addCollection($importedRoutes);

        $this->isLoaded = true;

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'jobboy' === $type;
    }
}
