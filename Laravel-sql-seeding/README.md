My course PHP Development. Part Laravel

Formulation of the task:

Create an application that inserts/updates/deletes data in the database using eloquent and laravel framework. Use PostgreSQL DB.

Models have to have next field

    Group:

        name

    Student:

        group_id
        first_name
        last_name

    Course:

        name
        description

Create relation MANY-TO-MANY between tables STUDENTS and COURSES.

Create a laravel application

    Create migrations that create db scheme
    Write seeds that generate test data

    10 groups with randomly generated names. The name should contain 2 characters, hyphen, 2 numbers
    Create 10 courses (math, biology, etc)
    200 students. Take 20 first names and 20 last names and randomly combine them to generate students.
    Randomly assign students to groups. Each group could contain from 10 to 30 students. It is possible that some groups will be without students or students without groups
    Randomly assign from 1 to 3 courses for each student

Create pages that

    Find all groups with less or equals student count.
    Find all students related to the course with a given name.
    Add new student
    Delete student by STUDENT_ID
    Add a student to the course (from a list)
    Remove the student from one of his or her courses

Add REST-api and swagger.

Write tests using Phpunit. Add code coverage report.

********************************************************

**to start the task9 program, use:**

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
      npm cache clean --force    
      etc...

**it will be possible to test the application in a browser at localhost**

   http://localhost - You will see a frontend built using Laravel blades

   http://localhost/api/documentation - Swagger API documentation

   http://localhost/api/v1/students (... /groups) - API responses

