#!/bin/bash
clear
while true; do
    echo "	  
            ____________________________________________
           |		                                |
           |	      Menú de Logs de Auditoría         |
           |--------------------------------------------|
           | 1)Mostrar Logs de Auditoría                |
           | 2)Mostrar Logs de Autenticación            |
           | 3)Mostrar Logs de Sistema                  |
           | 4)Mostrar Últimas Entradas del audit.log   |
           | 5)Mostrar Últimas Entradas del secure      |
           | 6)Mostrar Últimas Entradas del menssages   |
           | 7)Volver	                                |  
           |____________________________________________| "
    

    read -p "Ingrese una opción: " opcion

    case "$opcion" in
        1)
            echo -e "\nLogs de Auditoría (audit.log):"
            cat /var/log/audit/audit.log
            ;;
        2)
            echo -e "\nLogs de Autenticación (secure):"
            cat /var/log/menssages
            ;;
        3)
            echo -e "\nLogs de Sistema (syslog):"
            cat /var/log/syslog
            ;;
        4)
            echo -e "\nÚltimas entradas del Log de Auditoría:"
            tail /var/log/audit/audit.log
            ;;
        5)
            echo -e "\nÚltimas entradas del Log de Autenticación:"
            tail /var/log/secure
            ;;
        6)
            echo -e "\nÚltimas entradas del Log de Sistema:"
            tail /var/log/menssages
            ;;
        7)
            clear
            exit 0
            ;;
        *)
            echo "Opción no válida. Inténtelo de nuevo."
            ;;
    esac

   
    sleep 1
    clear
done
