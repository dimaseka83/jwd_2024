**Repository Name: Tourism Management System**

**Description:**
This repository contains the source code for a Tourism Management System, which facilitates the management of tourism-related data, including destinations, bookings, and user authentication. The system comprises several folders and files, each serving a specific purpose as outlined below:

1. **assets**: This folder contains CSS and JavaScript files utilized throughout the system's pages to ensure consistent styling and functionality.

2. **backend**: This folder encompasses files responsible for connecting the system to the database. It includes:
   - **assets**: A subfolder containing assets such as images uploaded by administrators.
   - **controller**: A subfolder housing code responsible for interacting with the database, managing data retrieval, insertion, and deletion.
   - **Routes File**: This non-folder file contains routes used to access data within the system.

3. **database**: This folder stores SQL files necessary for setting up the system's database schema and initial data.

4. **page**: This folder comprises various page files, organized into subfolders based on user roles and functionality:
   - **admin**: Contains pages accessible to administrators for managing destinations, bookings, and other administrative tasks.
   - **auth**: Contains pages for user authentication, including login and registration functionalities.
   - **components**: Houses reusable components used across different pages for efficiency and consistency.
   - **customer**: Contains pages accessible to customers for browsing destinations, making bookings, and viewing their bookings.

5. **index.php**: This file serves as the entry point for the system, automatically opening upon initial access.

**Usage:**
1. Clone the repository to your local machine.
2. Set up the database using the SQL files provided in the 'database' folder.
3. Configure the backend files to connect to your database server.
4. Host the files on a web server capable of running PHP scripts.
5. Access the system through the index.php file to begin managing tourism data, authenticate users, and perform administrative tasks.

**Author:**
Dhimas Eka Prasetya

**Contact:**
Email : dimaseka83@gmail.com