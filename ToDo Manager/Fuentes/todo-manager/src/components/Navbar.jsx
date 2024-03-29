import { useContext } from "react"; // Importación de módulos de React
import { NavLink } from "react-router-dom"; // Importación de componentes de React Router DOM
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
import { RUTA_ADMIN, RUTA_MAIN, RUTA_PERFIL } from '../services/Rutas'; // Importo el servicio de rutas

/**
 * Componente referente al navbar que se mostrará en la parte superior de las páginas una vez el usuario inicie sesión
 */
const Navbar = () => {
  const {nickname, rol, signOut} = useContext(UserContext); // Consigo la variable del contexto referente a cerrar sesión

  return (
    <nav className="navbar bg-light border-bottom row">
        <div className="container">
            <img src="ToDoManagerIcon.png" width={"100rem"}></img>
            <h2 className="col-7 ms-4">Bienvenido, {nickname}</h2>
            <NavLink className="btn btn-primary col-1" to={RUTA_MAIN} >Main</NavLink>
            <NavLink className="btn btn-primary col-1" to={RUTA_PERFIL} >Perfil</NavLink>
            {rol == 2 && <NavLink className="btn btn-primary col-1" to={RUTA_ADMIN} >Admin</NavLink>}
            <button className='btn btn-danger col-1 me-4' onClick={signOut}>Cerrar Sesión</button>
        </div>
    </nav>
  )
}

export default Navbar