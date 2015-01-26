<?php

namespace Detail\FileConversion\Client\Response;

interface ListResponseInterface
{
    /**
     * @return array
     */
    public function getItems();

    /**
     * @return integer
     */
    public function getPageCount();

    /**
     * @return integer
     */
    public function getPageSize();

    /**
     * @return integer
     */
    public function getItemCount();

    /**
     * @return integer
     */
    public function getTotalItemCount();
}
