La tabla colonias contiene más de 44,000 registros. 
Por lo que no se carga con php artisan db:seed (para evitar errores de memoria).
Para importar la tabla de Colonias debes de entar al contenedor del proyecto
y ejecutar el sig comando:
docker exec -i macasa_mariadb mysql -umacasa_user -pmacasa123 erp_ecommerce_db < database/seeders/sql/colonias.sql

Despues ir a tu gesto de base de datos y revisar si la tabla de colonias tiene los registros
correr el comando: docker exec -it macasa_erp bash
y despues correr los seeders para poder asi poblar de informacion los datos de las tablas
Estados y Ciudades con el comando php artisan db:seed