<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\ThemeBundle\Translation\Provider\Resource;

use Sylius\Bundle\ThemeBundle\Translation\Resource\TranslationResource;
use Sylius\Bundle\ThemeBundle\Translation\Resource\TranslationResourceInterface;

final class SymfonyTranslatorResourceProvider implements TranslatorResourceProviderInterface
{
    /** @var TranslationResourceInterface[] */
    private array $resources = [];

    private array $resourcesLocales = [];

    private array $filepaths;

    public function __construct(array $filepaths = [])
    {
        $this->filepaths = $filepaths;
    }

    public function getResources(): array
    {
        $this->initializeIfNeeded();

        return $this->resources;
    }

    public function getResourcesLocales(): array
    {
        $this->initializeIfNeeded();

        return $this->resourcesLocales;
    }

    /**
     * 1. The function does not initialize if needed. It initializes everytime it is executed.
     * 2. The function appends resources and locales each time it is executed, meaning that if I have 100 translation
     * files and execute it
     */
    private function initializeIfNeeded(): void
    {
        $this->resources = [];
        $this->resourcesLocales = [];

        foreach ($this->filepaths as $key => $filepath) {
            $resource = new TranslationResource($filepath);

            $this->resources[] = $resource;
            $this->resourcesLocales[] = $resource->getLocale();
        }

        $this->resourcesLocales = array_unique($this->resourcesLocales);
        $this->filepaths = [];
    }
}
