<?php

namespace App;

use App\Items\ItemSet;
use App\Items\WallpaperItemSet;
use App\Paginates\Paginate;
use App\Paginates\WallpaperPaginate;
use App\UrlItems\UrlItem;
use App\UrlItems\WallpaperUrlItem;
use Sunra\PhpSimple\HtmlDomParser;

class Parser extends HtmlDomParser
{
    private $host;
    private $path;
    public $out_file;
    public $delimiter = ';';
    public $portion = 20;
    public $max_page;

    private $p = 1;

    /**
     * Create properties.
     */

    public function __construct($dir, $settings)
    {
        $parse_url = parse_url($settings['url']);
        $this->host = $parse_url['scheme'] . '://' . $parse_url['host'];
        if (isset($parse_url['path'])) {
            $this->path = $parse_url['path'];
        }
        $this->out_file = $dir . '/' . $settings['out_file'];
        $this->delimiter = $settings['delimiter'];
        $this->portion = $settings['portion'];
        $this->max_page = $settings['quantity_page'];
    }

    /**
     * This function adapter to run the function getItem().
     */

    public function adaptGetItem($url, $htm)
    {
        $item_set = new WallpaperItemSet();
        return $this->getItem($item_set, $url, $htm);
    }

    /**
     * This function adapter to run the function getUrlItem().
     */

    public function adaptGetUrlItem()
    {
        $url_item = new WallpaperUrlItem();
        return $this->getUrlItem($url_item);
    }

    /**
     * This function adapter to run the function getPagePaginate().
     */

    public function adaptGetNextPage()
    {
        $paginate = new WallpaperPaginate();
        return $this->getNextPage($paginate);
    }

    /**
     * Recursive function run the parser.
     */

    public function startParser()
    {
        echo 'page: ' . $this->path . PHP_EOL;
        $this->writeFile();
        $page = $this->adaptGetNextPage();
        if ($page != $this->path) {
            $this->path = $page;
            if($this->max_page) {
                if ($this->p < $this->max_page) {
                    $this->p++;
                    $this->startParser();
                }
            }else{
                $this->startParser();
            }
        } else {
            echo 'No more pages' . PHP_EOL;
        }
    }

    /**
     * Gets the next page.
     */

    public function getNextPage(Paginate $paginate)
    {
        $dom = parent::file_get_html($this->host . $this->path);
        $page = $paginate->getNextPage($dom);
        $dom->clear();
        unset($dom);
        return $page;
    }

    /**
     *  Gets the urls array of the items from one the page.
     */

    public function getUrlItem(UrlItem $urlItem)
    {
        $dom = parent::file_get_html($this->host . $this->path);
        $elements = $urlItem->getUrl($dom);
        $result = [];
        foreach ($elements as $element) {
            $result[] = $element->href;
        }
        $dom->clear();
        unset($dom);
        return $result;
    }

    /**
     *  Gets the parameters of one element.
     */

    public function getItem(ItemSet $itemSet, $url, $htm)
    {
        $dom = parent::str_get_html($htm);
        $item[] = $this->host . $url;
        $item = array_merge($item, $itemSet->getItemSet($dom, $this->host));
        $dom->clear();
        unset($dom);
        return $item;
    }

    /**
     *  Runs n - quantity of threads of the process parsing.
     */

    function parallel_map($func, $urls) {
        $mh = curl_multi_init();
        $conn = [];
        $result = [];

        foreach ($urls as $i => $url) {
            $conn[$i] = curl_init($this->host.$url);
            curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($conn[$i], CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($conn[$i], CURLOPT_HEADER, 0);
            curl_setopt($conn[$i], CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($conn[$i], CURLOPT_SSL_VERIFYHOST, false);
            curl_multi_add_handle($mh, $conn[$i]);
            echo 'start '.$i. PHP_EOL;
        }

        do {
            $status = curl_multi_exec($mh, $active);
            $info = curl_multi_info_read($mh);
            if (false !== $info) {
            }
        } while ($status === CURLM_CALL_MULTI_PERFORM || $active);

        foreach ($urls as $i => $url) {
            $result[$url] = $this->$func($url, curl_multi_getcontent($conn[$i]));
            echo 'stop '.$i. PHP_EOL;
            curl_multi_remove_handle($mh, $conn[$i]);
            curl_close($conn[$i]);
        }

        return $result;
    }

    /**
     *  Writes data to the output file.
     */

    public function writeFile()
    {
        $urls = $this->adaptGetUrlItem();
        $urls = array_chunk($urls, $this->portion);
        $f = fopen($this->out_file, 'a');
        foreach ($urls as $url) {
            $items = $this->parallel_map('adaptGetItem', $url);
            foreach ($items as $item) {
                fputcsv($f, $item, $this->delimiter);
            }
        }
        fclose($f);
    }

    /**
     *  Cleans the output file.
     */

    public function cleanFile()
    {
        if (file_exists($this->out_file)) {
            file_put_contents($this->out_file, ''); // Clean file
        }
    }
}