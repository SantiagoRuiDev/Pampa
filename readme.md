![Pampa Banner](public/files/images/PampaBanner.png)

# **Pampa Framework**

### - Descripción
Pampa es un Framework de PHP ligero, pensado para desarrollar API Rest con todas las funcionalidades basicas necesarias y sin tener que reinventar la rueda.

### - Router 
El modulo de Ruteo permite inicializar la aplicación y crear los endpoints disponibles para los clientes, el Router carga consigo la responsabilidad de asignarle un controlador y un metodo a cada ruta. (Tambien pueden agregarse middlewares)
Es necesario saber que en cada función de nuestro controlador o middleware deberemos de invocar a las clases Request y Response para poder manejar las peticiones y las respuestas. (Esto esta ejemplificado)

### - Autenticación
Este modulo, nos permite crear, validar y exportar JWT, hashear y comparar contraseñas, solo debes instanciarlo en el controlador o middleware y acceder a este como una propiedad del objeto. (Esto esta ejemplificado) // Recuerda siempre establecer tu firma secreta al instanciar la Clase Autenticación.

### - Filemanager
Este modulo nos permite guardar y eliminar archivos.
Cuenta con pocas funciones, con posibilidad de crecer en el futuro. (Esto esta ejemplificado)

### - Mailing
Este modulo nos permite enviar emails via SMTP (Se esta finalizando el desarrollo de este Modulo)

### 

*Aclaraciones:* 

- Una vez instalado, segun el nombre de tu carpeta que contenga los archivos sera el nombre inicial de la ruta:

    - **{ruta_servidor_apache}/api**


------------


### - Requerimientos
- Contar con XAMPP / MySQL / Apache Server.
- Conocimientos mínimos sobre Programación Orientada a Objetos.
- Entender el protocolo HTTP.
- Contar con Composer para descargar dependencias.

------------



### - Instalación

Abre una terminal de tu proyecto (Puede ser en Visual)
Ejecute el siguiente comando.

```bash
  composer install
```
    

### - Endpoints
    
- #### Lista tus primeros endpoints!

    En el archivo main, podras encontrar la manera de inicializar tu aplicación con unos ejemplos ya creados.
    Donde podras asignar la ruta, metodo http, controlador y metodo controlador a tu endpoint.
    
