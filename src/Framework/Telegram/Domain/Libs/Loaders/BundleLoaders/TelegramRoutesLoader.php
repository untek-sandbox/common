<?php

namespace Untek\Framework\Telegram\Domain\Libs\Loaders\BundleLoaders;

use Untek\Core\Bundle\Base\BaseLoader;
use Untek\Core\Arr\Helpers\ArrayHelper;

class TelegramRoutesLoader extends BaseLoader
{

    public function loadAll(array $bundles): void
    {
        $config = [];
        foreach ($bundles as $bundle) {
            $containerConfigList = $this->load($bundle);
            if ($containerConfigList) {
                $config = ArrayHelper::merge($config, $containerConfigList);
            }
        }
        $this->getConfigManager()->set('telegramRoutes', $config);
    }
}
