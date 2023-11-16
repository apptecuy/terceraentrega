#!/bin/bash

# Datos de la BD
DB_USER="apptec"
DB_PASSWORD="47743966"
DB_NAME="bdapptec"

# Directorio donde va a quedar el respaldo
BACKUP_DIR="/home/apptec/Escritorio/CompartidaVB/Respaldos/RespaldoBaseDatos"

# Nombre que le vamos a asiganar
BACKUP_FILE="${BACKUP_DIR}/RespaldoBaseDatos_$(date +\%Y\%m\%d).sql"

#Comando para el respaldo
mysqldump -u $DB_USER $DB_NAME > $BACKUP_FILE

# Verificacion para saber si se completó con éxito
if [ $? -eq 0 ]; then
    echo "Respaldo exitoso. El archivo se encuentra en: $BACKUP_FILE"
else
    echo "Error en el respaldo. Por favor, revisa los detalles."
fi

