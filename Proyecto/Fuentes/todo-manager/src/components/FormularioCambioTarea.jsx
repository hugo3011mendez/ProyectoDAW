import { useState, useContext } from "react"; // Importamos el hook de React
import { useFormulario } from "../hooks/useFormulario"; // Importación del hook personalizado referente al form
import axios from "axios"; // Importo Axios
import { URL_ACTUALIZAR_TAREA } from "../services/API"; // Importación de URLs del archivo de constantes
import Swal from 'sweetalert2' // Importo el paquete de Sweet Alert 2 que he instalado previamente en el proyecto

const FormularioCambioTarea = ({tarea}) => {
  console.log(tarea);

  // Declaro una variable con los valores iniciales que deben tomar los elementos del form
  const initialState = { // El estado inicial de los campos es igual al de los valores del usuario
    txtNombre: tarea.nombre,
    txtDescripcion: tarea.descripcion,
    estado: null, // Establezco el estado inicial a null para que el usuario tenga que escogerlo de nuevo
  };

  const [message, setMessage] = useState(""); // Un hook referente al mensaje de error por defecto una cadena vacía

  const [inputs, handleChange, reset] = useFormulario(initialState); // Uso el hook personalizado en Utils
  const {txtNombre, txtDescripcion, estado} = inputs; // Destructuración de los valores de los inputs

  /**
    * Función para controlar el evento onSubmit
    * @param {*} e Evento onSubmit
    */
  const handleSubmit = e => {
    e.preventDefault();

    // Compruebo los datos escritos
    if (!txtNombre.trim() || !txtDescripcion.trim() || estado == null) {
      setMessage("Errores en la escritura de los datos"); // Establezco el valor del mensaje de error
      Swal.fire({ // Muestro el modal indicando error
        title: 'Error',
        text: ""+message,
        icon: 'error',
        showConfirmButton: false,
        timer: 1500
      });
      reset(); // Termino reiniciando el estado de los inputs
    }
    else{
      // Defino el cuerpo del mensaje que le mandaré a la API con los datos introducidos
      const datosEnviar = {"id": tarea.id, "txtNombre":txtNombre.trim(), "txtDescripcion":txtDescripcion.trim(), "estado":parseInt(estado)};
      const cuerpo = JSON.stringify(datosEnviar); // Convierto a JSON los datos a enviar a la API

      // Realizo la petición a la API con Axios
      axios.post(URL_ACTUALIZAR_TAREA, cuerpo).then(function(response){
        if (response.data.success == 1) {
          setMessage(response.data.message); // Consigo el mensaje de la petición
          Swal.fire({ // Muestro el modal indicando éxito
            title: 'Éxito!',
            text: ""+message,
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
          });
        }
        else{
          setMessage(response.data.message); // Consigo el mensaje de la petición
          Swal.fire({ // Muestro el modal indicando error
            title: 'Error',
            text: ""+message,
            icon: 'error',
            showConfirmButton: false,
            timer: 1500
          });
        }
      });
    }
  };

  
  return (
    <>
      <h5>Datos del perfil</h5>
      <form className="mb-3" onSubmit={handleSubmit}> {/* Le paso el hook a la referencia y le adjunto el evento onSubmit */}
        {/* Campo referente al nombre de la tarea */}
        <label htmlFor="txtNombre"> Nombre : </label>
        <input
          type="text"
          name="txtNombre"
          className="form-control mb-2" 
          onChange={handleChange}
          value={txtNombre}
        /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}

        {/* Campo referente al descripción del usuario */}
        <label htmlFor="txtDescripcion"> Descripción : </label>
        <textarea
          type="text"
          name="txtDescripcion"
          className="form-control mb-2" 
          onChange={handleChange}
          value={txtDescripcion}
        /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}


        {/* Radio Buttons referentes al estado de la tarea : */}
        <div className="form-check">
          <input className="form-check-input" type="radio" name="estado" id="pendiente" value={0} onChange={handleChange} />
          <label className="form-check-label" htmlFor="pendiente">
            Pendiente
          </label>
        </div>
        <div className="form-check">
          <input className="form-check-input" type="radio" name="estado" id="finalizada" value={1} onChange={handleChange} />
          <label className="form-check-label" htmlFor="finalizada">
            Finalizada
          </label>
        </div>

        <button type="submit" className="btn btn-warning">Actualizar datos</button>
      </form>
    </>
  )
}

export default FormularioCambioTarea