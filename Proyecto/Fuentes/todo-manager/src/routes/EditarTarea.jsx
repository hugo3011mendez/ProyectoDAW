import { useContext } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
import { useFetch } from '../hooks/useFetch'; // Importo el hook personalizado referente al fetch
import { useParams } from "react-router-dom";
import { URL_LEER_TAREA } from "../services/API"; // Importo el archivo de constantes con URLs
// Importaciones de componentes personalizados
import FormularioCambioTarea from "../components/FormularioCambioProyecto";
import Loading from '../components/Loading';

const EditarTarea = () => {
    let params = useParams(); // Recojo todos los parámetros en la URL
    let idTarea = params.id; // Obtengo la ID del proyecto a editar

    const {comprobarLogin} = useContext(UserContext); // Consigo las variables del contexto
    comprobarLogin(); // Añado comprobación del login

    // FIXME : Se ejecuta varias veces, acaba después de renderizarse
    const {data, loading, error} = useFetch(URL_LEER_TAREA+idTarea);  
    
    if (loading) { // Compruebo que esté cargando los datos para mostrar el spinner
      return <Loading />;
    }
      
    if (error !== "") { // Compruebo que haya algún error para mostrarlo
      console.log(error);
      return <div className="alert alert-danger" role="alert">Error de petición a la API</div>
    }


  return (
    <div className="container">
        <FormularioCambioTarea proyecto={data[0]} /> {/* Muestro el formulario de cambio de datos del proyecto */}
    </div>
  )
}

export default EditarTarea