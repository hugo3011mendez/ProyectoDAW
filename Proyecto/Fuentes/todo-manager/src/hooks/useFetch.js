import { useEffect, useState } from "react"; // Importo los hooks necesarios
import axios from "axios"; // IMPORTANTE : HAY QUE TENER EL PAQUETE AXIOS INSTALADO


export const useFetch = (url) => { // Este hook recibe la URL a la que realizamos la petición
    const [data, setData] = useState([]); // Referente a los datos que recogemos de la API
    const [error, setError] = useState(""); // Referente al error que obtengamos de la API
    const [loading, setLoading] = useState(false); // Referente al momento de carga de los datos de la API

    useEffect(() => { // Cada vez que cambie la URL, realizo las siugientes acciones
        setLoading(true); // Primero indico que los datos deben cargarse

        axios.get(url).then(function(response){ // Realizo la petición
          setData(response.data); // Consigo los datos
        }).finally(() => setLoading(false)); // Finalmente después de todo eso, dejo de cargar
    }, [url]);

  return {data, error, loading} // Devuelvo los datos de los hooks
}
