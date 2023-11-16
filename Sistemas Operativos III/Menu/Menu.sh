#!/bin/bash
clear
echo "   

			___________________________________
		       |				   |	
		       |     ¡ Bienvenido al sistema!      |
		       |				   | 
		       |___________________________________| "
sleep 1
clear
while [ "$op" != 8 ] ; do
echo "	
	    _____________________________
	   |		                 |
	   |	Menú principal           |
	   |-----------------------------|
           | 1)Configuración de usuario  |
           | 2)Configuración de grupo    |
           | 3)Servicios	         |
           | 4)Respaldos		 |
           | 5)Red			 |
           | 6)Firewall			 |
           | 7)Logs 			 |
	   | 8)Salir			 |
           |_____________________________|
"
read -p "Ingrese una opción: " op

case $op in
	1) bash ConfigurarU.sh
   	   sleep 0;;
	2) bash ConfigurarG.sh
	   sleep 0;;
	3) bash Servicios.sh
  	   sleep 0;;
  	4) bash MenuRespaldos.sh
  	   sleep 0;;
  	5) bash Red.sh
  	   sleep 0;;   
  	6) bash Firewall.sh
  	   sleep 0;; 
  	7) bash MenuLogs.sh
  	   sleep 0;;
  	8) echo "Saliendo del sistema" 
	   sleep 0s
  	   clear
           exit;;
	*) echo "Opción no valida"
   	   sleep 1s  	
		
clear
esac	 
done

