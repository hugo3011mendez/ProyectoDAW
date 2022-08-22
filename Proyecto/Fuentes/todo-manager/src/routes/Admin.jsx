import { useContext } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
// Importación de componentes personalizados
import NavbarAdmin from "../components/NavbarAdmin";

const Admin = () => {
  const {id, nickname} = useContext(UserContext); // Consigo las variables del contexto

  return (
    <>
      <NavbarAdmin /> {/* Muestro primero el Navbar */}
      <div>Menú de Admin</div>
    </>
  )
}

export default Admin