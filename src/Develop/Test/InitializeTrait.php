<?php

namespace Untek\Develop\Test;

// todo: написать доку
trait InitializeTrait
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->runInitializeMethods();
    }

    /**
     * Выполнение методов инициализации
     *
     * Для расширения возможностей можете создавать методы в трейтах и классах
     * с префиксом `initialize`, эти методы будут выполняться до запуска тест-кейса.
     * Эти методы должны быть винды внутри класса, помечаем их как protected.
     */
    protected function runInitializeMethods(): void
    {
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (str_starts_with($method, 'initialize')) {
                call_user_func([$this, $method]);
            }
        }
    }
}
