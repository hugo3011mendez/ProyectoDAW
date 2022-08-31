import { useContext } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
// Importación de componentes personalizados
import ListaProyectos from "../components/ListaProyectos";
import { useEffect } from "react";
import { Link } from "react-router-dom";
import { RUTA_CREAR_PROYECTO } from "../services/Rutas";


/**
 * Ruta referente a la vista principal de los proyectos del usuario
 */ 
const Main = () => {
  const {comprobarLogin} = useContext(UserContext); // Consigo la variable del contexto
  
  useEffect(()=>{
    comprobarLogin(); // Añado comprobación del login
  }, []);

  return ( // Muestro la lista de los proyectos del usuario junto con un botón para crear un proyecto
    <div className="container">
      <ListaProyectos />
      <Link to={RUTA_CREAR_PROYECTO} className="btn btn-primary mt-3 ms-2">Crear Proyecto</Link>
    </div>
  )
}

export default Main