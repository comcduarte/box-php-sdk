<?php
namespace Laminas\Box\Service;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Laminas\Box\API\AccessToken;

class DefaultAccessTokenFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new AccessToken($container->get('access-token-config'));
    }
}