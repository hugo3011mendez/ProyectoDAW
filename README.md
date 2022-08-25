# ProyectoDAW
Subiré aquí todos los archivos relacionados con el proyecto de fin de ciclo de Desarrollo de Aplicaciones Web.




## Idea para el proyecto
- Un To Do Manager, para organizar tareas
	- Con usuarios que tendrán roles los cuales poseerán privilegios, proyectos dentro de los cuales habrá tareas pendientes y realizadas 




## [Repositorio donde he subido todos los archivos referentes a aprender React](https://github.com/hugo3011mendez/Aprendiendo-React)




## Changelog

### Anclas
#### [Funciones probadas relacionadas con la base de datos](#funcionesBBDDprobadas)
#### [Funciones probadas relacionadas con la gestión de usuarios](#funcionesUsuariosProbadas)


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

#### Creado archivo SQL para la creación de la base de datos
- Definidas tablas Usuarios, Roles y Privilegios
- Introducidos los datos pertinentes en la tabla Privilegios


### 24/05/2022
- Modificado documento referente a "Entidad de Datos"
	- Quitado el campo Usuario_Creador a las tablas "Tareas" y "Tareas Finalizadas"

- Terminado archivo SQL para la creación de la base de datos
	- Añadidas tablas "Proyectos", "Tareas" y "Tareas Finalizadas"

#### Creado archivo PHP referente a las acciones a realizar para la gestión de la base de datos


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
		- Crear un rol
		- Actualizar un rol
		- Crear un proyecto
	- Modificada sentencia INSERT INTO en la función para finalizar subtareas
	- Se ha mejorado el formato de todos los mensajes de error

- Modificado archivo SQL de creación de la base de datos
	- Añadidos comentarios sobre el formato del tipo de dato datetime
	- **Pendiente de** saber cómo representar la imagen de los usuarios


### 08/06/2022
- Modificado archivo PHP para las funciones de manejo de la base de datos
	- Modificada función referente al registro de un usuario, con la comprobación de si el email ya existe en la base de datos

- Ha comenzado la prueba de las funciones de manejo de la base de datos

- He creado el archivo Gitignore para evitar subir los archivos de prueba al repositorio

- Se ha modificado el código de creación de la BBDD en el archivo BBDD.sql para que no haya errores


### 09/06/2022
- Modificado archivo PHP para las funciones de manejo de la base de datos
	- Modificada función referente al registro de un usuario, añadiendo comillas en la comprobación del email y guardando el resultado de la query en una variable para ser recorrida
	- Se ha guardado en variables el resultado de las querys que después serán recorridas

- Modificado archivo de funciones útiles en los archivos PHP
	- He añadido comillas para mostrar mensajes de error en la consola del navegador

- Avances en la prueba de funciones :
	- Funciona correctamente :
		- Registrar un usuario
		- Eliminar un usuario
		- Actualizar usuario


### 13/06/2022
- Modificado archivo PHP para las funciones de manejo de la base de datos
	- He cambiado la función PHP date() por la función SQL NOW() incluida en las sentencias que necesiten especificar fechas
	- He añadido paréntesis de cierre en las sentencias INSERT INTO que no lo tenían
	- He añadido la comprobación de si el proyecto existe a la función referente a crear una tarea
	- He añadido una función para conseguir el/los datos que sean null a la hora de actualizar un usuario
	- He añadido una función para conseguir el/los datos que sean null a la hora de actualizar un rol
	- He añadido una función para conseguir el/los datos que sean null a la hora de actualizar un proyecto
	- He añadido una función para conseguir el/los datos que sean null a la hora de actualizar una tarea
	- He actualizado la función eliminarProyecto() para poder trabajar tanto con el objeto de un proyecto como con su ID
	- He actualizado la función eliminarTodasTareas() para poder trabajar tanto con el objeto de un proyecto como con su ID
	- He actualizado la función eliminarTarea() para poder trabajar tanto con el objeto de una tarea como con su ID
	- He actualizado la función eliminarSubtareas() para poder trabajar tanto con el objeto de una tarea como con su ID
	- Añadidas comillas en el campo descripción para insertar una subtarea en la lista de tareas finalizadas, a la hora de finalizar las subtareas de una tarea
	- He cambiado el valor de los campos a insertar por el valor de la función NOW() para las fechas a la hora de finalizar tareas y subtareas
	- He creado una variable auxiliar para que cuando se finalice la tarea padre, no falle si su parentID es null

