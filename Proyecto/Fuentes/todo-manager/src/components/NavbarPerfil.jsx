import { useContext } from "react"; // Importación de módulos de React
import { Link } from "react-router-dom"; // Importación de componentes de React Router DOM
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
import { RUTA_MAIN, RUTA_ADMIN } from '../services/Rutas'; // Importo el servicio de rutas

const NavbarPerfil = () => {
    const {nickname, rol, signOut} = useContext(UserContext); // Consigo las variables del contexto

    return (
      <nav className="navbar bg-light border-bottom row">
          <div className="container">
              <Link to={RUTA_MAIN} className="btn btn-primary col-1 ms-4">Main</Link>
              <h2 className="col-6">{nickname}</h2>
              {
                rol == 2 && <Link to={RUTA_ADMIN} className="btn btn-primary col-1 ms-4">Menú de Admin</Link>
              }
              <button className='btn btn-danger col-1 me-4' onClick={signOut}>Cerrar Sesión</button>
          </div>
      </nav>
    )
}

export default NavbarPerfil