# Home Builder Management System (Minimal MVC Starter)

This repository contains a minimal custom PHP MVC skeleton for the Home Builder Management System. It's intended as a starting point to implement the Admin, Builder, and Client workflows.

## Setup (XAMPP on Windows)

1. Start Apache and MySQL from XAMPP Control Panel.
2. Import `database_schema.sql` into MySQL (use phpMyAdmin or mysql CLI).
3. Adjust DB credentials in `Config/config.php` if needed.
4. Place the project in `C:\xampp\htdocs\homebuilder` (already assumed).
5. Open in browser: http://localhost/homebuilder/index.php

## Routes
- `/index.php?url=auth` - Login page
- `/index.php?url=auth/register` - Register
- `/index.php?url=admin` - Admin dashboard (requires admin role)
- `/index.php?url=builder` - Builder dashboard (requires builder role)
- `/index.php?url=client` - Client dashboard (requires client role)

## Next steps
- Implement CRUD controllers and views for projects, quotations, and materials.
- Add validation, CSRF protection, and input sanitization.
- Implement file uploads for project progress photos.
