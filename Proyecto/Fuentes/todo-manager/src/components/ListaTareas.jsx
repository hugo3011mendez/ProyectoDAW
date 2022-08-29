import { useContext } from "react"; // Importamos módulos de React
import { Link } from "react-router-dom";
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
import { useFetch } from '../hooks/useFetch'; // Importo el hook personalizado referente al fetch
import { URL_ELIMINAR_TAREA, URL_LEER_TAREAS_DE_PROYECTO, URL_CREAR_TAREA } from "../services/API"; // Importación de URLs del archivo de constantes
import axios from "axios";
// Importaciones de componentes personalizados
import Loading from './Loading';
import { RUTA_CREAR_TAREA, RUTA_EDITAR_TAREA_SIN_ID } from "../services/Rutas";

const ListaTareas = ({proyecto}) => { // Recibo la ID del proyecto como prop
  // Consigo los datos del hook personalizado llamándolo y pasándole la URL a la que quiero realizarla
  const {data, error, loading} = useFetch(URL_LEER_TAREAS_DE_PROYECTO+proyecto.id);
  console.log(data); // FIXME : Se ejecuta varias veces y devuelve undefined
  
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
    axios.post(URL_ELIMINAR_TAREA+id);
    window.location.reload(); // Recargo la página para que se muestren los datos actualizados
  }
  

  return (
  <>
    <h4>Tareas</h4>
    <table className='col-9 border-start table'>
      <thead>
          <th scope="col">ID</th>
          <th scope="col">Nombre</th>
          <th scope="col">Descripción</th>
          <th scope="col">Fecha de creación</th>
          <th scope="col">Fecha de modificación</th>
      </thead>
      <tbody>
        { // Listo todos los usuarios que haya para que se muestren
          data.map(item => 
            <tr key={item.id}> {/* Uso los nombres de los campos en la BBDD MySQL */}
              <th scope="row">{item.id}</th>
              <td>{item.nombre}</td>
              <td>{item.descripcion}</td>
              <td>{item.fecha_creacion}</td>
              <td>{item.fecha_modificacion}</td> {/* Muestra en texto el rol del usuario, en vez de en un número */}
              <td> {/* Botones referentes a acciones que podremos hacer con una tarea */}
                <div className="btn-group" role="group" aria-label="Basic example">
                  <Link to={RUTA_EDITAR_TAREA_SIN_ID+item.id} className="btn btn-warning">Editar</Link>
                  {/* Le asigno al evento onClick una función para eliminar el usuario de la BBDD */}
                  <button type="button" className="btn btn-danger ms-1" onClick={() => eliminarTarea(item.id)}>Borrar</button>
                </div>
              </td>
            </tr>              
          )
        }
      </tbody>
    </table>
    <Link to={RUTA_CREAR_TAREA} className="btn btn-primary mt-3 ms-2">Crear Tarea</Link>
  </>
  )
}

export default ListaTareas