<?php

namespace Untek\Component\I18Next\Libs\BundleLoaders;

use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Core\Bundle\Base\BaseLoader;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Core\Contract\Common\Exceptions\ReadOnlyException;
use Untek\Component\I18Next\Facades\I18Next;

class I18NextLoader extends BaseLoader
{

    public function loadAll(array $bundles): void
    {
        /*$config = $this->getValueFromCache();
        if (empty($config)) {
            $config = $this->getConfig($bundles);
            $this->setValueToCache($config);
        }*/
        $config = $this->getConfig($bundles);
        $this->getConfigManager()->set('i18nextBundles', $config);
        $this->initI18Next();
    }

    protected function getConfig(array $bundles): array
    {
        $config = [];
        foreach ($bundles as $bundle) {
            $i18nextBundles = $this->load($bundle);
            $config = ArrayHelper::merge($config, $i18nextBundles);
        }
        return $config;
    }

    private function initI18Next()
    {
        $container = $this->getContainer();
        try {
            I18Next::setContainer($container);
        } catch (ReadOnlyException $exception) {
        }
    }
}
