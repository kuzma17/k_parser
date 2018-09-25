<?php

namespace App\UrlItems;

interface UrlItem
{

    /**
     *  Gets the urls array of the items from one the page.
     */

    public function getUrl($dom);

}