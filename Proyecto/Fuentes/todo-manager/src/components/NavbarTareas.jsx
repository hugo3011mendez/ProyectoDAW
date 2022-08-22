import { useContext } from "react"; // Importación de módulos de React
import { Link } from "react-router-dom"; // Importación de componentes de React Router DOM
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario

/**
 * Navbar que se mostrará en todas las páginas que tengan que ver con la visualización de las tareas
 */
const NavbarTareas = ({titulo}) => { // Recibo el título que se muestre como prop
  const {signOut} = useContext(UserContext); // Consigo la variable del contexto referente a cerrar sesión

  return (
    <nav className="navbar bg-light border-bottom row">
        <div className="container">
            <h2 className="col-9 ms-4">{titulo}</h2> {/* Muestro el título como el prop recibido */}
            <Link to="/perfil" className="btn btn-primary col-1">Perfil</Link>
            <button className='btn btn-danger col-1 me-4' onClick={signOut}>Cerrar Sesión</button>
        </div>
    </nav>
  )
}

export default NavbarTareas