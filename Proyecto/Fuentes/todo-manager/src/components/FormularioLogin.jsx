import { useState } from "react"; // Importamos el hook de React
import { Link } from "react-router-dom"; // Importación de componentes de React Router DOM
import { useFormulario } from "../hooks/useFormulario"; // Importación del hook personalizado

const FormularioLogin = () => {

  // Declaro una variable con los valores iniciales que deben tomar los elementos del form
  const initialState = { // Deben tener el mismo nombre que el atributo name de cada elemento
    txtEmail: "",
    txtPassword: ""
  };

  const [error, setError] = useState(false); // Un hook referente al error, por defecto a false
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

    if (!txtEmail.trim() || !regexpEmail.test(txtEmail.trim()) || !txtPassword.trim()) {
      setMessage("Hay errores en la escritura de los datos");
      setError(true); // Cambio el error a true ya que hay espacios vacíos
      reset();
    }
    else{
      setError(false); // Cambio el error a false ya que los datos están bien puestos
      console.log("Email : " + txtEmail.trim());
      console.log("Password : " + txtPassword.trim());

      // TODO : Realizar comprobaciones de login
    }
  };

  // Creo un nuevo componente pequeño, referente a mostrar el error
  const PintarError = () => (
    <div className="alert alert-danger" role="alert">{message}</div>
  );

  
  return (
    <>
      {/* Compruebo si existe algún error con el hook, y en caso afirmativo pinto el mensaje */}
      {error && <PintarError />} {/* Con '&&' se hace una ternaria con sólo el caso afirmativo */}

      <div className="card p-0" style={{width: "18rem"}}> {/* Un card para meter el formulario dentro */}
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
              No tienes una cuenta? <Link to={"/registro"} className="link-primary" style={{textDecoration: "none"}}>Regístrate</Link>
            </div>
          </form>
        </div>
      </div>
    </>
  )
}

export default FormularioLogin