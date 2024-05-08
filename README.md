# Tourism Management System

## Description
This repository contains the source code for a Tourism Management System, which facilitates the management of tourism-related data, including destinations, bookings, and user authentication.

### Folder Structure
1. **assets**: Contains CSS and JavaScript files for styling and functionality.
2. **backend**: Includes files for database connection and data manipulation.
   - **assets**: Images uploaded by administrators.
   - **controller**: Code for database interaction.
   - **Routes File**: Routes for accessing system data.
3. **database**: SQL files for setting up the database schema and initial data.
4. **page**: Various page files categorized into subfolders based on user roles and functionality.
   - **admin**: Pages for administrators to manage destinations, bookings, etc.
   - **auth**: Pages for user authentication (login, registration).
   - **components**: Reusable components across pages.
   - **customer**: Pages for customers to browse destinations, make bookings, etc.
5. **index.php**: Entry point for the system.

## Usage
1. Clone the repository to your local machine.
2. Set up the database using the SQL files provided in the 'database' folder.
3. Configure the backend files to connect to your database server.
4. Host the files on a web server capable of running PHP scripts.
5. Access the system through the index.php file to manage tourism data and perform administrative tasks.
6. To run using Docker, execute the following command:

   docker-compose up -d


## Author
Dhimas Eka Prasetya

## Contact
Email: dimaseka83@gmail.com
