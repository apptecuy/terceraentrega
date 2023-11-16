#!/bin/bash
clear

while [ "$op" != 3 ]; do
echo "
   	  ___________________________
         |                           |
         |      Menú Logs            |
         |---------------------------|
         | 1) Logs sistema           |
         | 2) Logs auditoria	     |		
         | 3) Volver     	     |
	 |___________________________|
"
read -p "Ingrese una opción: " op
case $op in
	1) bash Logs.sh
	   sleep 0s;;
	2) bash LogsAudi.sh
	   sleep 0s;;
	3) clear
	   exit;;
        *) echo "Opción no valida"
           sleep 1s
 clear
 esac
 done      
      
