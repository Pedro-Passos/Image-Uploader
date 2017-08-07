 *******************************************************
 * Building Web Applications using MySQL and PHP       *
 * Pedro Dos Passos                                    *
 * --------------------------------------------------- *
 *******************************************************
 
Documentation
-------------

 *General Description*
 
  The web application has the upload form at the top with all the thumbnails of user submitted images below.
  If none have been added yet the message "No results to display." will be displayed.
  Any image that has been uploaded will appear in the gallery automatically accompanied by the title of the image.
  Clicking on the image will take the user to a seperate view with a larger version with the maximum dimensions of 600x600 width and height.
  If the original image is smaller than these dimensions the original size of the image will be displayed.
  The title will de displayed at the top and the description below the image. Clicking on the image will then take the user back to the index page.
 
 *The upload form*
 
  The user begins by selecting an image and typing in the desired title and description all of which are mandatory and need to meet certain criteria.
  File type needs to be a valid jpeg image no bigger than 2MB.
  The title needs to be an alpha numeric string with atleast four characters and the description needs to be six or more characters and can inlude special characters.
 
 *File uploading*
 
  Any file that is submitted is checked by the processUpload() function where it is checked to see if it is a valid jpeg image.
  The function will also check if there isn't already a file in the gallery with the same name.
  If successful it is then moved to the "original" folder and processing continues.
  A thumbnail version is created with the img_resize() function with the dimensions of 150x150 and a larger version in the folder "large" with the dimensions 600x600.
  It is also important to note that if the images are smaller than the requisite dimensions of the resize function they will not be stretched in anyway.
  The images will also always maintain their original aspect ratio.
  Next the data is stored in a mysql database with the following properties: 
	Original image - file path, name, title, description.
	Large image - file name, width and height.
 
 *The Gallery*
 
  The gallery uses the function gallery() by connecting to the database and retrieving all the entries and then running them through a loop.
  The loop will also add the necessary html for them to be displayed with the title.
  When any of the images are clicked the img_view.php view is loaded and the clicked image name is passed in the url.
  We quey the database for the passed file name from the url and then display the large version of the image as well as the title and description.
  These are placed in the placeholder values in the lrg_template.html through the use of the lrg_template function().
  Code has also been added to return the user back to the index.php when the large version is clicked.
 
 
Installation
------------
 
  The application can be installed by unzipping the package and placing the files in the desired destination.
  Database settings can be updated through the config.inc.php.
  If you would like to create the tables in the database simply uncomment lines 22-27 in index.php to have them created.
  Code for creating the tables has also been included in the "sql" folder in MySQL format so you can copy/paste and then executing through MySQL workbench if preffered.
  The SQL queries used in this web application are also inside the "sql" folder.
  Templates used for the website are included in the "templates" folder.
  All images are stored in the "images folder" with three subfolders one for the original uploaded image then another for thumbnails and one for the large version.


JSON web application
--------------------

The following paramater needs to be passed to it: ?type=lrg_imgs
The JSON web service can be used to retrieve the title, description, filename, width and height of the large version of any image that has been uploaded to the application.
The data is returned from the service as a JSON object.
 