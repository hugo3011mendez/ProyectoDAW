import { useState } from "react"; // Importamos el hook de React
import { useFormulario } from "../hooks/useFormulario"; // Importación del hook personalizado referente al form
import axios from "axios"; // Importo Axios
import { URL_ACTUALIZAR_PROYECTO } from "../services/API"; // Importación de URLs del archivo de constantes
import { RUTA_MAIN } from "../services/Rutas";
import { useNavigate } from "react-router-dom";
import Swal from 'sweetalert2' // Importo el paquete de Sweet Alert 2 que he instalado previamente en el proyecto

/**
 * Componente dedicado a editar la información de un proyecto
 * @param proyecto Objeto con los datos del proyecto que se quiere editar
 */
const FormularioCambioProyecto = ({proyecto}) => {  
  // Declaro una variable con los valores iniciales que deben tomar los elementos del form
  const initialState = { // El estado inicial de los campos es igual al de los valores del usuario
    txtNombre: proyecto.nombre,
    txtDescripcion: proyecto.descripcion
  };

  const [message, setMessage] = useState(""); // Un hook referente al mensaje de error por defecto una cadena vacía

  const [inputs, handleChange, reset] = useFormulario(initialState); // Uso el hook personalizado en Utils
  const {txtNombre, txtDescripcion} = inputs; // Destructuración de los valores de los inputs

  const navigate = useNavigate(); // Navegador para volver al Main

  /**
    * Función para controlar el evento onSubmit
    * @param {*} e Evento onSubmit
    */
  const handleSubmit = e => {
    e.preventDefault();

    // Compruebo los datos escritos
    if (!txtNombre.trim() || !txtDescripcion.trim()) {
      setMessage("Errores en la escritura de los datos"); // Establezco el valor del mensaje de error
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
      const datosEnviar = {"id":proyecto.id, "txtNombre":txtNombre.trim(), "txtDescripcion":txtDescripcion.trim()};
      const cuerpo = JSON.stringify(datosEnviar); // Convierto a JSON los datos a enviar a la API

      // Realizo la petición a la API con Axios
      axios.post(URL_ACTUALIZAR_PROYECTO, cuerpo).then(function(response){
        if (response.data.success == 1) {
          setMessage(response.data.message); // Consigo el mensaje de la petición
          Swal.fire({ // Muestro el modal indicando éxito
            title: 'Éxito!',
            text: ""+message,
            icon: 'success',
            showConfirmButton: false,
            timer: 1000
          });
          navigate(RUTA_MAIN); // Finalmente vuelvo al main
        }
        else{
          setMessage(response.data.message); // Consigo el mensaje de la petición
          Swal.fire({ // Muestro el modal indicando error
            title: 'Error',
            text: ""+message,
            icon: 'error',
            showConfirmButton: false,
            timer: 1000
          });
        }
      });
    }
  };

  
  return (
    <>
      <h5>Editar Proyecto</h5>
      <form className="mb-3" onSubmit={handleSubmit}> {/* Le paso el hook a la referencia y le adjunto el evento onSubmit */}
        {/* Campo referente al nombre del proyecto */}
        <label htmlFor="txtNombre"> Nombre : </label>
        <input
          type="text"
          name="txtNombre"
          className="form-control mb-2" 
          onChange={handleChange}
          value={txtNombre}
        /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}

        {/* Campo referente a la descripción del proyecto */}
        <label htmlFor="txtDescripcion"> Descripción : </label>
        <textarea
          type="text"
          name="txtDescripcion"
          className="form-control mb-2" 
          onChange={handleChange}
          value={txtDescripcion}
        /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}

        <button type="submit" className="btn btn-warning">Actualizar datos</button>
      </form>
    </>
  )
}

export default FormularioCambioProyecto