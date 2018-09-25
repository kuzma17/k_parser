<?php

namespace App\Items;

class WallpaperItemSet implements ItemSet
{

    /**
     * Gets the parameters of one element.
     */

    public function getItemSet($dom, $host)
    {
        $item = [];
        $element = $dom->find('div.card--image-block div.card--image a img', 0);
        if($element){
            $item[] = trim($element->alt);
            $item[] = trim($host.$element->src);
        }

        $element = $dom->find('div.product--price span.current span.s16', 0);
        if($element){
            $item[] = trim($element->text());
        }

        $elements = $dom->find('section.card--params table td p');
        if(count($elements)){
            foreach ($elements as $element) {
                $text = $element->text();
                $text = preg_replace('/\s+/', ' ', $text);
                $options = explode(':', $text);
                $item[] = trim($options[1]);
            }
        }

        return $item;
    }

}