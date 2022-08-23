import { useState, useContext } from "react"; // Importamos el hook de React
import { useFormulario } from "../hooks/useFormulario"; // Importación del hook personalizado referente al form
import axios from "axios"; // Importo Axios
import { URL_ACTUALIZAR_USUARIO } from "../services/API"; // Importación de URLs del archivo de constantes
import Swal from 'sweetalert2' // Importo el paquete de Sweet Alert 2 que he instalado previamente en el proyecto
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario


const FormularioCambioPerfil = ({usuario}) => {
 
  // Declaro una variable con los valores iniciales que deben tomar los elementos del form
  const initialState = { // El estado inicial de los campos es igual al de los valores del usuario
    txtEmail: usuario.email,
    txtNickname: usuario.nickname,
    txtPassword: usuario.pwd,
    txtRePassword: ""
  };

  const [message, setMessage] = useState(""); // Un hook referente al mensaje de error por defecto una cadena vacía

  const [inputs, handleChange, reset] = useFormulario(initialState); // Uso el hook personalizado en Utils
  const {txtEmail, txtPassword, txtNickname, txtRePassword} = inputs; // Destructuración de los valores de los inputs

  /**
    * Función para controlar el evento onSubmit
    * @param {*} e Evento onSubmit
    */
  const handleSubmit = e => {
    e.preventDefault();
    
    let regexpEmail = new RegExp(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/); // Creo una expresión regular adecuada para validar el email

    // Compruebo los datos escritos
    if (!txtEmail.trim() || !regexpEmail.test(txtEmail.trim()) || !txtNickname.trim() || !txtPassword.trim() || !txtRePassword.trim()) {
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
    else if(txtPassword.trim()!==txtRePassword.trim()){ // En el caso de que las contraseñas no coincidan
      setMessage("Las contraseñas no coinciden"); // Establezco el valor del mensaje de error
      Swal.fire({ // Muestro el modal indicando error
        title: 'Error',
        text: ""+message,
        icon: 'error',
        showConfirmButton: false,
        timer: 1500
      });
    }
    else{
      // Defino el cuerpo del mensaje que le mandaré a la API con los datos introducidos
      // Cada usuario que se registre tendrá el rol 1 : Usuario
      const datosEnviar = {"txtEmail":txtEmail.trim(), "txtNickname":txtNickname.trim(), "txtPassword":txtPassword.trim(), "rol":1};
      const cuerpo = JSON.stringify(datosEnviar); // Convierto a JSON los datos a enviar a la API

      // Realizo la petición a la API con Axios
      axios.post(URL_ACTUALIZAR_USUARIO, cuerpo).then(function(response){
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
        {/* Campo referente al email del usuario que quiera registrarse */}
        <label htmlFor="txtEmail"> Email : </label>
        <input
          type="text"
          name="txtEmail"
          className="form-control mb-2" 
          onChange={handleChange}
          value={txtEmail}
        /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}

        {/* Campo referente al nickname del usuario */}
        <label htmlFor="txtNickname"> Nickname : </label>
        <input
          type="text"
          name="txtNickname"
          className="form-control mb-2" 
          onChange={handleChange}
          value={txtNickname}
        /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}

        {/* Campo referente a la contraseña del nuevo usuario */}
        <label htmlFor="txtPassword"> Contraseña : </label>
        <input
          type="password"
          name="txtPassword"
          className="form-control mb-2" 
          onChange={handleChange}
          value={txtPassword}
        /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}
            
        {/* Campo referente a la repetición de la contraseña del nuevo usuario */}
        <label htmlFor="txtRePassword"> Repite la contraseña : </label>
        <input
          type="password"
          name="txtRePassword"
          className="form-control mb-2" 
          onChange={handleChange}
          value={txtRePassword}
        /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}

        <button type="submit" className="btn btn-warning">Actualizar datos</button>
      </form>
    </>
  )
}

export default FormularioCambioPerfil