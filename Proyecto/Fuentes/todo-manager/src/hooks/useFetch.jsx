import { useEffect, useState } from "react"; // Importo los hooks necesarios
import axios from "axios";


export const useFetch = async(url) => { // Este hook recibe la URL a la que realizamos la petición
  const [data, setData] = useState([]); // Referente a los datos que recogemos de la API
  const [loading, setLoading] = useState(false); // Referente al momento de carga de los datos de la API
  const [error, setError] = useState(""); // Referente al error que obtengamos de la API

  useEffect(() => { // Cada vez que cambie la URL, realizo las siugientes acciones
    setLoading(true); // Primero indico que los datos deben cargarse

    axios.get(url).then(function(response){ // Realizo la petición
      setData(response.data); // Consigo los datos
    }).catch(error => {setError(error);})
    .finally(() => setLoading(false)); // Finalmente después de todo eso, dejo de cargar
  }, [url]);

  return {data, loading, error} // Devuelvo los datos de los hooks
}
