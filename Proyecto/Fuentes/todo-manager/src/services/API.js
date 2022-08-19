// Archivo donde estableceré las constantes referentes al proyecto
const URL_USUARIO = "https://localhost/ToDoManager/funcionesUsuarios.php";
const URL_REGISTRAR_USUARIO = "https://localhost/ToDoManager/funcionesUsuarios.php?registrarUsuario=1";
const URL_LEER_USUARIOS = "https://localhost/ToDoManager/funcionesUsuarios.php?listaUsuarios=1";
const URL_LEER_USUARIO = "https://localhost/ToDoManager/funcionesUsuarios.php?conseguirUsuario="; // Concatenar con la ID cuando se use
const URL_ACTUALIZAR_USUARIO = "https://localhost/ToDoManager/funcionesUsuarios.php?actualizarUsuario=1";
const URL_ELIMINAR_USUARIO = "https://localhost/ToDoManager/funcionesUsuarios.php?eliminarUsuario="; // Concatenar con la ID cuando se use


const URL_PROYECTO = "https://localhost/ToDoManager/funcionesProyectos.php";
const URL_CREAR_PROYECTO = "https://localhost/ToDoManager/funcionesProyectos.php?crearProyecto=1";
const URL_LEER_PROYECTOS = "https://localhost/ToDoManager/funcionesProyectos.php?listaProyectos=1";
const URL_LEER_PROYECTO = "https://localhost/ToDoManager/funcionesProyectos.php?conseguirProyecto="; // Concatenar con la ID cuando se use
const URL_ACTUALIZAR_PROYECTO = "https://localhost/ToDoManager/funcionesProyectos.php?actualizarProyecto=1";
const URL_ELIMINAR_PROYECTO = "https://localhost/ToDoManager/funcionesProyectos.php?eliminarProyecto="; // Concatenar con la ID cuando se use


const URL_TAREA = "https://localhost/ToDoManager/funcionesTareas.php";
const URL_CREAR_TAREA = "https://localhost/ToDoManager/funcionesTareas.php?crearTarea=1";
const URL_LEER_TAREAS_DE_PROYECTO = "https://localhost/ToDoManager/funcionesTareas.php?listaTareasDeProyecto="; // Concatenar con la ID del proyecto cuando se use
const URL_LEER_SUBTAREAS = "https://localhost/ToDoManager/funcionesTareas.php?listaSubtareas="; // Concatenar con la ID de la tarea padre cuando se use
const URL_LEER_TAREA = "https://localhost/ToDoManager/funcionesTareas.php?conseguirTarea="; // Concatenar con la ID cuando se use
const URL_ACTUALIZAR_TAREA = "https://localhost/ToDoManager/funcionesTareas.php?actualizarTarea=1";
const URL_ELIMINAR_TAREA = "https://localhost/ToDoManager/funcionesTareas.php?eliminarTarea="; // Concatenar con la ID cuando se use


const URL_ROL = "https://localhost/ToDoManager/funcionesRoles.php";
const URL_CREAR_ROL = "https://localhost/ToDoManager/funcionesRoles.php?crearRol=1";
const URL_LEER_ROLES = "https://localhost/ToDoManager/funcionesRoles.php?listaRoles=1";
const URL_LEER_ROL = "https://localhost/ToDoManager/funcionesRoles.php?conseguirRol="; // Concatenar con la ID cuando se use
const URL_ACTUALIZAR_ROL = "https://localhost/ToDoManager/funcionesRoles.php?actualizarRol=1";
const URL_ELIMINAR_ROL = "https://localhost/ToDoManager/funcionesRoles.php?eliminarRol="; // Concatenar con la ID cuando se use

export { URL_REGISTRAR_USUARIO, URL_LEER_USUARIOS, URL_LEER_USUARIO, URL_ACTUALIZAR_USUARIO, URL_ELIMINAR_USUARIO,
    URL_CREAR_PROYECTO, URL_LEER_PROYECTOS, URL_LEER_PROYECTO, URL_ACTUALIZAR_PROYECTO, URL_ELIMINAR_PROYECTO,
    URL_CREAR_TAREA, URL_LEER_TAREAS_DE_PROYECTO, URL_LEER_SUBTAREAS, URL_LEER_TAREA, URL_ACTUALIZAR_TAREA, URL_ELIMINAR_TAREA,
    URL_CREAR_ROL, URL_LEER_ROLES, URL_LEER_ROL, URL_ACTUALIZAR_ROL, URL_ELIMINAR_ROL
}