<?php

namespace Untek\Framework\Telegram\Domain\Dto;

class Photo
{

    private string $file_id;
    private string $file_unique_id;
    private int $file_size;
    private int $width;
    private int $height;

    /**
     * @return string
     */
    public function getFileId(): string
    {
        return $this->file_id;
    }

    /**
     * @param string $file_id
     */
    public function setFileId(string $file_id): void
    {
        $this->file_id = $file_id;
    }

    /**
     * @return string
     */
    public function getFileUniqueId(): string
    {
        return $this->file_unique_id;
    }

    /**
     * @param string $file_unique_id
     */
    public function setFileUniqueId(string $file_unique_id): void
    {
        $this->file_unique_id = $file_unique_id;
    }

    /**
     * @return int
     */
    public function getFileSize(): int
    {
        return $this->file_size;
    }

    /**
     * @param int $file_size
     */
    public function setFileSize(int $file_size): void
    {
        $this->file_size = $file_size;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

}