import { useState, useContext } from "react"; // Importamos módulos de React
import { Link } from "react-router-dom"; // Importación de componentes de React Router DOM
import { useFormulario } from "../hooks/useFormulario"; // Importación del hook personalizado del form
import axios from "axios"; // Importo Axios
import { URL_LOGIN_USUARIO } from "../services/API"; // Importación de URLs de la API
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
import { RUTA_REGISTRO } from '../services/Rutas'; // Importo el servicio de rutas
import Swal from 'sweetalert2' // Importo el paquete de Sweet Alert 2 que he instalado previamente en el proyecto

/**
 * Componente referente al formulario de inicio de sesión
 */
const FormularioLogin = () => {
  let {id, nickname, rol} = useContext(UserContext); // Consigo las variables del contexto

  // Declaro una variable con los valores iniciales que deben tomar los elementos del form
  const initialState = { // Deben tener el mismo nombre que el atributo name de cada elemento
    txtEmail: "",
    txtPassword: ""
  };

  const [message, setMessage] = useState(""); // Un hook referente al mensaje de error o de acción correcta, por defecto una cadena vacía

  const [inputs, handleChange, reset] = useFormulario(initialState); // Uso el hook personalizado en Utils
  const {txtEmail, txtPassword} = inputs; // Destructuración de los valores de los inputs


  /**
    * Función para controlar el evento onSubmit
    * @param {*} e Evento onSubmit
    */
  const handleSubmit = e => {
    e.preventDefault();

    let regexpEmail = new RegExp(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/); // Creo una expresión regular adecuada para validar el email
    // Compruebo que no haya datos vacíos y que el email se adecúe a la expresión regular
    if (!txtEmail.trim() || !regexpEmail.test(txtEmail.trim()) || !txtPassword.trim()) {
      setMessage("Hay errores en la escritura de los datos");
      Swal.fire({ // Muestro el modal indicando error
        title: 'Error',
        text: ""+message,
        icon: 'error',
        showConfirmButton: false,
        timer: 1500
      });
      reset();
    }
    else{
      // Defino el cuerpo del mensaje que le mandaré a la API con los datos introducidos
      const datosEnviar = {"txtEmail":txtEmail.trim(), "txtPassword":txtPassword.trim()};
      const cuerpo = JSON.stringify(datosEnviar); // Convierto a JSON los datos a enviar a la API
      
      // Realizo la petición a la API enviándole los datos del cuerpo previamente establecido
      axios.post(URL_LOGIN_USUARIO, cuerpo).then(function(response){
        if (response.data.success == 1) { // Si he recibido una respuesta OK
          localStorage.setItem("ID", response.data.usuario.id); // Establezco la ID en el localStorage
          id = response.data.usuario.id; // Establezco la variable referente al ID en el contexto

          localStorage.setItem("nickname", response.data.usuario.nickname); // Establezco el nickname en el localStorage
          nickname = response.data.usuario.nickname; // Establezco la variable referente al nickname en el contexto

          localStorage.setItem("rol", response.data.usuario.rol); // Establezco el rol en el localStorage
          rol = response.data.usuario.rol; // Establezco la variable referente al rol en el contexto

          if (id != "" && nickname != "" && rol != null) { // Compruebo que las variables del contexto estén bien puestas
            setMessage(response.data.message); // Establezco el mensaje que se mostrará en SweetAlert
            Swal.fire({ // Muestro el modal indicando éxito
              title: 'Éxito!',
              text: ""+message,
              icon: 'success',
              showConfirmButton: false,
              timer: 1500
            });

            window.location.reload(); // Recargo la página para que pase a Main
          }
        }
        else{ // Si la respuesta no da OK
          setMessage(response.data.message); // Establezco el mensaje que se mostrará en SweetAlert
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
    <> {/* Meto el formulario dentro de un card */}
      <div className="card p-0" style={{width: "18rem"}}>
        <div className="card-header text-center">
          <h5 className="card-title">Iniciar Sesión</h5>
        </div>
        <div className="card-body">
          <form className="mb-3" onSubmit={handleSubmit}> {/* Le paso el hook a la referencia y le adjunto el evento onSubmit */}
            {/* Campo referente al email del usuario que quiera iniciar sesión */}
            <label htmlFor="txtEmail"> Escribe tu email </label>
            <input
              type="text"
              name="txtEmail"
              className="form-control mb-2" 
              onChange={handleChange}
              value={txtEmail}
            /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}

            {/* Campo referente a la contraseña del usuario que quiere iniciar sesión */}
            <label htmlFor="txtPassword"> Escribe tu contraseña </label>
            <input
              type="password"
              name="txtPassword"
              className="form-control mb-2" 
              onChange={handleChange}
              value={txtPassword}
            /> {/* Le asocio el evento onChange referenciando a su función manejadora y el valor a cambiar correspondiente */}

            <button type="submit" className="btn btn-success mb-3">Iniciar Sesión</button>

            <div className="card-footer">
              {/* Pongo un enlace a la página de registro para que el usuario pueda crearse una cuenta */}
              No tienes una cuenta? <Link to={RUTA_REGISTRO} className="link-primary" style={{textDecoration: "none"}}>Regístrate</Link>
            </div>
          </form>
        </div>
      </div>
    </>
  )
}

export default FormularioLogin