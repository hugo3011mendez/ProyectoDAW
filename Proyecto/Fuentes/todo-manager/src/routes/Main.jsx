import { useContext } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
// Importación de componentes personalizados


/**
 * Ruta referente a la vista principal de los proyectos y las tareas del usuario
 *  */ 
const Main = () => {
  const {nickname} = useContext(UserContext); // Consigo la variable del contexto

  return (
    <>
      <div>Main</div>
    </>
  )
}

export default Main