Learn.dev – PDF Notes-Sharing Website
====================================

This guide walks you through every single click—no coding knowledge needed.
By the end you’ll be able to upload a PDF, watch it appear on the dashboard,
and let classmates download it.

----------------------------------------------------
1. What’s inside the “Learn.dev” folder?
----------------------------------------------------
Learn.dev/
│
├── index.php   →  The main web page (dashboard + upload form)
├── index.html  →  Alternative view used after first upload
├── upload.php  →  Handles the “Upload” button; puts files in the right place
├── delete.php  →  Removes a note and its file when you click “Delete”
├── db_config.php →  Knows *where* your database lives and how to log in
├── schema.sql  →  Creates one simple table called **notes** in MySQL
├── styles.css  →  Makes everything dark-mode and tidy
├── scripts.js  →  Switches between “Dashboard” and “Upload” tabs
└── uploads/    →  **Where every PDF is stored.** This folder is made automatically by the website if it doesn’t exist yet

----------------------------------------------------
2. Software you’ll need (all free)
----------------------------------------------------
• **XAMPP** for Windows or Linux – bundles Apache (web server) and MySQL
Download, install, then open the control-panel and press START for both
*Apache* and *MySQL* (two green lights mean you’re good).
----------------------------------------------------
3. Put Learn.dev in the right place
----------------------------------------------------
1) Find the “htdocs” (or XAMPP/htdocs”) folder that XAMPP created.  
2) Copy the entire **Learn.dev** folder into *htdocs*.  
   • Example (Windows): `C:\xampp\htdocs\Learn.dev`

Why here? – Anything inside *htdocs* can be seen in your browser at
`http://localhost/…`. That’s how Apache works.

----------------------------------------------------
4. Create the database (one-time step)
----------------------------------------------------
1) Open your browser and go to **http://localhost/phpmyadmin**  
2) Look for an **Import** tab, choose **schema.sql**, then click **Go**.  
   Job done—this file builds a tiny database called *notes_portal* with the
   **notes** table the site expects. :contentReference[oaicite:3]{index=3}

----------------------------------------------------
5. Tell the site how to reach MySQL
----------------------------------------------------
1) Double-click **db_config.php** to open it in Notepad.  
2) If you left the MySQL password blank during XAMPP setup (default), do nothing.
   Otherwise type your password between the quotes on the `$pass` line and save. :contentReference[oaicite:4]{index=4}

----------------------------------------------------
6. Launch Learn.dev in your browser
----------------------------------------------------
Visit **http://localhost/Learn.dev/index.php**  
You should see a dark dashboard titled **All Notes**.

----------------------------------------------------
7. Upload your first note
----------------------------------------------------
1) Click **Upload** (top right).  
2) Press **Choose PDF** and pick a file. The page only accepts “.pdf” files;
   any other type is politely refused by the code. :contentReference[oaicite:5]{index=5}  
3) Fill in **Title** and **Description** and hit **Upload**.  
   • The PDF lands in the **uploads** folder, created if missing. :contentReference[oaicite:6]{index=6}  
   • A row describing your file is added to the database. :contentReference[oaicite:7]{index=7}  
4) The dashboard refreshes and shows a new card with:  
   • A **download arrow** (click to grab the PDF—served from /uploads/) :contentReference[oaicite:8]{index=8}  
   • A **3-dot menu → Delete** (click to remove the record *and* the file) :contentReference[oaicite:9]{index=9}

That’s it—your Learn.dev site is live. Share the localhost link on your LAN or
move the folder to any hosting that supports PHP 8 and MySQL.
