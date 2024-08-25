<?php

namespace Untek\Core\Contract\Encoder\Interfaces;

/**
 * Возможность кодирования/декодирования данных
 * 
 * Другими словами: сериализация/десериализация данных
 *
 * @todo наследовать от:
 * @see \Symfony\Component\Serializer\Encoder\EncoderInterface
 * @see \Symfony\Component\Serializer\Encoder\DecoderInterface
 */
interface EncoderInterface extends EncodeInterface, DecodeInterface
{

}
