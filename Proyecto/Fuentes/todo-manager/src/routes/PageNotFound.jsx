import { Link } from "react-router-dom"
import { RUTA_MAIN, RUTA_REGISTRO } from '../services/Rutas'; // Importo el servicio de rutas
import { useContext, useEffect } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario

/**
 * Ruta referente al mensaje que se mostrará cuando se intente acceder a una dirección que no existe
 */
const PageNotFound = () => {
  const {comprobarLogin} = useContext(UserContext); // Consigo la variable del contexto
  
  useEffect(()=>{
    comprobarLogin(); // Añado comprobación del login
  }, []);

  return (
    <div className="container">
        <h1>ERROR : PÁGINA NO ENCONTRADA</h1>
        <h4>Quizás quisiste ir a alguna de estas páginas :</h4>
        <Link to={RUTA_MAIN} className="btn btn-warning">Main</Link>
        <Link to={RUTA_REGISTRO} className="btn btn-warning">Registrarse en la app</Link>
    </div>
  )
}

export default PageNotFound