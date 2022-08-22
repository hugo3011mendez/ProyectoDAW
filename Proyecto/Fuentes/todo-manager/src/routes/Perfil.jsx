import { useContext } from "react"; // Importamos módulos de React
// Importación de componentes personalizados
import NavbarPerfil from "../components/NavbarPerfil"
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario

// Ruta referente a la vista del perfil del usuario
const Perfil = () => {
  const {id, nickname} = useContext(UserContext); // Consigo las variables del contexto

  return (
    <>
      <NavbarPerfil /> {/* Muestro primero el Navbar */}
      <div>Perfil</div>
    </>
  )
}

export default Perfil