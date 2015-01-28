<?php

namespace Detail\FileConversion\Client\Response;

abstract class ListResponse extends BaseResponse implements
    ListResponseInterface
{
    /**
     * @return array
     */
    abstract public function getItems();

    /**
     * @return integer
     */
    public function getPageCount()
    {
        return (int) $this->getResult('page_count');
    }

    /**
     * @return integer
     */
    public function getPageSize()
    {
        return (int) $this->getResult('page_size');
    }

    /**
     * @return integer
     */
    public function getItemCount()
    {
        return count($this->getItems());
    }

    /**
     * @return integer
     */
    public function getTotalItemCount()
    {
        return (int) $this->getResult('total_items');
    }
}
