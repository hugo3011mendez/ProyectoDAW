import { useContext } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
// Importación de componentes personalizados
import ListaProyectos from "../components/ListaProyectos";
import ListaTareas from "../components/ListaTareas";


/**
 * Ruta referente a la vista principal de los proyectos y las tareas del usuario
 *  */ 
const Main = () => {
  const {comprobarLogin} = useContext(UserContext); // Consigo la variable del contexto
  comprobarLogin(); // Añado comprobación del login

  // TODO : Pendiente para añadir una tarea
  // TODO : Pendiente para añadir un proyecto
  return (
    <div className="row">
      <ListaProyectos />
      <ListaTareas proyecto={1} /> {/* TODO : Cuando corrija errores de Axios, cambiar el valor del prop */}
    </div>
  )
}

export default Main