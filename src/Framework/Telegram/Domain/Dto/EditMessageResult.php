<?php

namespace Untek\Framework\Telegram\Domain\Dto;

class EditMessageResult extends SendMessageResult
{

    private ?int $editDate = null;

    /**
     * @return int
     */
    public function getEditDate(): ?int
    {
        return $this->editDate;
    }

    /**
     * @param int $editDate
     */
    public function setEditDate(?int $editDate): void
    {
        $this->editDate = $editDate;
    }

}