import { useContext } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
// Importación de componentes personalizados
import NavbarTareas from "../components/NavbarTareas"

/**
 * Ruta referente a la vista principal de los proyectos y las tareas del usuario
 *  */ 
const Main = () => {
  const {nickname} = useContext(UserContext); // Consigo la variable del contexto
  const tituloNav = "Proyectos de " + nickname; // Esta variable defino el título que se mostrará en el Navbar

  return (
    <>
      <NavbarTareas titulo={tituloNav} /> {/* Muestro primero el Navbar */}
      <div>Main</div>
    </>
  )
}

export default Main