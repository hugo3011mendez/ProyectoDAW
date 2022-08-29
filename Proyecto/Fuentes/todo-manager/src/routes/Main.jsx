import { useContext } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
// Importación de componentes personalizados
import ListaProyectos from "../components/ListaProyectos";
import ListaTareas from "../components/ListaTareas";
import { useEffect } from "react";
import { Link } from "react-router-dom";
import { RUTA_CREAR_PROYECTO } from "../services/Rutas";


/**
 * Ruta referente a la vista principal de los proyectos y las tareas del usuario
 *  */ 
const Main = () => {
  const {comprobarLogin} = useContext(UserContext); // Consigo la variable del contexto
  
  useEffect(()=>{
    comprobarLogin(); // Añado comprobación del login
  }, []);

  // TODO : Pendiente para añadir una tarea
  // TODO : Pendiente hacer contexto referente al proyecto seleccionado
  return (
    <div className="row">
      <div className="col-3 border-end">
        <ListaProyectos />
        <Link to={RUTA_CREAR_PROYECTO} className="btn btn-primary mt-3 ms-2">Crear Proyecto</Link>
      </div>

      <ListaTareas proyecto={1} /> {/* TODO : Cuando corrija errores de Axios, cambiar el valor del prop */}
    </div>
  )
}

export default Main