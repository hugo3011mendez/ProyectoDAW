import { useContext } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
// Importación de componentes personalizados
import ListaUsuarios from "../components/ListaUsuarios";

/**
 * Ruta sólo accesible para los administradores, referente a la página en la que se encontrará la lista de usuarios
 */
const Admin = () => {
  const {comprobarLoginAdmin} = useContext(UserContext); // Consigo las variables del contexto
  comprobarLoginAdmin(); // Añado comprobación del login

  return (
    <div className="container">
      <ListaUsuarios /> {/* Muestro la lista de los usuarios en la base de datos */}
    </div>
  )
}

export default Admin