#!/bin/bash
clear
while true; do
    echo "	  
            _________________________________________________
           |		                                     |
           |	         Gestión de Logs                     |
           |-------------------------------------------------|
           | 1) Mostrar el contenido del log de mensajes     |
           | 2) Mostrar el contenido del log de kernel       |
           | 3) Buscar eventos en los logs                   |
           | 4) Salir                                        |
           |_________________________________________________|
    "
    read -p "Ingrese una opción: " opcion

    case "$opcion" in
        1)
            echo -e "\nContenido del log de mensajes:"
            cat /var/log/messages
            ;;
        2)
            echo -e "\nContenido del log de kernel:"
            cat /var/log/kern.log
            ;;
        3)
            read -p "Ingrese el término de búsqueda: " termino_busqueda
            echo -e "\nResultados de la búsqueda en los logs:"
            grep -i "$termino_busqueda" /var/log/*
            ;;
        4)
            echo 
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
