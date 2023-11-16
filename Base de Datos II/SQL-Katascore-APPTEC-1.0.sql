#CONSULTAS SIGT 2023 - APPTEC 

use bd_apptec;

#1. Obtener a los competidores de un torneo o fecha, ordenado por torneo y por la fecha en la cual se realiza el torneo.
#(cédula, nombre, apellido, escuela, nombre_torneo, fecha, categoría, género)
select c.id_competidor as CI, c.nombre as nombre_competidor, c.apellido, c.escuela, comp.nombre  nombre_competencia, comp.fecha, r.categoria,
case when c.sexo = 'M' then 'Masculino' when c.sexo = 'F' then 'Femenino' end as genero
from competidor c
join registra r on c.id_competidor = r.id_competidor
join competencia comp on r.id_competencia = comp.id_competencia
order by nombre_competencia, fecha;

#2. Listado de katas realizadas durante el año actual, donde aquellos katas que no han sido ejecutadas aparezcan con una cantidad de cero.
#El listado debe estar organizado en orden descendente, mostrando primero las Katas más ejecutadas y descendiendo hacia las menos ejecutadas.
#(número_kata, nombre_kata, cantidad)
select kata.id_kata, kata.nombre,
(select count(*) from realiza
join ejecucion on realiza.id_ejecucion = ejecucion.id_ejecucion
join competencia on ejecucion.id_competencia = competencia.id_competencia
where year (competencia.fecha) = 2023 and realiza.id_kata = kata.id_kata) as cantidad
from kata
order by cantidad desc;

#3. Calificaciones de un torneo conocido en una categoría conocida, una vez finalizado el torneo.
#(nombre, apellido, puntaje)
select competidor.nombre, competidor.apellido, ejecucion.puntaje_total
from competidor 
join ejecucion on competidor.id_competidor = ejecucion.id_competidor
join competencia on ejecucion.id_competencia = competencia.id_competencia
where ejecucion.categoria = 'Mayores Masculino' and competencia.nombre = 'Competencia ISBO 2023';

#4. Registro de los Katas realizados por un competidor en particular en cada ronda de un torneo conocido.
#(nombre, apellido, número_kata, nombre_kata, ronda, nombre_torneo, puntaje)
select competidor.nombre, competidor.apellido, kata.id_kata , kata.nombre, ejecucion.ronda, competencia.nombre, ejecucion.puntaje_total
from competidor
join ejecucion on competidor.id_competidor = ejecucion.id_competidor
join realiza on ejecucion.id_ejecucion = realiza.id_ejecucion
join kata on realiza.id_kata = kata.id_kata
join competencia on ejecucion.id_competencia = competencia.id_competencia
where competencia.nombre = 'Competencia ISBO 2023' and competidor.id_competidor = 45434618;

#5. Obtener grupos de competidores del sembrado para el torneo actual de una categoría conocida.
#Se pretende obtener los grupos para la primera ronda de una categoría del torneo actual.
#(cédula, nombre, apellido, nombre_torneo, categoría, género, pool(AKA/AO))
select c.id_competidor as cedula, c.nombre, c.apellido, comp.nombre as nombre_torneo, r.categoria, c.sexo, e.cinturon as pool
from competidor c
join registra r on c.id_competidor = r.id_competidor
join competencia comp on r.id_competencia = comp.id_competencia
join ejecucion e on c.id_competidor = e.id_competidor
where comp.id_competencia = 11 and r.categoria = 'Mayores Masculino' and e.ronda = 'Primera Ronda'
order by pool;

#7. Listar las rondas en las que se ejecutó un kata específico.
#(nombre_torneo, ronda, nombre_kata)
select competencia.nombre as nombre_competencia, ejecucion.ronda, kata.nombre as kata
from competencia
join ejecucion on competencia.id_competencia = ejecucion.id_competencia
join realiza on ejecucion.id_ejecucion = realiza.id_ejecucion
join kata on realiza.id_kata = kata.id_kata
where realiza.id_kata = 59;

#8. Historial de los Katas ejecutados por un competidor con el puntaje obtenido, ordenado por puntaje.
#(nombre, apellido, nombre_kata, puntaje)
select competidor.nombre, competidor.apellido, kata.nombre, ejecucion.ronda, ejecucion.puntaje_total
from competidor
join ejecucion on competidor.id_competidor = ejecucion.id_competidor 
join realiza on ejecucion.id_ejecucion = realiza.id_ejecucion
join kata on realiza.id_kata = kata.id_kata
where competidor.id_competidor = 45434618
order by ejecucion.puntaje_total desc;

#10.Lista de Katas ejecutadas en un torneo en particular, ordenado por categoría y género.
#(número_kata, nombre_kata, categoría, género)
select kata.id_kata, kata.nombre, ejecucion.categoria,
case when competidor.sexo = 'M' then 'Masculino' when competidor.sexo = 'F' then 'Femenino' end as genero
from kata
join realiza on kata.id_kata = realiza.id_kata
join ejecucion on realiza.id_ejecucion = ejecucion.id_ejecucion
join competencia on ejecucion.id_competencia = competencia.id_competencia
join competidor on ejecucion.id_competidor = competidor.id_competidor
where competencia.nombre = 'Competencia ISBO 2023'
order by ejecucion.categoria, competidor.sexo, kata.id_kata;

#11. Cantidad de participantes de cada escuela, ordenado por escuela. (nombre_escuela, cantidad).
select c.escuela, count(*) as cantidad_competidores
from competidor c
group by c.escuela
order by cantidad_competidores desc;

#promedio anual (2023) de un competidor (competidor sabido)
#(id_competidor, nombre, apellido, año, promedio_puntaje)
select competidor.id_competidor as CI, competidor.nombre, competidor.apellido, year(competencia.fecha) as año, round(avg(ejecucion.puntaje_total), 1) as promedio_puntaje
from competidor 
join ejecucion on competidor.id_competidor = ejecucion.id_competidor
join competencia on ejecucion.id_competencia = competencia.id_competencia
where competidor.id_competidor = 45434618 and year(competencia.fecha) = 2023
group by competidor.id_competidor, year(competencia.fecha);







