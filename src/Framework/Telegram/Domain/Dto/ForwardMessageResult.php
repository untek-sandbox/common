<?php

namespace Untek\Framework\Telegram\Domain\Dto;

class ForwardMessageResult extends SendMessageResult
{

    private int $forwardDate;
    private From $forwardFrom;

    /**
     * @return int
     */
    public function getForwardDate(): int
    {
        return $this->forwardDate;
    }

    /**
     * @param int $forwardDate
     */
    public function setForwardDate(int $forwardDate): void
    {
        $this->forwardDate = $forwardDate;
    }

    /**
     * @return From
     */
    public function getForwardFrom(): From
    {
        return $this->forwardFrom;
    }

    /**
     * @param From $forwardFrom
     */
    public function setForwardFrom(From $forwardFrom): void
    {
        $this->forwardFrom = $forwardFrom;
    }
}