<?php

use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;
use Untek\Core\Container\Libs\Container;
use Symfony\Component\Console\Application;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Core\FileSystem\Helpers\FilePathHelper;
use Untek\Framework\Console\Symfony4\Helpers\CommandHelper;
use Untek\Crypt\Pki\Domain\Libs\Rsa\RsaStoreFile;
use Untek\Lib\Components\Time\Enums\TimeEnum;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Untek\Lib\Rest\Symfony4\Helpers\RestApiControllerHelper;
use Untek\Core\FileSystem\Helpers\FileHelper;

/**
 * @var Application $application
 * @var Container $container
 */

// --- Generator ---

/** @var ContainerConfiguratorInterface $containerConfigurator */
$containerConfigurator = $container->get(ContainerConfiguratorInterface::class);
//$containerConfigurator = ContainerHelper::getContainerConfiguratorByContainer($container);
$containerConfigurator->bind(RsaStoreFile::class, function () {
    $rsaDirectory = getenv('RSA_CA_DIRECTORY');
    return new RsaStoreFile($rsaDirectory);
}, true);
$containerConfigurator->bind(AbstractAdapter::class, function () {
    $cacheDirectory = getenv('CACHE_DIRECTORY');
    return new FilesystemAdapter('cryptoSession', TimeEnum::SECOND_PER_DAY, $cacheDirectory);
}, true);

/*$container->bind(RsaStoreFile::class, function () {
    $rsaDirectory = FileHelper::rootPath() . '/' . getenv('RSA_CA_DIRECTORY');
    return new RsaStoreFile($rsaDirectory);
}, true);
$container->bind(AbstractAdapter::class, function () {
    $cacheDirectory = FileHelper::rootPath() . '/' . getenv('CACHE_DIRECTORY');
    return new FilesystemAdapter('cryptoSession', TimeEnum::SECOND_PER_DAY, $cacheDirectory);
}, true);*/

CommandHelper::registerFromNamespaceList([
    'Untek\Crypt\Pki\Symfony4\Commands',
], $container);
