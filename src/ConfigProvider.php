<?php

declare(strict_types=1);

namespace Laminas\Box;

use Laminas\Box\API\AccessToken;

class ConfigProvider
{
    /**
     * Return general-purpose configuration.
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
            'aliases' => [
                'access-token' => AccessToken::class,
            ],
            'factories'          => [
                AccessToken::class => Service\DefaultAccessTokenFactory::class,
            ],
        ];
    }
}