- Modificado archivo de constantes PHP
	- He añadido constantes referentes a los roles de los usuarios y a los privilegios de éstes

- Avances en la prueba de funciones :
	- Funciona correctamente :
		- Crear un proyecto
		- Crear una tarea
		- Eliminar todos los proyectos de usuario
		- Eliminar un proyecto
		- Eliminar todas las tareas de un usuario
		- Eliminar un usuario
		- Actualizar un usuario
		- Actualizar un proyecto
		- Actualizar una tarea
		- Eliminar una tarea con ID
		- Eliminar subtareas con ID
		- Finalizar una tarea
		- Finalizar las subtareas de una tarea


### 14/06/2022
- Modificado archivo PHP para las funciones de manejo de la base de datos
	- He quitado una comilla simple que sobraba en la sentencia de la función para actualizar un rol, para que funcione correctamente
	- He creado una función relacionada con la obtención del rol de un usuario específico

- Avances en la prueba de funciones :
	- Funciona correctamente :
		- Crear un rol
		- Actualizar un rol
		- Eliminar un rol
		- Conseguir el rol de un usuario

#### Creado archivo PHP referente a las acciones a realizar para la gestión de la base de datos
- He enlazado todos los archivos PHP necesarios a este, para su correcto funcionamiento


### 15/06/2022
- Modificado archivo SQL referente a la creación de la base de datos
	- He eliminado la tabla de tareas finalizadas y en su lugar he añadido un campo binario a la tabla de tareas indicando el estado de las mismas

- Modificado documento referente a la Entidad de Datos según este último cambio

- Modificado archivo de constantes en PHP :
	- He eliminado la constante referente a la tabla de tareas finalizadas
	- He añadido constantes referentes a los estados de las tareas

- Modificado archivo PHP para las funciones de manejo de la base de datos
	- He modificado las funciones referentes a actualizar una tarea y conseguir datos de una tarea añadiendo el dato parentID
	- He creado una función relacionada con actualizar los datos de una tarea finalizada
	- He creado una función relacionada con conseguir los datos de una tarea finalizada
	- He creado una función relacionada con devolver una tarea finalizada a pendiente
	- He modificado la función para conseguir datos de una tarea pendiente, si parentID es null devolverá una cadena "null"
	- He añadido comentarios para realizar mañana los cambios pertinentes según los últimos cambios en la base de datos

- Avances en la prueba de funciones :
	- Funciona correctamente :
		- actualizarTareaFinalizada()
		- conseguirDatoTareaFinalizada()
	- **Pendiente de** corregir fallos en :
		- ponerTareaEnPendiente()


### 16/06/2022
- Modificado archivo PHP para las funciones de manejo de la base de datos
	- He añadido el campo de estado de la tarea a crear y he comprobado si es una tarea padre en la función referente a crear una tarea
	- He añadido el campo de estado de la tarea a actualizar en la función referente a actualizar una tarea
	- He añadido el campo de estado para conseguir su info en la función referente a conseguir un dato de una tarea
	- He modificado y acortado la función referente a eliminar las subtareas de una tarea padre debido a la eliminación de la tabla de tareas finalizadas
	- He acortado la función referente a finalizar una tarea usando sólo una sentencia UPDATE y actualizando su fecha de modificación
	- He acortado la función referente a finalizar las subtareas de una tarea padre usando sólo una sentencia UPDATE y actualizando su fecha de modificación
	- He acortado y modificado la función referente a poner una tarea en pendiente debido a los cambios recientes en la BBDD y he actualizado las fechas de modificación
	- He modificado la función referente a eliminar todas las tareas de un proyecto debido a los últimos cambios en la BBDD
	- He cambiado todas las comprobaciones igualando a null por el uso de la función is_null()

### <a name="funcionesBBDDprobadas">Se han terminado las pruebas de las funciones relacionadas con la base de datos</a>
- Funciones probadas con un funcionamiento correcto :
	- conectarBBDD()
	- comprobarResultadoDeQuery()
	- accionesDeError()
	- registrarUsuario()
	- actualizarUsuario()
	- conseguirDatoUsuario()
	- eliminarUsuario()
	- eliminarProyectosDeUsuario()
	- conseguirRolDeUsuario()
	- crearRol()
	- actualizarRol()
	- conseguirDatoRol()
	- eliminarRol()
	- crearProyecto()
	- actualizarProyecto()
	- conseguirDatoProyecto()
	- eliminarProyecto()
	- eliminarTodasTareas()
	- crearTarea()
	- actualizarTarea()
	- conseguirDatoTarea()
	- finalizarTarea() 
	- finalizarSubtareas()
	- eliminarTarea()
	- eliminarSubtareas()
	- ponerEnPendiente()


