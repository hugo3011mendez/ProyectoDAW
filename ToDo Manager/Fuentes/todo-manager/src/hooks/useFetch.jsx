import { useEffect, useState } from "react"; // Importo los hooks necesarios

/**
 * Hook personalizado referente a la realización de una petición GET a la API
 * 
 * @param url La URL a la que se realizará la petición
 * 
 * @returns Los datos de la respuesta, un booleano indicando si se debe mostrar la animación de carga y el error si hay
 */
 export const useFetch = (url) => { // Este hook recibe la URL a la que realizamos la petición
    const [data, setData] = useState([]); // Referente a los datos que recogemos de la API
    const [error, setError] = useState(""); // Referente al error que obtengamos de la API
    const [loading, setLoading] = useState(false); // Referente al momento de carga de los datos de la API

    useEffect(() => { // Cada vez que cambie la URL, realizo las siugientes acciones
        setLoading(true); // Primero indico que los datos deben cargarse
        fetch(url)
            .then(res => res.json()) // Luego realizo la petición
            .then(data => setData(data)) // Consigo los datos
            .catch(e => setError("Error de servidor")) // Si algo falla, consigo el mensaje de error
            .finally(() => setLoading(false)) // Finalmente después de todo eso, dejo de cargar
    }, [url]);

  return {data, error, loading} // Devuelvo los datos de los hooks
}
