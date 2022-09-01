import { useContext, useEffect } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
import { useParams } from "react-router-dom";
// Importaciones de componentes personalizados :
import FormularioAddTarea from "../components/FormularioAddTarea";

/**
 * Ruta referente a la página en la que se podrá crear una tarea
 */
 const AddTarea = () => {
  const {comprobarLogin} = useContext(UserContext); // Consigo la variable del contexto
  
  useEffect(()=>{
    comprobarLogin(); // Añado comprobación del login
  }, []);
  
  let params = useParams(); // Recojo todos los parámetros en la URL
  let idProyecto = params.id; // Obtengo la ID del proyecto a editar

  return ( // Muestro el formulario dedicado a crear una tarea
    <FormularioAddTarea proyecto={idProyecto} />
  )
}

export default AddTarea