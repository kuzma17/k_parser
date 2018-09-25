<?php

namespace App\Paginates;

interface Paginate
{

    /**
     * Gets the next page.
     */

    public function getNextPage($dom);
}