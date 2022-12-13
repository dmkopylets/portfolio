<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

**Dmytro Kopylets**

My course PHP Development. Part Laravel

**Racing Report**

Write a web application using Laravel framework and your previous report task:

    There are 2 log files start.log and end.log that contain start and end data of the best lap for each racer of Formula 1 - Monaco 2018 Racing. (Start and end times are fictional, but the best lap times are true). Data contains only the first 20 minutes that refers to the first stage of the qualification.
    Q1: For the first 20 minutes (Q1), all cars together on the track try to set the fastest time. The slowest seven cars are eliminated, earning the bottom grid positions. Drivers are allowed to complete as many laps as they want during this short space of time.
    Top 15 cars are going to the Q2 stage. If you are so curious, you can read the rules here https://www.thoughtco.com/developing-saga-of-formula1-qualifying-1347189
    The third file abbreviations.txt contains abbreviation explanations.
    Parse hint:
    SVF2018-05-24_12:02:58.917
    SVF - racer abbreviation 
    2018-05-24 - date
    12:02:58.917 - time

    Your task is to read data from 2 files, order racers by time and print a report that shows the top 15 racers and the rest after underline.

    E.g.

    1. Daniel Ricciardo   | RED BULL RACING TAG HEUER | 1:12.013
    2. Sebastian Vettel   | FERRARI                   | 1:12.415
    3. ...
    ------------------------------------------------------------------------
    16. Brendon Hartley   | SCUDERIA TORO ROSSO HONDA | 1:13.179
    17. Marcus Ericsson   | SAUBER FERRARI            | 1:13.265

The application has to have a few routes.

http://localhost:5000/report - shows common statistic

http://localhost:5000/report/drivers/ - shows list of drivers name and code. Code should be a link on info about drivers

http://localhost:5000/report/drivers/?driverId=SVF - shows info about a driver
(Where SVF is abbreviation of driver)

http://localhost:5000/report/drivers/?order=desc - Also, each route could get order parameter

Use Blade for html template and AdminLTE.

Add to the previous web application REST API and add swagger.

The application has to have a version and format(json, xml) parameters.

    E.g.
    http://localhost:5000/api/v1/report/?format=json 

Create eloquent models.

You have to decide how many models you have to create.

Create MySQL / MariaDB / Postges database for application and SQLite db for tests. Write migrations to create a db scheme.

     (my docker config is Postges)

Convert and store data from files to the database.

Write a CLI-command that should parse and save data from files to a database.

    to run import use this:
    php import.php app:import

Write tests using Phpunit. Add code coverage report.

********************************************************

**to start the task8 program, use:**

sail up -d

or vendor/bin/sail ...

    (I added a line to my _.zshrc_:
    **alias sail='[ -f sail ] && zsh sail || sh vendor/bin/sail'**
    and now I can just use commands:
     sail build
     sail up -d
     sail stop)

You can also use classic commands:

      docker-compose exec php-fpm bash  
      for this (example):
      composer update

**it will be possible to test the application in a browser at localhost**


now port 5000 is not used.
But, if absolutely necessary, then in the file docker-compose.yml in line 12 or 13 it is enough to change the value to 5000
