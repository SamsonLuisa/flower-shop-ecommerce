# Grădina cu Flori - Flower Shop

## About

A web application for a flower shop with basic features: product browsing, user authentication, and administration.

## Technologies

- PHP + MySQL (with PDO)
- Apache (web server)
- Docker (containerization)
- Bootstrap 5 (frontend)

## How to run

1. Start Docker containers
docker-compose up --build

2. Open in browser
http://localhost:8080

3. Open in browser
http://localhost:8081

Server: mysql_db
User: root
Password: toor

## Project structure

src/
├── index.php              # Homepage
├── about.php              # About Us
├── flori.php              # All products
├── listaflori.php         # Flowers only
├── buchetespeciale.php    # Bouquets
├── decoratiuni.php        # Decorations
├── bazaDeDateFlori.php    # ADMIN - Products
├── conturi.php            # ADMIN - Users
├── login_form.php         # Login
├── registerform.php       # Register
├── upload.php             # Add product
├── editimage.php          # Edit product
├── view.php               # View product
├── deleteimage.php        # Delete product
├── connection.php         # DB connection
├── clase.php              # PHP classes
└── multimedia/            # Uploaded images
