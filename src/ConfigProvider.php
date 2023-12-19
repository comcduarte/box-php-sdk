<?php

declare(strict_types=1);

namespace Laminas\Box;

class ConfigProvider
{
    /**
     * Return general-purpose laminas-navigation configuration.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
        ];
    }
    
    /**
     * Return application-level dependency configuration.
     *
     * @return array
     */
    public function getDependencyConfig()
    {
        return [
            'factories'          => [
                'access-token' => Service\DefaultAccessTokenFactory::class,
            ],
        ];
    }
}
