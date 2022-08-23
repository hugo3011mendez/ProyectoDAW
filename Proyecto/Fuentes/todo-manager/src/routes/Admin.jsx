import { useContext } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
// Importación de componentes personalizados
import ListaUsuarios from "../components/ListaUsuarios";

const Admin = () => {
  const {comprobarLoginAdmin} = useContext(UserContext); // Consigo las variables del contexto
  comprobarLoginAdmin(); // Añado comprobación del login

  return (
    <>
      <ListaUsuarios /> {/* Muestro la lista de los usuarios en la base de datos */}
    </>
  )
}

export default Admin