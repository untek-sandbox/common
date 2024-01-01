<?php

namespace Untek\Bundle\Notify\Domain\Interfaces\Repositories;

interface FlashRepositoryInterface
{

    /**
     * Adds a flash message for the given type.
     *
     * @param string $type
     * @param mixed  $message
     */
    public function add(string $type, string $message, int $delay);

    /**
     * Registers one or more messages for a given type.
     *
     * @param string       $type
     * @param string|array $messages
     */
    //public function set($type, $messages);

    /**
     * Gets flash messages for a given type.
     *
     * @param string $type    Message category type
     * @param array  $default Default value if $type does not exist
     *
     * @return array
     */
    //public function peek($type, array $default = []);

    /**
     * Gets all flash messages.
     *
     * @return array
     */
    //public function peekAll();

    /**
     * Gets and clears flash from the stack.
     *
     * @param string $type
     * @param array  $default Default value if $type does not exist
     *
     * @return array
     */
    //public function get($type, array $default = []);

    /**
     * Gets and clears flashes from the stack.
     *
     * @return array
     */
    //public function all();

    /**
     * Sets all flash messages.
     */
    //public function setAll(array $messages);

    /**
     * Has flash messages for a given type?
     *
     * @param string $type
     *
     * @return bool
     */
    //public function has($type);

    /**
     * Returns a list of all defined types.
     *
     * @return array
     */
    //public function keys();

}

