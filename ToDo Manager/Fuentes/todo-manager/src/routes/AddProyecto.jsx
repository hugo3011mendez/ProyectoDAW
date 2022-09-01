import { useContext, useEffect } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
// Importaciones de componentes personalizados :
import FormularioAddProyecto from "../components/FormularioAddProyecto";

/**
 * Ruta referente a la página en la que se podrá crear un proyecto para el usuario loggeado
 */
const AddProyecto = () => {
    const {comprobarLogin} = useContext(UserContext); // Consigo la variable del contexto
  
    useEffect(()=>{
      comprobarLogin(); // Añado la comprobación del login
    }, []);
      

  return ( // Muestro el formulario donde se introducirán los datos para crear el proyecto
    <FormularioAddProyecto />
  )
}

export default AddProyecto