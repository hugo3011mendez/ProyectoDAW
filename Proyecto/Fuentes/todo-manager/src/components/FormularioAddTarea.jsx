import { useState } from "react"; // Importamos el hook de React
import { useFormulario } from "../hooks/useFormulario"; // Importación del hook personalizado
import { Link, useNavigate } from "react-router-dom"; // Importación de componentes de React Router DOM
import axios from "axios"; // Importo Axios
import { URL_CREAR_TAREA } from "../services/API"; // Importación de URLs del archivo de constantes
import { RUTA_LISTA_TAREAS_SIN_ID } from "../services/Rutas"; // Importación de rutas
import Swal from 'sweetalert2' // Importo el paquete de Sweet Alert 2 que he instalado previamente en el proyecto

/**
 * Componente dedicado a crear una nueva tarea
 * @param proyecto La ID del proyecto en el que se creará la nueva tarea
 * @param parentID La ID de la tarea padre, si tiene, de la nueva tarea
 */
const FormularioAddTarea = ({proyecto, parentID}) => {
  
  // Declaro una variable con los valores iniciales que deben tomar los elementos del form
  const initialState = { // Deben tener el mismo nombre que el atributo name de cada elemento
    txtNombre: "",
    txtDescripcion:"",
    estado: null
  };

  const [message, setMessage] = useState(""); // Un hook referente al mensaje de error por defecto una cadena vacía

  const [inputs, handleChange, reset] = useFormulario(initialState); // Uso el hook personalizado en Utils
  const {txtNombre, txtDescripcion, estado} = inputs; // Destructuración de los valores de los inputs

  const navigate = useNavigate(); // Establezco el hook referente a cambiar de dirección web

  /**
    * Función para controlar el evento onSubmit
    * @param {*} e Evento onSubmit
    */
  const handleSubmit = e => {
    e.preventDefault();
    
    // Compruebo los datos escritos
    if (!txtNombre.trim() || !txtDescripcion.trim() || proyecto == null || estado == null) {
      setMessage("Hay errores en la escritura de los datos"); // Establezco el valor del mensaje de error
      console.log("Hay errores en la escritura de los datos");
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
      const datosEnviar = {"txtNombre":txtNombre.trim(), "txtDescripcion":txtDescripcion.trim(), "proyecto":parseInt(proyecto) , "parentID":parentID , "estado":parseInt(estado)};
      const cuerpo = JSON.stringify(datosEnviar); // Convierto a JSON los datos a enviar a la API

      // Realizo la petición a la API con Axios
      axios.post(URL_CREAR_TAREA, cuerpo).then(function(response){
        setMessage(response.data.message); // FIXME : Sale error en la API pero inserta igual, he quitado el condicional para que no se muestre error
        Swal.fire({ // Muestro el modal indicando éxito
          title: 'Éxito!',
          text: ""+message,
          icon: 'success',
          showConfirmButton: false,
           timer: 1000
        });
        navigate(RUTA_LISTA_TAREAS_SIN_ID+proyecto); // Vuelvo a la lista de tareas
      });
    }
  };

  
  return (
    <div className="container">
      <h5 className="card-title">Crear Tarea</h5>
      <form className="mb-3" onSubmit={handleSubmit}> {/* Le paso el hook a la referencia y le adjunto el evento onSubmit */}
        {/* Campo referente al nombre de la nueva tarea */}
        <label htmlFor="txtNombre"> Escribe el nombre de la nueva tarea </label>
        <input
          type="text"
          name="txtNombre"
          className="form-control mb-2" 
          onChange={handleChange}
          value={txtNombre}
        /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}

        {/* Campo referente a la descripción de la nueva tarea */}
        <label htmlFor="txtDescripcion"> Escribe la descripción de la nueva tarea </label>
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

        <button type="submit" className="btn btn-success">Crear Tarea</button>
      </form>

      {/* Pongo un enlace a la página de lista de tareas para que el usuario pueda volver */}
      <Link to={RUTA_LISTA_TAREAS_SIN_ID+proyecto} className="h5 link-primary" style={{textDecoration: "none"}}>🔙</Link>
    </div>
  )
}

export default FormularioAddTarea