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

  const [inputs, handleChange, reset] = useFormulario(initialState); // Uso el hook personalizado en Utils
  const {txtEmail, txtPassword} = inputs; // Destructuración de los valores de los inputs


  /**
    * Función para controlar el evento onSubmit
    * @param {*} e Evento onSubmit
    */
  const handleSubmit = e => {
    e.preventDefault();
    
    if (!txtEmail.trim() || !txtPassword.trim()) {
      setError(true); // Cambio el error a true ya que hay espacios vacíos
      console.log("ERROR : Hay datos vacíos");
    }
    else{
      setError(false); // Cambio el error a false ya que los datos están bien puestos
      console.log("¡Campos validados!");
      console.log("Email : " + txtEmail);
      console.log("Password : " + txtPassword);
    }
  };


  // Creo un nuevo componente pequeño, referente a mostrar el error
  const PintarError = () => (
    <div className="alert alert-danger"  role="alert">Todos los campos son obligatorios</div>
  );

  
  return (
    <>
      {/* Compruebo si existe algún error con el hook, y en caso afirmativo pinto el mensaje */}
      {error && <PintarError />} {/* Con '&&' se hace una ternaria con sólo el caso afirmativo */}

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

        <button type="submit" className="btn btn-success">Iniciar Sesión</button>
      </form>
      
      {/* Pongo un enlace a la página de registro para que el usuario pueda crearse una cuenta */}
      <Link to={"/registro"} className="link-primary">No tienes una cuenta? Regístrate</Link>
    </>
  )
}

export default FormularioLogin