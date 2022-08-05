Trootect Test Project - Rohit Sonawane

composer dump-autoload                      //to create vendor (if it fails use follows)
composer update --no-script                 //to update composer
composer update --ignore-platform-reqs      //to fullfill all requirements
php artisan key:generate                    //for key generation (if it fails then use follows)
php artisan passport:install                //to install passport package
php artisan optimize
php artisan serve

then make changes in .env file setting with table_name given in mysql

php artisan migrate:fresh


For api's and details of project, please find thunder-collection_trootech.json file in folder
