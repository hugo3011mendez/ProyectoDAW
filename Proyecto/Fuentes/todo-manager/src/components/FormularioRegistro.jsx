import { useState } from "react"; // Importamos el hook de React
import { useFormulario } from "../hooks/useFormulario"; // Importación del hook personalizado
import { Link, useNavigate } from "react-router-dom"; // Importación de componentes de React Router DOM
import axios from "axios"; // Importo Axios
import {URL_REGISTRAR_USUARIO} from "../services/API"; // Importación de URLs del archivo de constantes
import emailjs from '@emailjs/browser'; // Importo EmailJS
import Swal from 'sweetalert2' // Importo el paquete de Sweet Alert 2 que he instalado previamente en el proyecto

/**
 * Componente referente al formulario dedicado a registrar un usuario en la base de datos
 */
const FormularioRegistro = () => {
  // Declaro una variable con los valores iniciales que deben tomar los elementos del form
  const initialState = { // Deben tener el mismo nombre que el atributo name de cada elemento
    txtEmail: "",
    txtNickname:"",
    txtPassword: "",
    txtRePassword:""
  };

  const [message, setMessage] = useState(""); // Un hook referente al mensaje de error por defecto una cadena vacía

  const [inputs, handleChange, reset] = useFormulario(initialState); // Uso el hook personalizado en Utils
  const {txtEmail, txtNickname, txtPassword, txtRePassword} = inputs; // Destructuración de los valores de los inputs

  const navigate = useNavigate(); // Establezco el hook referente a cambiar de dirección web

  /**
    * Función para controlar el evento onSubmit
    * @param {*} e Evento onSubmit
    */
  const handleSubmit = e => {
    e.preventDefault();
    
    let regexpEmail = new RegExp(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/); // Creo una expresión regular adecuada para validar el email

    // Compruebo los datos escritos
    if (!txtEmail.trim() || !regexpEmail.test(txtEmail.trim()) || !txtNickname.trim() || !txtPassword.trim() || !txtRePassword.trim()) {
      setMessage("Hay errores en la escritura de los datos"); // Establezco el valor del mensaje de error
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
      axios.post(URL_REGISTRAR_USUARIO, cuerpo).then(function(response){
        if (response.data.success === 1) { // Compruebo si el resultado del success es correcto
          emailjs.send("service_127gzsg","template_qoen1zh",{to_name: txtEmail.trim()}, "EivE0959jTxI2uoZq"); // Mando un email
          setMessage(response.data.message);
          Swal.fire({ // Muestro el modal indicando éxito
            title: 'Éxito!',
            text: ""+message,
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
          });
          navigate("/"); // Vuelvo a la página de login
        }
        else{ // Y en el caso de que haya algún error
          setMessage(response.data.message); // Establezco el valor del mensaje de error
          Swal.fire({ // Muestro el modal indicando error
            title: 'Error',
            text: ""+message,
            icon: 'error',
            showConfirmButton: false,
            timer: 1500
          });
          reset(); // Termino reiniciando el estado de los inputs
        }
      });      
    }
  };

  
  return (
    <> {/* Muestro el formulario dentro de un card */}
      <div className="card p-0" style={{width: "18rem"}}>
        <div className="card-header text-center">
          <h5 className="card-title">Registro</h5>
        </div>
        <div className="card-body">
          <form className="mb-3" onSubmit={handleSubmit}> {/* Le paso el hook a la referencia y le adjunto el evento onSubmit */}
            {/* Campo referente al email del usuario que quiera registrarse */}
            <label htmlFor="txtEmail"> Escribe tu email </label>
            <input
              type="text"
              name="txtEmail"
              className="form-control mb-2" 
              onChange={handleChange}
              value={txtEmail}
            /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}

            {/* Campo referente al nickname del usuario */}
            <label htmlFor="txtNickname"> Escribe tu nickname </label>
            <input
              type="text"
              name="txtNickname"
              className="form-control mb-2" 
              onChange={handleChange}
              value={txtNickname}
            /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}

            {/* Campo referente a la contraseña del nuevo usuario */}
            <label htmlFor="txtPassword"> Escribe tu contraseña </label>
            <input
              type="password"
              name="txtPassword"
              className="form-control mb-2" 
              onChange={handleChange}
              value={txtPassword}
            /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}
            
            {/* Campo referente a la repetición de la contraseña del nuevo usuario */}
            <label htmlFor="txtRePassword"> Repite tu contraseña </label>
            <input
              type="password"
              name="txtRePassword"
              className="form-control mb-2" 
              onChange={handleChange}
              value={txtRePassword}
            /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}

            <button type="submit" className="btn btn-success">Registrarse</button>
          </form>

          {/* Pongo un enlace a la página de login para que el usuario pueda volver en el caso de que se haya equivocado */}
          <Link to={"/"} className="h5 link-primary" style={{textDecoration: "none"}}>🔙</Link>
        </div>
      </div>

    </>
  )
}

export default FormularioRegistro