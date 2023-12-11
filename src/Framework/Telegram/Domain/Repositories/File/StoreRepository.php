<?php

namespace Untek\Framework\Telegram\Domain\Repositories\File;

use Untek\Component\FormatAdapter\StoreFile;

class StoreRepository
{

    public function __construct(private string $lockFile)
    {
    }

    public function getLastId()
    {
        $storeInstance = $this->getStoreInstance();
        $storeData = $storeInstance->load();
        $lastId = $storeData['last_update_id'] ?? null;
        return $lastId;
    }

    public function setLastId($lastId)
    {
        $storeInstance = $this->getStoreInstance();
        $storeData = $storeInstance->load();
        $storeData['last_update_id'] = $lastId;
        $storeInstance->save($storeData);
    }
    
    private function getStoreInstance(): StoreFile {
        $storeFile = new StoreFile($this->lockFile);
        return $storeFile;
    }
}
