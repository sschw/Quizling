#############################
### HOW TO SETUP QUIZling ###
#############################

Before you can use QUIZling you have to modify the basepath in the config file (config/config.conf), the basepath in the htaccess file and the db connection data in the DBController file (lib/database/DBController.php).

##########################################
### HOW TO SETUP DATABASE FOR QUIZling ###
##########################################

Create the Database quiz and run the SQL Scripts from lib/database/init.sql.

#############
### Login ###
#############

After setting up the database, your quiz is ready to start. Just Login into the admin panel with username/password: root/root

#############
### Note: ###
#############

This project is finished but isn't bug free.
Also the quiz tries to connect to the google tts api to get the sound files. To perform this the application tries to open a connection to the api but on most free providers this feature is blocked.