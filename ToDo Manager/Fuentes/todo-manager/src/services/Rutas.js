// Archivo donde estableceré las constantes referentes a las rutas de las páginas

const RUTA_REGISTRO = "/registro";

const RUTA_MAIN = "/user";

const RUTA_LISTA_TAREAS = RUTA_MAIN + "/tareas/:id"; // Esta ruta será para index.js
const RUTA_LISTA_TAREAS_SIN_ID = RUTA_MAIN + "/tareas/"; // Esta ruta será para completar en el código

const RUTA_PERFIL = RUTA_MAIN + "/perfil";

const RUTA_ADMIN = RUTA_MAIN + "/admin";

const RUTA_EDITAR_PROYECTO = RUTA_MAIN + "/editarProyecto/:id"; // Esta ruta será para index.js
const RUTA_EDITAR_PROYECTO_SIN_ID = RUTA_MAIN + "/editarProyecto/"; // Esta ruta será para completar en el código
const RUTA_CREAR_PROYECTO = RUTA_MAIN + "/crearProyecto";

const RUTA_EDITAR_TAREA = RUTA_MAIN + "/editarTarea/:id"; // Esta ruta será para index.js
const RUTA_EDITAR_TAREA_SIN_ID = RUTA_MAIN + "/editarTarea/"; // Esta ruta será para completar en el código

const RUTA_CREAR_TAREA = RUTA_MAIN + "/crearTarea/:id"; // Esta ruta será para index.js
const RUTA_CREAR_TAREA_SIN_ID = RUTA_MAIN + "/crearTarea/" // Esta ruta será para completar en el código

const RUTA_CREAR_ADMIN = RUTA_MAIN + "/crearAdmin" // Esta ruta será para completar en el código


export {RUTA_REGISTRO,
    RUTA_MAIN,
    RUTA_LISTA_TAREAS, RUTA_LISTA_TAREAS_SIN_ID,
    RUTA_PERFIL,
    RUTA_ADMIN,
    RUTA_EDITAR_PROYECTO, RUTA_EDITAR_PROYECTO_SIN_ID, RUTA_CREAR_PROYECTO,
    RUTA_EDITAR_TAREA, RUTA_EDITAR_TAREA_SIN_ID, RUTA_CREAR_TAREA, RUTA_CREAR_TAREA_SIN_ID,
    RUTA_CREAR_ADMIN
};