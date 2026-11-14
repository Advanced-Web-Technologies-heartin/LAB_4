# Amity University — Web Technologies Lab Work 4

A three-page student website demonstrating HTML, CSS, JavaScript and PHP:
- **Home:** an `iframe` loads `about.html`.
- **Registration Form:** JavaScript sends form data to the PHP backend.
- **Result Table:** PHP returns saved students as JSON; JavaScript builds the table and displays the class average.

## Project structure

```text
lab4/
├── frontend/
│   ├── index.html
│   ├── about.html
│   ├── form.html
│   ├── table.html
│   ├── css/style.css
│   ├── js/app.js
│   └── images/amity-logo.png
└── backend/
    ├── config.php
    ├── submit.php
    ├── get_students.php
    └── data/students.json
```

## Run on Windows with PHP

1. Install PHP 8.x or XAMPP.
2. Open this `lab4` folder in VS Code.
3. Open **Terminal → New Terminal**.
4. Run `php -v` to check PHP is installed.
5. In the terminal, make sure the current directory is the `lab4` folder and run:

   ```powershell
   php -S localhost:8000
   ```

6. Open this address in Chrome or Edge:

   `http://localhost:8000/frontend/index.html`

Keep the terminal open while using the website. Stop the server with **Ctrl + C**.

> Do not open the HTML files by double-clicking them or using Live Server alone. PHP must run through the PHP server for form submission and table loading to work.

## Tests to perform

1. Open Home: header, navigation and iframe should appear; backend status should show the number of saved students.
2. Navigate to Registration Form and submit valid details.
3. Confirm the success message appears.
4. Open Result Table: the new student, calculated grade and updated average should appear.
5. Try marks outside 0–100 or an already-used enrollment number; the backend should reject the submission.

## Grade scale

| Marks | Grade |
|---:|:---|
| 90–100 | A+ |
| 80–89 | A |
| 70–79 | B |
| 60–69 | C |
| Below 60 | D |

The four sample student records are included in `backend/data/students.json`. Replace or remove them if your instructor requires an initially empty database.

## Notes

- Student records are stored locally in `backend/data/students.json`.
- This is a lab demonstration, not a production student information system.
- The included logo is a simple project placeholder; replace it with the official university logo if your instructor requires the exact branding.
