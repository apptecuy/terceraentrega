#!/bin/bash
clear
while true; do
    	echo "	                                                    
	     	    _______________________________________________  
		   |		                             	   | 
		   |	              Menú RED                	   | 
	    	   |-----------------------------------------------| 
                   | 1)Mostrar Usuarios Logueados                  | 
         	   | 2)Mostrar Información de la Interfaz de RED   | 
                   | 3)Mostrar Tabla ARP                           | 
                   | 4)Mostrar Último Login Usuario                | 
		   | 5)Volver                                      | 
            	   |_______________________________________________| "

    read -p "Ingrese una opción: " op

    case "$op" in
        1)
            echo -e "\nUsuarios Logueados: "
            who
            ;;
        2)
            echo -e "\nInterfaz de RED: "
            ip addr show
            ;;
        3)
            echo -e "\nTabla ARP: "
            arp -a
            ;;
        4)
            echo -n "Ingrese un usuario del cual quiera saber su último login: "
            read -r usuario
            echo -e "\nÚltimo login de $usuario:"
            last "$usuario"
            ;;
        5)
            clear
            exit 0
            ;;
        *)
            echo -e "\nOpción no válida. Por favor, ingrese una opción válida.\n"
            sleep 1
            ;;
    esac

  
    read -rsn1 -p "Presiona una tecla para continuar..."
    clear
done
