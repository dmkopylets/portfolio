**Dmytro Kopylets**

**to start the program in the command line, use:**

php index.php app:report --files <folder_path> [--asc | --desc]
_shows list of drivers and optional order (default order is asc)_

_shows statistic about driver :_
php index.php app:report --files <folder_path>  driver “Sebastian Vettel”

***************************
**Formulation of the problem "Racing Report":**

<p dir="ltr">There are 2 log files start.log and end.log that contain start and end data of the best lap for each racer of Formula 1 - Monaco 2018 Racing. (Start and end times are fictional, but the best lap times are true). Data contains only the first 20 minutes that refers to the first stage of the qualification.</p>
<p dir="ltr">Q1: For the first 20 minutes (Q1), all cars together on the track try to set the fastest time. The slowest seven cars are eliminated, earning the bottom grid positions. Drivers are allowed to complete as many laps as they want during this short space of time.</p>
<p dir="ltr">Top 15 cars are going to the Q2 stage. If you are so curious, you can read the rules here https://www.thoughtco.com/developing-saga-of-formula1-qualifying-1347189</p>
<p dir="ltr">The third file abbreviations.txt contains abbreviation explanations.</p>
<p dir="ltr"></p>
<p dir="ltr">Parse hint:</p>
<p dir="ltr">SVF2018-05-24_12:02:58.917</p>
<p dir="ltr">SVF - racer abbreviation&nbsp;</p>
<p dir="ltr">2018-05-24 - date</p>
<p dir="ltr">12:02:58.917 - time</p>
<p dir="ltr">Your task is to read data from 2 files, order racers by time and print a report that shows the top 15 racers and the rest after underline.</p>
<p dir="ltr"><strong>E.g.</strong></p>
<p dir="ltr">1. Daniel Ricciardo&nbsp; &nbsp; &nbsp; | RED BULL RACING TAG HEUER&nbsp; &nbsp; &nbsp;| 1:12.013</p>
<p dir="ltr">2. Sebastian Vettel&nbsp; &nbsp; &nbsp; | FERRARI&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; | 1:12.415</p>
<p dir="ltr">3. ...</p>
<p dir="ltr">------------------------------------------------------------------------</p>
<p dir="ltr">16. Brendon Hartley&nbsp; &nbsp;| SCUDERIA TORO ROSSO HONDA | 1:13.179</p>
<p dir="ltr">17. Marcus Ericsson&nbsp; | SAUBER FERRARI&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; | 1:13.265</p>
<p dir="ltr"><strong>Requirements:</strong></p>
<ul>
<li>Data files should be stored in a separate folder.</li>
<li>You should have two general functions like a "build_report" and a "print_report".</li>
<li>The "print_report" function should get the result of the "build_report" function and generate an html report.</li>
<li>Add a command-line interface. The application has to have a few parameters.</li>
</ul>
<p dir="ltr"><strong>E.g.</strong></p>
<p dir="ltr">php index.php app:report --files &lt;folder_path&gt; [--asc | --desc]&nbsp; shows list of drivers and optional order (default order is asc)</p>
<p dir="ltr">php index.php app:report --files &lt;folder_path&gt; driver “Sebastian Vettel”&nbsp; shows statistic about driver&nbsp;</p></div>
