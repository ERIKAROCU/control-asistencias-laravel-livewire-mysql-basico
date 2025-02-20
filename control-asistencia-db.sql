CREATE DATABASE control_asistencias_laravel_basico;

USE control_asistencias_laravel_basico;

select*from feriados;
select*from empleados;
select*from asistencias;
select*from control_asistencias;
select*from hora_defecto;

delete from asistencias where id = 13;
-- TRUNCATE TABLE control_asistencias;


-- drop database control_asistencias_laravel_basico;