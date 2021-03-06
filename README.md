# Countries-Api
search places by zip codes 

## Short overview on the project

backend:
DB: The backend relies on a MySQL DB composed of 2 tables,
The first table is the zip code table, containing a country's zip code, name and abbreviation
The second table is the places table, containing the name, longitude and latitude of the place or places in the zip code specified.
Table two also contains a foreign key of the zip code id that represents the combination of country and zip code in the first table.

countries_db.sql: contains the DB.

db_credentials.php: contains the information needed to connect to DB

db_connection.php: contains the connection function to DB

functions.php: contains the functions that query the database using sql queries for checking whether the user query exists in the DB or, if it doesn't, goes to the API, gets the needed information for the user query and inserts the relevant new tuples into the DB.

find_place.php: gets the information from the ajax call in index.php (will be described in the front end paragraph), calling the relevant function in functions.php and sending the response back to index.php, if it gets an error as a response, it sends the type of error back to index.php.

Front end:
index.php file: in charge of handling the users inputs (front end validations of user queries), sending the values to the server using ajax and handling the response that comes back, depending on the response (error/type of error/not error) it chooses the relevant information to show to the user.

style.css file: design.

## Set up

1.Install php on your computer.

2. Install XAMPP on your computer.

3. Enable MySQL serviece through the XAMPP control panel, and configure username password host and port.
4. Go to the db credential.php file and update the corresponding credentials.
5. if you like to change the default port of XAMMP (the default XAMPP port is 80) do the following:
- Stop Apache service from XAMPP control panel
- Open httpd.conf
- Change listen 80 to 8080 for example
- Change serverName localhost:80 to ServerName localhost:8080 
- Save the httpd.conf file
 OR you can use this short video guide to change it - https://www.youtube.com/watch?v=MaFB6od53Aw.
5. Import files from the git repository link - countries Api.
6. Import the countries_db.sql file to my MySQL to create the DB.
7. Make sure files are in the correct folder the project folder containing the files have to be inside XAMPP\htdocs in my case: C:\xampp\htdocs\countriesApi.
8. Browse to the URL: http://localhost:{XAMPP PORT}/YOURPROJECTFOLDER/index.php.
9. Once the url is up and running you can choose a country with the select input and type in any zip code you wnat, if the zip code you entered exists in the country you select you will get a list of places hat are in this zip code and some information about them.

Important Notes: 
If you encounter in a "caching_sha2" error please type in the following query in MySQL: ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password' 
-  Please dont forget to insert your data base cradentials to the db_cradentails.php file before running.


Thank you,
have fun!
