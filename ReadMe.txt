===========================
Learn.dev Notes Portal
===========================

Welcome!  
This is a personal notes sharing portal where users can sign up, log in, upload notes in PDF format, view their notes, and delete them when needed.

------------------------------------------------------------
1. WHAT THIS PROJECT DOES
------------------------------------------------------------
• Lets you create a user account (Sign Up).  
• Allows you to log in with your username & password.  
• Lets you upload notes (PDF files only) with a title and description.  
• Stores your notes in your own personal folder on the server.  
• Lists your uploaded notes so you can open them or delete them.  
• Securely saves user information and notes in a database.  
• Lets you log out when done.

------------------------------------------------------------
2. WHAT YOU NEED BEFORE STARTING
------------------------------------------------------------
• A computer with **XAMPP** (or WAMP/MAMP) installed.  
  - XAMPP will provide:
    - PHP (to run the code)
    - MySQL (to store the data)
    - Apache (to serve the website)
• A web browser (Chrome, Edge, Firefox, etc.)
• Basic ability to copy and paste files.

------------------------------------------------------------
3. HOW TO SET IT UP
------------------------------------------------------------

STEP 1 — Install XAMPP  
- Download XAMPP
- Install it, then open the **XAMPP Control Panel**.  
- Start **Apache** and **MySQL**.

STEP 2 — Place the Project Files  
- Copy the entire project folder into:
  C:\xampp\htdocs\  
- For example:  
  C:\xampp\htdocs\learn_dev  

STEP 3 — Create the Database  
1. Open your browser and go to:
   http://localhost/phpmyadmin
2. Click the **SQL** tab.
3. Open the file `schema.sql` from the project folder.
4. Copy everything from it and paste into the SQL box.
5. Click **Go**.  
   This will create:
   - A database named `learn_dev`
   - A `users` table (for storing accounts)
   - A `notes` table (for storing uploaded notes)

STEP 4 — Database Connection Settings  
- Open the file `db_config.php` in a text editor.  
- Make sure the settings match your XAMPP install:
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db   = 'learn_dev';
- Usually you don’t need to change anything if using XAMPP defaults.

STEP 5 — Access the Website  
- In your browser, go to:
  http://localhost/learn_dev  
- You should see the login or register page.

------------------------------------------------------------
4. HOW TO USE THE WEBSITE
------------------------------------------------------------

**REGISTER A NEW ACCOUNT**  
- Click "Register".
- Fill in your username, email, and password.
- Submit the form — your account is saved in the database.

**LOG IN**  
- Use your username and password to log in.
- You’ll be redirected to your notes dashboard.

**UPLOAD NOTES**  
- Fill in the Title and Description.
- Choose a PDF file.
- Click "Upload".
- Your file will be stored in:
  `uploads/<your_username>/`  
- Information about your note is stored in the `notes` table.

**VIEW NOTES**  
- Your uploaded notes appear as cards.
- Click the card to open the PDF in a new tab.

**DELETE NOTES**  
- Click the delete icon on a note.
- This removes it from the server and the database.

**LOG OUT**  
- Click "Logout" to end your session.

------------------------------------------------------------
5. WHAT EACH FILE DOES
------------------------------------------------------------

**db_config.php** — Connects the site to the MySQL database.  

**index.php** — Main dashboard; lists your notes, handles file uploads, and stores note details in the database.  

**login.php** — Lets users log in by checking username & password in the database.  

**register.php** — Creates a new user account and saves it in the `users` table.  

**logout.php** — Ends the session so the user is logged out.  

**upload.php** — Handles the process of saving uploaded files to the server.  

**delete.php** — Removes a specific note from both the server and the database.  

**scripts.js** — Adds interactivity:
- Handles navigation tab switching.
- Shows selected file name.
- Opens notes when clicked.

**schema.sql** — The instructions to create the database tables.

**uploads/** — This folder stores each user's notes in a separate subfolder.
