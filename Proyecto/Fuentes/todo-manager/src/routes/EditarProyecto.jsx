import { useContext } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
import { useFetch } from '../hooks/useFetch'; // Importo el hook personalizado referente al fetch
import { useParams } from "react-router-dom";
import { URL_LEER_PROYECTO } from "../services/API"; // Importo el archivo de constantes con URLs
// Importaciones de componentes personalizados
import FormularioCambioProyecto from "../components/FormularioCambioProyecto";
import Loading from '../components/Loading';


const EditarProyecto = () => {
  let params = useParams(); // Recojo todos los parámetros en la URL
  let idProyecto = params.id; // Obtengo la ID del proyecto a editar

  const {comprobarLogin} = useContext(UserContext); // Consigo las variables del contexto
  comprobarLogin(); // Añado comprobación del login

  const {data, loading, error} = useFetch(URL_LEER_PROYECTO+idProyecto);
    
  if (loading) { // Compruebo que esté cargando los datos para mostrar el spinner
    return <Loading />;
  }
      
  if (error !== "") { // Compruebo que haya algún error para mostrarlo
    console.log(error);
    return <div className="alert alert-danger" role="alert">{error}</div>
  }
  
  return (
    <div className="container">
      { // Compruebo que se hayan recogido los datos conseguidos y muestro el formulario
        data[0] != undefined && <FormularioCambioProyecto proyecto={data[0]} /> /* Muestro el formulario de cambio de datos del proyecto */
      }
    </div>
  )
}

export default EditarProyecto