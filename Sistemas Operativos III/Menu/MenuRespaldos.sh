!#/bin/bash
clear
while [ "$op" != 2 ]; do
echo "	
	    _____________________________
	   |		                 |
	   |	   Respaldos             |
	   |-----------------------------|
           | 1)Respaldos		 |
           | 2)Volver			 |
           |_____________________________|
"
read -p "Ingrese una opción: " op

case $op in
	1) bash RespBD.sh
  	   sleep 0;;
  	2) clear
	   exit;;
	*) echo "Opción no valida"
   	   sleep 1s  


clear
esac	 
done
