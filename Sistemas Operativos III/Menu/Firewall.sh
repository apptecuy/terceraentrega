#!/bin/bash
clear
while true; do
    echo "	  
            __________________________________________
           |		                              |
           |	         Menú FIREWALL                |
           |------------------------------------------|
           | 1)Mostrar Reglas del Firewall            |
           | 2)Agregar Regla al Firewall              |
           | 3)Eliminar Regla del Firewall            |
           | 4)Reiniciar Reglas del Firewall          |
           | 5)Volver                                 |  
           |__________________________________________|
    "

    read -p "Ingrese una opción: " opcion

    case "$opcion" in
        1)
            echo "Reglas actuales del firewall:"
            iptables -L -n
            ;;
        2)
            read -p "Ingrese la regla a agregar: " nueva_regla
            iptables -A INPUT -p $nueva_regla -j ACCEPT
            echo "Regla agregada con éxito."
            ;;
        3)
            read -p "Ingrese la regla a eliminar: " regla_a_eliminar
            iptables -D INPUT -p $regla_a_eliminar -j ACCEPT
            echo "Regla eliminada con éxito."
            ;;
        4)
            echo "Reiniciando reglas del firewall..."
            iptables -F
            iptables -P INPUT DROP
            echo "Reglas reiniciadas con éxito."
            ;;
        5)
            clear
            exit 0
            ;;
        *)
            echo "Opción no válida. Por favor, ingrese una opción válida."
            sleep 1
            ;;
    esac

   
    read -rsn1 -p "Presiona una tecla para continuar..."
    clear
done
