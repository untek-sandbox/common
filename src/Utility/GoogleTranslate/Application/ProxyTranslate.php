<?php

namespace Untek\Utility\GoogleTranslate\Application;

use Psr\Container\ContainerInterface;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Untek\Component\Time\Enums\TimeEnum;
use Untek\Core\ConfigManager\Interfaces\ConfigManagerInterface;
use Untek\Core\Container\Traits\ContainerAttributeTrait;
use Untek\Core\Instance\Libs\Resolvers\ArgumentMetadataResolver;
use Untek\Core\Instance\Libs\Resolvers\InstanceResolver;

class ProxyTranslate
{

    protected ?string $source;

    protected ?string $target;

    public function __construct()
    {
    }

    public function getCache(): AdapterInterface
    {
        $cacheDirectory = getenv('CACHE_DIRECTORY');
        $adapter = new FilesystemAdapter('translate', TimeEnum::SECOND_PER_YEAR, $cacheDirectory);
//        $adapter->setLogger($container->get(LoggerInterface::class));
        return $adapter;
    }

    public function setTarget(string $target): self
    {
        $this->target = $target;
        return $this;
    }

    public function setSource(string $source = null): self
    {
        $this->source = $source ?? 'auto';
        return $this;
    }

    public function translate(string $string): ?string
    {
        
        $has = $this->getCache()->hasItem($string);
        
        if($has) {
            $item = $this->getCache()->getItem($string);
            $translate = $item->get();
        } else {
            $tr = new GoogleTranslate();
            $tr->setSource($this->source);
            $tr->setTarget($this->target);
            $translate = $tr->translate($string);
            $item = $this->getCache()->getItem($string);
            $item->set($translate);
            $this->getCache()->save($item);
        }
        
        return $translate;
    }
}