import { useContext, useEffect } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
import { useFetch } from '../hooks/useFetch'; // Importo el hook personalizado referente al fetch
import { URL_LEER_USUARIO } from "../services/API"; // Importación de URLs del archivo de constantes
// Importación de componentes personalizados
import FormularioCambioPerfil from "../components/FormularioCambioPerfil";
import Loading from '../components/Loading';
import axios from "axios";

// Ruta referente a la vista del perfil del usuario
const Perfil = () => {
  const {id} = useContext(UserContext); // Consigo las variables del contexto
  // const {data, loading, error} = useFetch(URL_LEER_USUARIO+id); // FIXME : Se ejecuta varias veces
  // console.log(data);

  function conseguirDatos(){
    axios.get(URL_LEER_USUARIO+id).then(function(response){
      return response.data[0];
    });
  }
  let usuario = conseguirDatos();
  console.log(usuario);

  // if (loading) { // Compruebo que esté cargando los datos para mostrar el spinner
  //   return <Loading />;
  // }
  
  // if (error !== "") { // Compruebo que haya algún error para mostrarlo
  //   console.log(error);
  //   return <div className="alert alert-danger" role="alert">Error de petición a la API</div>
  // }


  return (
    <div className="container">
      {/* <FormularioCambioPerfil usuario={data[0]} /> {/* Muestro el formulario de cambio de datos del usuario */}
    </div>
  )
}

export default Perfil