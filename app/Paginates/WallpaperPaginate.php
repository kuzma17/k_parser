<?php

namespace App\Paginates;

class WallpaperPaginate implements Paginate
{

    /**
     * Gets the next page.
     */

    public function getNextPage($dom)
    {

        $element = $dom->find('ul#yw0 li.selected', 0);
        $element = $element->next_sibling()->find('a', 0);
        $element = $element ->href;
        return $element;
    }
}