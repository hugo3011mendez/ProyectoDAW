import { useContext } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
import { useState } from "react"; // Importamos el hook de React
import { useFormulario } from "../hooks/useFormulario"; // Importación del hook personalizado
import { Link, useNavigate } from "react-router-dom"; // Importación de componentes de React Router DOM
import axios from "axios"; // Importo Axios
import { URL_CREAR_PROYECTO } from "../services/API"; // Importación de URLs del archivo de constantes
import { RUTA_MAIN } from "../services/Rutas"; // Importación de rutas
import Swal from 'sweetalert2' // Importo el paquete de Sweet Alert 2 que he instalado previamente en el proyecto

/**
 * Formulario dedicado a crear un nuevo proyecto en la base de datos
 */
const FormularioAddProyecto = () => {
  const {id} = useContext(UserContext); // Consigo la variable del contexto
  
  // Declaro una variable con los valores iniciales que deben tomar los elementos del form
  const initialState = { // Deben tener el mismo nombre que el atributo name de cada elemento
    txtNombre: "",
    txtDescripcion:""
  };

  const [message, setMessage] = useState(""); // Un hook referente al mensaje de error, por defecto una cadena vacía

  const [inputs, handleChange, reset] = useFormulario(initialState); // Uso el hook personalizado
  const {txtNombre, txtDescripcion} = inputs; // Destructuración de los valores de los inputs

  const navigate = useNavigate(); // Establezco el hook referente a cambiar de dirección web

  /**
    * Función para controlar el evento onSubmit
    * @param {*} e Evento onSubmit
    */
  const handleSubmit = e => {
    e.preventDefault();
    
    // Compruebo los datos escritos
    if (!txtNombre.trim() || !txtDescripcion.trim()) {
      setMessage("Hay errores en la escritura de los datos"); // Establezco el valor del mensaje de error
      Swal.fire({ // Muestro el modal indicando error
        title: 'Error',
        text: ""+message,
        icon: 'error',
        showConfirmButton: false,
        timer: 1500
      });
      reset(); // Reinicio el estado de los inputs
    }
    else{
      // Defino el cuerpo del mensaje que le mandaré a la API con los datos introducidos
      const datosEnviar = {"creador":id, "txtNombre":txtNombre.trim(), "txtDescripcion":txtDescripcion.trim()};
      const cuerpo = JSON.stringify(datosEnviar); // Convierto a JSON los datos a enviar a la API

      // Realizo la petición a la API con Axios
      axios.post(URL_CREAR_PROYECTO, cuerpo).then(function(response){
        if (response.data.success === 1) { // Compruebo si el resultado es correcto
          setMessage(response.data.message); // Establezco el mensaje
          Swal.fire({ // Muestro el modal indicando éxito
            title: 'Éxito!',
            text: ""+message,
            icon: 'success',
            showConfirmButton: false,
            timer: 1000
          });
          navigate(RUTA_MAIN); // Vuelvo a la página Main
        }
        else{ // Y en el caso de que haya algún error
          setMessage(response.data.message); // Establezco el valor del mensaje de error
          Swal.fire({ // Muestro el modal indicando error
            title: 'Error',
            text: ""+message,
            icon: 'error',
            showConfirmButton: false,
            timer: 1000
          });
          reset(); // Reinicio el estado de los inputs
        }
      });      
    }
  };

  
  return (
    <div className="container">
      <h5 className="card-title">Crear Proyecto</h5>
      <form className="mb-3" onSubmit={handleSubmit}> {/* Le adjunto el evento onSubmit */}
        {/* Campo referente al nombre del nuevo proyecto */}
        <label htmlFor="txtNombre"> Escribe el nombre del nuevo proyecto </label>
        <input
          type="text"
          name="txtNombre"
          className="form-control mb-2" 
          onChange={handleChange}
          value={txtNombre}
        /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}

        {/* Campo referente a la descripción del nuevo proyecto */}
        <label htmlFor="txtDescripcion"> Escribe la descripción del proyecto </label>
        <textarea
          type="text"
          name="txtDescripcion"
          className="form-control mb-2" 
          onChange={handleChange}
          value={txtDescripcion}
        /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}

        <button type="submit" className="btn btn-success">Crear Proyecto</button>
      </form>

      {/* Pongo un enlace a la página Main para que el usuario pueda volver */}
      <Link to={RUTA_MAIN} className="h5 link-primary" style={{textDecoration: "none"}}>🔙</Link>
    </div>
  )
}

export default FormularioAddProyecto