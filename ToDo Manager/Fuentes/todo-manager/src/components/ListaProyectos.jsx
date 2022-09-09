import { useContext } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
import { Link } from "react-router-dom";
import { useFetch } from '../hooks/useFetch'; // Importo el hook personalizado referente al fetch
import { URL_ELIMINAR_PROYECTO, URL_LEER_PROYECTOS_DE_USUARIO } from "../services/API"; // Importación de URLs del archivo de constantes
import axios from "axios";
// Importaciones de componentes personalizados
import Loading from './Loading';
import { RUTA_EDITAR_PROYECTO_SIN_ID, RUTA_LISTA_TAREAS_SIN_ID } from "../services/Rutas";

/**
 * Componente referente a la lista de los proyectos del usuario que haya iniciado sesión
 */
const ListaProyectos = () => {
  let {id} = useContext(UserContext); // Consigo la variable del contexto

  // Consigo los datos del hook personalizado llamándolo y pasándole la URL a la que quiero realizarla
  const {data, error, loading} = useFetch(URL_LEER_PROYECTOS_DE_USUARIO+id);
  
  if (loading) { // Compruebo que esté cargando los datos para mostrar el spinner
    return <Loading />;
  }
    
  if (error !== "") { // Compruebo que haya algún error para mostrarlo
    return <div className="col-2 border-end alert alert-danger" role="alert">{data.length == 0 ? "El usuario aún no tiene proyectos creados" : error}</div>
  }
  

  /**
  * Realiza una petición a la API y al server para eliminar el proyecto indicado 
  * @param {*} id La ID del proyecto a eliminar
  */
  function eliminarProyecto(id){
    axios.get(URL_ELIMINAR_PROYECTO+id).then(function(response){
      if (response.data.success == 1) { // Si la petición a la API ha resultado bien
        window.location.reload(); // Recargo la página para que se muestren los datos actualizados   
      }
      else{
        console.log("ERROR AL ELIMINAR EL PROYECTO");
      }
    });
  }
  

  return (
    <div className="ms-2">
      <h4>Proyectos</h4>
      <ul className="list-group">
        { // Listo todos los proyectos del usuario que haya para que se muestren
          data.map(item =>
            <li key={item.id} className="list-group-item">
              <Link to={RUTA_LISTA_TAREAS_SIN_ID+item.id} className="btn btn-primary">{item.nombre}</Link> {/* Muestro el nombre del proyecto */}

              {/* Y pongo un botón que edite el proyecto */}
              <Link to={RUTA_EDITAR_PROYECTO_SIN_ID+item.id} className="btn btn-warning">Editar</Link>
              
              {/* Pongo un botón que elimine el proyecto */}
              <button type="button" className="btn btn-danger ms-1 me-3" onClick={() => eliminarProyecto(item.id)}>Eliminar</button>

              {item.descripcion} {/* Muestro la descripción del proyecto */}
            </li>
          )
        }
      </ul>
    </div>
  )
}

export default ListaProyectos