### 20/06/2022
- Modificado archivo SQL de creación de la base de datos
	- He modificado el límite de caracteres para el campo referente a la contraseña en la tabla de usuarios, para que quepa ahí todo el código MD5

- Modificado archivo PHP para las funciones de gestión de usuarios
	- Modificado formato de los comentarios
	- Añadida función referente a la comprobación del email introducido con el de algún usuario de la BBDD
	- Añadida función referente a la comprobación de la contraseña introducida con el de algún usuario de la BBDD


### 05/07/2022
- Modificado archivo PHP para las funciones de gestión de usuarios
	- Creada función referente al inicio de sesión del usuario que ha introducido sus credenciales


### 07/07/2022
- Modificado archivo PHP para las funciones de gestión de usuarios
	- He eliminado la función referente a comprobar la contraseña del usuario, para incorporarla a la función referente al inicio de sesión
	- He modificado la función referente al inicio de sesión para que compruebe la contraseña y en caso afirmativo haga login


### 08/07/2022
- Modificado archivo PHP para las funciones de gestión de usuarios
	- He modificado la función referente al inicio de sesión para que elimine la cookie creada anteriormente en caso de que todo vaya bien

- <a name="funcionesUsuariosProbadas">Se han hecho avances en las pruebas de las funciones relacionadas con la gestión de usuarios</a> :
	- Funciona correctamente :
		- comprobarEmail()
		- login()


### 16/08/2022
- Modificado archivo SQL de creación de la base de datos
	- He eliminado el campo referente a la imagen en la tabla de usuarios


### 17/08/2022
- Creado proyecto de React referente al ToDo Manager
	- Añadido paquete Router v6 con NPM
	- Añadido paquete Axios con NPM para la gestión de la BBDD
	- Añadido paquete SweetAlert2 con NPM
	- Eliminados archivos que no hacen falta en el proyecto de React
	- Creadas carpetas para los diferentes tipos de archivos en React en el proyecto

- Cambiado el nombre de la carpeta de archivos PHP a `old-php`

