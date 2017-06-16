# EMQ - Electronic Retail Store

Emmanuel Mendoza, Frederic Murry, David Navarro, Michael Nguyen, Ha Nguyen

San Jose State University - Fall 2016 - CS 160 - Software Engineering

## Where to place the repository

For Windows:

Place this repository into your C:/xampp/htdocs folder

When complete, you should have all our repository files within C:/xampp/htdocs/emq folder

For Mac:

Place this repository into your Applications/XAMPP/htdocs folder

When complete, you should have all our repository files within Applications/XAMPP/htdocs/emq folder

## Setting up the database

This needs to be done if it's your first time pulling the repository.

You'll need to create the database that will be used in laravel.

Start XAMPP with Apache and MySQL activated then visit this site on your browser.

[http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)

On the left hand side you should see a small database icon with a green/white plus sign and the word New. Click it.

Then create a database with the name "**emqdb**"

Next you will open a console within your emq directory and run the following command

**php artisan migrate:refresh --seed**

Everything should now be setup. Test by visiting the link below and creating a user.

[http://localhost/emq/public/](http://localhost/emq/public)

Also, test to see if you are able to see the mock products json api.

[http://localhost/emq/public/api?data=products](http://localhost/emq/public/api?data=products)
