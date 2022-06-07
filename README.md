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


### 24/05/2022
- Modificado documento referente a "Entidad de Datos"
	- Quitado el campo Usuario_Creador a las tablas "Tareas" y "Tareas Finalizadas"

- Terminado archivo SQL para la creación de la base de datos
	- Añadidas tablas "Proyectos", "Tareas" y "Tareas Finalizadas"

- Creado archivo PHP referente a las acciones a realizar para la gestión de la base de datos


### 25/05/2022
- Modificado archivo SQL de creación de la base de datos
	- Se han introducido valores en la tabla de roles, para que haya unos roles iniciales creados

- Modificado archivo PHP para las funciones de manejo de la base de datos
	- Se han añadido las siguientes funciones : 
		- Registrar un usuario
		- Eliminar un usuario
		- Eliminar los proyectos de un usuario
		- Eliminar un proyecto 
		- Eliminar todas las tareas de un proyecto

- Se ha creado otro archivo PHP para las funciones de uso general


### 31/05/2022
- Modificado archivo SQL de creación de la base de datos
	- Se han eliminado la entrada de datos para el rol "Invitado"

- Modificado archivo PHP para las funciones de manejo de la base de datos
	- Se ha dado un mejor formato al código
	- Se han añadido y modificado comentarios
	- Se han cambiado las constantes para el archivo Utils
	- Se han añadido las siguientes funciones : 
		- Eliminar una tarea
		- Eliminar todas las subtareas de una tarea

- Modificado archivo PHP para las funciones de uso general
	- Se ha movido el lugar de declaración de las constantes a este archivo


### 01/06/2022
- Modificado archivo PHP para las funciones de manejo de la base de datos
	- Se ha añadido la funcionalidad de rollback a todas las consultas, para cuando ocurra algún error
	- Se han añadido todos los comentarios pertinentes
	- Se han añadido las siguientes funciones : 
		- Finalizar una tarea
		- Finalizar todas las subtareas de una tarea

- Modificado archivo SQL de creación de la base de datos
	- Se ha eliminado el campo "estado" de las tablas para las tareas y tareas finalizadas, debido a la falta de uso

- Modificado documento referente a "Entidad de Datos" debido a este último cambio

- Creado nuevo archivo para el almacenamiento de constantes


### 07/06/2022
- Modificado archivo PHP para las funciones de manejo de la base de datos
	- Se ha dado un mejor formato al código con el fin de que sea más legible
	- Se han añadido las siguientes funciones :
		- Comprobar el resultado de una query y devolver un booleano según corresponda
		- Crear una tarea
		- Actualizar una tarea
		- Actualizar un usuario
		- Crear un proyecto
	- Modificada sentencia INSERT INTO en la función para finalizar subtareas

- Modificado archivo SQL de creación de la base de datos
	- Añadidos comentarios sobre el formato del tipo de dato datetime
	- Pendiente de saber cómo representar la imagen de los usuarios