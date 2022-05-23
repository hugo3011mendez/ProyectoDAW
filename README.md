# ProyectoDAW
Subiré aquí todos los archivos relacionados con el proyecto de fin de ciclo de Desarrollo de Aplicaciones Web.

## Idea para el proyecto
- Un To Do Manager, para organizar tareas
	- Con usuarios, tareas pendientes, realizándose y realizadas.

## Changelog

### 21/04/2022
### Repositorio creado e iniciada escritura en este Readme


### 27/04/2022
- Añadida estructura de carpetas con toda la información posible.


### 10/05/2022
- Comienzo del trabajo relacionado con el hito "Entidades de Datos"
	- Creadas tablas de la BBDD
	- Se ha creado un esquema relacional de la BBDD
	- Añadidas explicaciones a los tipos de datos y campos de las tablas


### 15/05/2022
- Comienzo del trabajo relacionado con el hito "Plantillas"
	- Creado [proyecto en Figma](https://www.figma.com/file/DvIS3RPxc4pvAt01vPY6pH/Plantillas-Proyecto-DAW?node-id=0%3A1) para el esquematizado de la apariencia del frontend


### 16/05/2022
- Actualizado documento relacionado con el hito "Entidades de Datos"
	- Añadido campo "Email" a la tabla Usuarios
	- Añadido campo "Proyecto" en la tabla Tareas y en la tabla Tareas Finalizadas
	- Creada tabla Proyectos referente a los proyectos en los que estarán contenidas las tareas
	- Modificado el campo "Estado" en la tabla Tareas a un booleano
	- Eliminada tabla Estados

- Actualizado hito "Plantillas"
	- Actualizado [proyecto en Figma](https://www.figma.com/file/DvIS3RPxc4pvAt01vPY6pH/Plantillas-Proyecto-DAW?node-id=0%3A1) con plantillas de las páginas para los usuarios sin privilegios


### 17/05/2022
- Actualizado hito "Plantillas"
	- Actualizadas las páginas creadas ayer, y añadidas las páginas referentes a los administradores y a las tareas finalizadas en el [proyecto en Figma](https://www.figma.com/file/DvIS3RPxc4pvAt01vPY6pH/Plantillas-Proyecto-DAW?node-id=0%3A1)
	- El documento se ha actualizado y entregado correctamente

### 23/05/2022
- Modificado documento referente al hito "Plantillas" para corregir erratas

- Creado archivo SQL para la creación de la base de datos
	- Definidas tablas Usuarios, Roles y Privilegios
	- Introducidos los datos pertinentes en la tabla Privilegios