#CREACIÓN DE BASE DE DATOS.

create database bd_apptec;

use bd_apptec;

#CREACIÓN DE TABLA USUARIO
create table usuario(
nombre_usuario varchar (20) primary key,            
contraseña varchar (30) not null,
tipo varchar (20) not null);

#CREACIÓN DE TABLA COMPETENCIA
create table competencia(
id_competencia int auto_increment primary key,
nombre varchar (50) not null,
fecha date not null,
hora time not null,
lugar varchar (100) not null);

#CREACIÓN DE TABLA COMPETIDOR
create table competidor(
id_competidor int (8) primary key,
nombre varchar (50) not null,
apellido varchar (50) not null,
escuela varchar (50) not null,
sexo varchar (1) not null,
ranking int null,
mail varchar (60) not null,
fecha_nac date not null,
constraint check (id_competidor between 10000001 and 99999999),
constraint check (sexo in('M' , 'F')));

#CREACIÓN DE TABLA REGISTRA
create table registra(
categoria varchar (25),
id_competidor int (8),
id_competencia int,
constraint foreign key (id_competencia) references competencia (id_competencia),
constraint foreign key (id_competidor) references competidor (id_competidor),
constraint primary key (categoria , id_competencia , id_competidor));

#CREACIÓN DE TABLA RESULTADOCOMPETENCIA
create table resultadocompetencia (
resultado varchar(30) not null,
categoria varchar(25) not null,
id_competencia int not null,
id_competidor int not null,
foreign key (categoria) references registra (categoria),
foreign key (id_competencia) references registra (id_competencia),
foreign key (id_competidor) references registra (id_competidor),
primary key (id_competencia, id_competidor, categoria, resultado));

create table ejecucion(
id_ejecucion int auto_increment primary key,
id_competidor int (8) not null,
id_competencia int not null,
ronda varchar (30) not null,
cinturon varchar (3) not null check (cinturon in ('AKA' , 'AO')),
tatami varchar (7) not null check (tatami in ('tatami1', 'tatami2')),
puntaje_total decimal (3,1),
categoria varchar (25),
estado varchar (20) null,
estadocompetencia varchar (20) null,
foreign key (id_competidor) references registra (id_competidor),
foreign key (id_competencia) references registra (id_competencia),
foreign key (categoria) references registra (categoria));

#CREACIÓN DE TABLA KATA
create table kata(
id_kata int primary key,
nombre varchar (25) not null);

#CREACIÓN DE TABLA REALIZA
create table realiza(
id_ejecucion int not null,
id_kata int not null,
constraint primary key (id_ejecucion),
constraint foreign key (id_ejecucion) references ejecucion (id_ejecucion),
constraint foreign key (id_kata) references kata (id_kata));

#CREACIÓN DE TABLA JUEZ
create table juez(
id_juez varchar (6) primary key,
nro_juez int null,
tatami varchar (7),
constraint check (nro_juez between 1 and 14),
constraint check (tatami in ('tatami1', 'tatami2')));

#CREACIÓN DE TABLA CALIFICA
create table califica(
id_ejecucion int,
id_juez varchar (6),
calificacion decimal (3,1),
constraint primary key (id_ejecucion , id_juez),
constraint foreign key (id_ejecucion) references ejecucion (id_ejecucion),
constraint foreign key (id_juez) references juez (id_juez),
constraint check (calificacion = 0 or (calificacion >= 5 and calificacion <= 10)));