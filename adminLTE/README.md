<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Dmytro Kopylets


**Racing Report**

Write a web application using Laravel framework and your previous report task.

The application has to have a few routes.

http://localhost:5000/report - shows common statistic

http://localhost:5000/report/drivers/ - shows list of drivers name and code. Code should be a link on info about drivers

http://localhost:5000/report/drivers/?driver_id=SVF - shows info about a driver

http://localhost:5000/report/drivers/?order=desc - Also, each route could get order parameter

Use Blade for html template and AdminLTE.

Write tests using Phpunit. Add code coverage report.


********************************************************

**to start the task6 program, use:**

sail up -d
(or vendor/bin/sail ...

i added a line to my _.zshrc_:

**alias sail='[ -f sail ] && zsh sail || sh vendor/bin/sail'**

and now I can just use commands:
* sail build
* sail up -d
* sail stop

)

**it will be possible to test the application in a browser at localhost**


now port 5000 is not used.
But, if absolutely necessary, then in the file docker-compose.yml in line 14 it is enough to change 80 to 5000

