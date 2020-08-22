
## Instrucciones

1.- Descargar el Repositorio

2.- Renombrear el archivo .env.example a .env

3.- Ajustar las credenciales de acceso de la Base de datos

4.- Ejecutar composer install

5.- Ejecutar php artisan migrate

6.- Ejecutar npm install

7.- Ejecutar npm run dev

8.- Ejecutar php artisan serve

9.- Ejecutar en un navegador http://locahost:8000

10.- Registrar un usuario

11.- Acceder al sistema con el usuario creado


## API

Este sistema propociona las siguientes url

GET http://localhost:8000/api/restaurants/  --> index
POST http://localhost:8000/api/restaurants/  --> store
PUT http://localhost:8000/api/restaurants/{id} --> update
DELETE http://localhost:8000/api/restaurants/{id} --> delete
GET http://localhost:8000/api/restaurants/{id} --> show


GET http://localhost:8000/api/reservations/  --> index
POST http://localhost:8000/api/reservations/  --> store
PUT http://localhost:8000/api/reservations/{id} --> update
DELETE http://localhost:8000/api/reservations/{id} --> delete
GET http://localhost:8000/api/reservations/{id} --> show

