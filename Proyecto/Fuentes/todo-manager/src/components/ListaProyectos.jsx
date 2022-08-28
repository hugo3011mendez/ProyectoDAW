import { useContext } from "react"; // Importamos módulos de React
import { Link } from "react-router-dom";
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
import { useFetch } from '../hooks/useFetch'; // Importo el hook personalizado referente al fetch
import { URL_ELIMINAR_PROYECTO, URL_LEER_PROYECTOS_DE_USUARIO } from "../services/API"; // Importación de URLs del archivo de constantes
import axios from "axios";
// Importaciones de componentes personalizados
import Loading from './Loading';
import { RUTA_EDITAR_PROYECTO_SIN_ID } from "../services/Rutas";

const ListaProyectos = () => {
  const {id} = useContext(UserContext); // Consigo la variable del contexto

  // Consigo los datos del hook personalizado llamándolo y pasándole la URL a la que quiero realizarla
  const {data, error, loading} = useFetch(URL_LEER_PROYECTOS_DE_USUARIO+id);
  console.log(data); // FIXME : Se ejecuta varias veces y devuelve undefined
  
  if (loading) { // Compruebo que esté cargando los datos para mostrar el spinner
    return <Loading />;
  }
    
  if (error !== "") { // Compruebo que haya algún error para mostrarlo
    return <div className="col-2 border-end alert alert-danger" role="alert">Error de petición a la API</div>
  }
  

  /**
  * Realiza una petición a la API y al server para eliminar el proyecto indicado 
  * @param {*} id La ID del proyecto a eliminar
  */
  function eliminarProyecto(id){
    axios.post(URL_ELIMINAR_PROYECTO+id);
    window.location.reload(); // Recargo la página para que se muestren los datos actualizados
  }
  

  return (
    <div className="col-2 border-end">
      <h4>Proyectos</h4>
      <ul className="list-group">
        { // Listo todos los proyectos del usuario que haya para que se muestren
          data.map(item =>
            <li className="list-group-item">
              {item.nombre} {/* Muestro el nombre del proyecto */}
              {/* Pongo un botón que elimine el proyecto */}
              <button type="button" className="btn btn-danger ms-1" onClick={() => eliminarProyecto(item.id)}>Eliminar</button>
              {/* Y pongo un botón que edite el proyecto */}
              <Link to={RUTA_EDITAR_PROYECTO_SIN_ID+item.id} className="btn btn-warning">Editar</Link>
            </li>
          )
        }
      </ul>
    </div>
  )
}

export default ListaProyectos