- Copiada y pegada la carpeta de archivos PHP realizados en el proyecto [Aprendiendo-React](https://github.com/hugo3011mendez/Aprendiendo-React) llamada `php`


### 18/08/2022
- Proyecto de React con ToDo Manager
	- Añadidos archivos JSX referentes a rutas de inicio de sesión, registro, vista del perfil y vista principal de proyectos
	- Añadidos hooks personalizados para la comunicación con la BBDD y la comprobación de formularios
	- Añadidos componentes referentes a la pantalla de carga y un formulario
	- Incluyo Bootstrap al proyecto
	- Modificados archivos *index.js* y *App.jsx* para la estructura del proyecto

- Modificados archivos PHP del lado del servidor
	- Creados más archivos PHP para dividir las funciones en entidades de la base de datos
	- Modificado el archivo index.php según la nueva info sobre usuarios
	- **Pendiente de** adecuar todos los archivos PHP a su uso


### 19/08/2022
- Modificados archivos PHP del lado del servidor
	- Modificado archivo de funciones referentes a la API de los usuarios
	- Creados y editados archivos referentes a la API de los proyectos : Usuarios, proyectos, tareas y roles

- Proyecto de React con ToDo Manager
	- Editado archivo referente a las constantes de la API con todas las URLs necesarias
	- Exportadas todas las URLs necesarias en el archivo referente a las constantes de la API


### 20/08/2022
- Proyecto de React con ToDo Manager
	- Modificado título de la pestaña en *index.html*
	- Quitada clase container en *App.jsx*
	- Añadido gradiente de fondo a las rutas *Login.jsx* y *Registro.jsx*
	- *FormularioLogin.jsx* y *FormularioRegistro.jsx* :
		- Añadido un card en el que se meterá el form
		- Añadidos estilos, **pendiente de terminarlos**
	- *FormularioLogin.jsx* :
		- Añadido hook referente al mensaje de error
		- 
	- *FormularioRegistro.jsx* :
		- Añadido link que va de vuelta a la página *Login.jsx*
		- Añadidos hooks referentes al mensaje de error y a la navegación hacia la página de login
		- Añadidas comprobaciones y peticiones a la API del servidor
		- Añadida expresión regular para validar el email del nuevo usuario
		- Mejoradas todas las validaciones y comprobaciones
		- **TERMINADA LÓGICA DE ESTE COMPONENTE**
	- *API.js* :
		- Añadida URL de login

- Modificados archivos PHP del lado del servidor
	- He cambiado el mensaje de error a la hora de registrar un usuario
	- He añadido la posibilidad de login
	- Añadidas funciones y comprobaciones necesarias para el listado de las tareas finalizadas en un proyecto


### 22/08/2022
- Proyecto de React con ToDo Manager
	- Añadida exportación de la ruta de Login en el archivo *API.js*
	- Quito la función de reinicio en el caso de que las contraseñas no coincidan a la hora de registrar el usuario
	- Creado servicio de email para mandar un email de registro al usuario que quiera registrarse
		- Instalado paquete EmailJS para mandar un correo de registro
		- Realizado código para mandar un email al usuario cuando se registre
	- Añadida función referente al cierre de la sesión en el archivo *API.js*
	- Añadidas comprobaciones y peticiones Axios necesarias para el login en *FormularioLogin.jsx*
	- Creado componente referente al navbar en las pantallas referentes al visualizado de tareas
	- Creada carpeta y un archivo dentro referentes a los contextos y al contexto de la info del usuario respectivamente
	- Creado archivo de constantes referentes a las rutas de las páginas
	- Creado componente referente al navbar en las pantallas referentes al perfil y el menú del admin
	- Añadidas rutas en el código del formulario para login
	- Añadido Navbar para el menú de admin

- Modificado archivo SQL de creación de la base de datos
	- He añadido un registro que se creará automátiamente en la tabla usuarios referente al administrador

- Modificados archivos PHP del lado del servidor
	- Quitado establecimiento de la cookie en la función referente a comprobar el email para login
	- Añadida comprobación y función referentes a la obtención del nickname de un usuario dado
	- He eliminado la carpeta antigua de PHP ya que no me sirve de utilidad


### 23/08/2022
- Proyecto de React con ToDo Manager
	- Quitado componente referente al error y añadido modal de SweetAlert en su lugar para los formularios de login y registro
	- Creados nuevos componentes referentes a la lista de tareas y a la lista de proyectos para la ruta Main
	- Creado nuevo componente referente al formulario de cambio de datos del usuario actual
	- Quitados NavBar individuales, añadido un NavBar para todas las rutas
	- Modificado archivo de rutas según la forma correcta
	- Añadidas funciones `comprobarLogin()` y `comprobarLoginAdmin()` al contexto del usuario para que nadie pueda acceder a las páginas donde se use sin antes haber iniciado sesión
	- He puesto las funciones de comprobación de login en todas las rutas establecidas de momento


### 24/08/2022
- Proyecto de React con ToDo Manager
	- Añadida exportación de `comprobarLoginAdmin()` en el contexto del usuario
	- Establecido un fragment en la ruta *Perfil*
	- Cambiado diseño de *ListaUsuarios.jsx*
	- *Main.jsx* :
		- Importados y añadidos *ListaProyectos.jsx* y *ListaTareas.jsx*
	- Quitada clase container en el div de *App.jsx* y añadida clase container a las rutas *Perfil.jsx* y *Admin.jsx*
	- Creada nueva URL en *API.js* referente a leer los proyectos de un usuario
	- Creada ruta a la página de editar un proyecto en *Rutas.js* y en *index.js*
	- Creado componente referente al formulario para editar un proyecto
	- Modificados los comentarios en el formulario referente a editar los datos del perfil
	- Creada ruta a la página de editar una tarea en *Rutas.js* y en *index.js*
	- Mostrada tabla en *ListaTareas.jsx*
	- Modificados parámetros de envío en el cuerpo JSON de los formularios que editan entidades

- Modificados archivos PHP del lado del servidor
	- Añadidas funciones para leer los proyectos de un usuario creador
	- Modificada sentencia en la función que lista las tareas, para que sólo se muestren las tareas padre
	- Quitado ParentID de la función referente a actualizar una tarea


### 25/08/2022
- Proyecto de React con ToDo Manager
	- Modificado formulario de login para que una vez loggeado se recargue la página 
	- Modificada ruta de login para que compruebe si el usuario está loggeado y navegue directamente hacia Main
	- 