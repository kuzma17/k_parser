<?php

   /*
   |--------------------------------------------------------------------------
   | Instructions for use
   |--------------------------------------------------------------------------
   |
   | This script must be run in console mode.
   |
   | "portion" - quantity run thread.
   | The more the more threads and the speed of parsing.
   | But do not take much, as the host can ban.
   | Recommended to select a multiple of the quantity of elements on the page.
   | 'quantity_page' - quantity of pages parsed.
   | If 'quantity_page' => 0, will be parsed all pages.
   |
   */

return[
    /*
     * Original URL
     */

    'url' => 'https://interyerus.ru/oboi/',

    /*
     * Output file csv
     */

    'out_file' => 'out_file.csv',

    /*
     * Delimiter
     */

    'delimiter' => ';',

    /*
     * Quantity run thread
     */

    'portion' => 20,

    /*
     * Quantity of pages parsed
     */

    'quantity_page' => 0
];
