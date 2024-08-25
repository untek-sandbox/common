<?php

namespace Untek\Component\Pattern\Singleton;

/**
 * Встраивание паттерна "Singleton"
 */
trait SingletonTrait
{

    /** @var static */
    private static $instance;

    private function __construct()
    {
    }
    
    /**
     * Получить экземпляр класса
     * @return static экземпляр класса
     */
    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = self::createInstance();
        }
        return self::$instance;
    }

    protected static function getConstructorParameters(): array
    {
        return [];
    }

    /**
     * Создать экземпляр класса
     * 
     * @param string|null $className
     * @return static
     */
    protected static function createInstance(string $className = null): self
    {
        $className = $className ?: static::class;
        $constructorParameters = static::getConstructorParameters();
        return new $className(...$constructorParameters);
    }
}
