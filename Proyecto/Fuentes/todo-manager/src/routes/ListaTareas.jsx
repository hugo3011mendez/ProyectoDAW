import { Link, useParams } from "react-router-dom";
import { useFetch } from '../hooks/useFetch'; // Importo el hook personalizado referente al fetch
import { URL_ELIMINAR_TAREA, URL_LEER_TAREAS_DE_PROYECTO} from "../services/API"; // Importación de URLs del archivo de constantes
import axios from "axios";
import { RUTA_CREAR_TAREA, RUTA_CREAR_TAREA_SIN_ID, RUTA_EDITAR_TAREA_SIN_ID } from "../services/Rutas";
import { useContext } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
// Importaciones de componentes personalizados
import Loading from '../components/Loading';
import { useEffect } from "react";

const ListaTareas = () => {
  const {comprobarLogin} = useContext(UserContext); // Consigo la variable del contexto
  
  useEffect(()=>{
    comprobarLogin(); // Añado comprobación del login
  }, []);

  let params = useParams(); // Recojo todos los parámetros en la URL
  let idProyecto = params.id; // Obtengo la ID del proyecto a editar

  // Consigo los datos del hook personalizado llamándolo y pasándole la URL a la que quiero realizarla
  const {data, error, loading} = useFetch(URL_LEER_TAREAS_DE_PROYECTO+idProyecto);
  
  if (loading) { // Compruebo que esté cargando los datos para mostrar el spinner
    return <Loading />;
  }
    
  if (error !== "") { // Compruebo que haya algún error para mostrarlo
    return <div className="col-9 border-start alert alert-danger" role="alert">{error}</div>
  }

  /**
  * Realiza una petición a la API y al server para eliminar la tarea indicada
  * @param {*} id La ID de la tarea a eliminar
  */
  function eliminarTarea(id){
    axios.post(URL_ELIMINAR_TAREA+id); // FIXME : Arreglar DELETE
    window.location.reload(); // Recargo la página para que se muestren los datos actualizados
  }
  

  return (
  <div className="col-9">
    <h4>Tareas</h4>

    { // Listo todos los usuarios que haya para que se muestren
      data.map(item => 
        <div className="card p-1 d-inline-block me-3" key={item.id}>
          <div class="card-body p-1">
            <h5 className="card-title">{item.nombre}</h5>
            <h6 className="card-subtitle mb-2 text-muted">Fecha de última modificación : {item.fecha_modificacion}</h6>
            <p className="card-text">{item.descripcion}</p>

            <Link to={RUTA_EDITAR_TAREA_SIN_ID+item.id} className="btn btn-warning">Editar</Link>
            <button type="button" className="btn btn-danger ms-1" onClick={() => eliminarTarea(item.id)}>Eliminar</button>
          </div>
        </div>
      )
    }

    <Link to={RUTA_CREAR_TAREA_SIN_ID+idProyecto} className="btn btn-primary mt-3 ms-2">Crear Tarea</Link>
  </div>
  )
}

export default ListaTareas