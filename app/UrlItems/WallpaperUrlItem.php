<?php

namespace App\UrlItems;

class WallpaperUrlItem implements UrlItem
{

    /**
     *  Gets the urls array of the items from one the page.
     */

    public function getUrl($dom)
    {
        $element = $dom->find('div.product a.product--name');
        return $element;
    }

}

