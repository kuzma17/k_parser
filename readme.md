
## K-parser
This is a script for parsing the Internet resources.<br>
The script writes the results to a file csv.<br>

<strong>This script must be run in console mode.</strong><br>
If you try to start from the browser, there will be a "Fatal error: Uncaught Error: Call to undefined function App\pcntl_fork()"

#### Running the script in the console
Example, if you run from the root directory of the script<br>
<strong>php index.php</strong>

#### Options
"portion" - quantity run thread.<br>
The more the more threads and the speed of parsing.<br>
But do not take much, as the host can ban.<br>
Recommended to select a multiple of the quantity of elements on the page. 
'quantity_page' - quantity of pages parsed.<br>
If 'quantity_page' => 0, will be parsed all pages.