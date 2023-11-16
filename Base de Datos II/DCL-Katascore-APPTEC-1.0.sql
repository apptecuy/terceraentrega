use bd_apptec;

CREATE USER 'administrador'@'%' IDENTIFIED BY 'administrador';
CREATE USER 'juez'@'%' IDENTIFIED BY 'juez';
CREATE USER 'publico'@'%' IDENTIFIED BY 'publico';

GRANT USAGE ON *.* TO 'administrador'@'%' WITH MAX_USER_CONNECTIONS 2;
GRANT USAGE ON *.* TO 'juez'@'%' WITH MAX_USER_CONNECTIONS 14;

#PERMISOS SOBRE TABLA COMPETENCIA
grant insert, update, delete, select on competencia to administrador;
grant select on competencia to juez;
grant select on competencia to publico;

#PERMISOS SOBRE TABLA COMPETIDOR
grant insert, update, delete, select on competidor to administrador;
grant select on competidor to juez;
grant select on competidor to publico;

#PERMISOS SOBRE TABLA REGISTRA
grant insert, update, delete, select on competidor to administrador;
grant select on registra to juez;
grant select on registra to publico;

#PERMISOS SOBRE TABLA RESULTADOCOMPETENCIA
grant insert, update, delete, select on competidor to administrador;
grant select on registra to juez;
grant select on registra to publico;

#PERMISOS SOBRE TABLA EJECUCION
grant insert, update, delete, select on ejecucion to administrador;
grant insert, select on ejecucion to juez;
grant select on ejecucion to publico;

#PERMISOS SOBRE TABLA KATA
grant select on kata to administrador;
grant select on kata to juez;
grant select on kata to publico;

#PERMISOS SOBRE TABLA REALIZA
grant select on realiza to administrador;
grant select on realiza to juez;
grant select on realiza to publico;

#PERMISOS SOBRE TABLA JUEZ
grant select on juez to administrador;
grant select on juez to juez;
grant select on juez to publico;

#PERMISOS SOBRE TABLA CALIFICA
grant insert, update, delete, select on califica to administrador;
grant insert,select on califica to juez;
grant select on califica to publico;