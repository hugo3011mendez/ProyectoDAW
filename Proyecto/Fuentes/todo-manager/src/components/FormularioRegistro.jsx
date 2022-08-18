import { useState } from "react"; // Importamos el hook de React
import { useFormulario } from "../hooks/useFormulario"; // Importación del hook personalizado

const FormularioRegistro = () => {

  // Declaro una variable con los valores iniciales que deben tomar los elementos del form
  const initialState = { // Deben tener el mismo nombre que el atributo name de cada elemento
    txtEmail: "",
    txtNickname:"",
    txtPassword: "",
    txtRePassword:""
  };

  const [error, setError] = useState(false); // Un hook referente al error, por defecto a false

  const [inputs, handleChange, reset] = useFormulario(initialState); // Uso el hook personalizado en Utils
  const {txtEmail, txtNickname, txtPassword, txtRePassword} = inputs; // Destructuración de los valores de los inputs


  /**
    * Función para controlar el evento onSubmit
    * @param {*} e Evento onSubmit
    */
  const handleSubmit = e => {
    e.preventDefault();
    
    // Compruebo que los datos escritos estén bien
    if (!txtEmail.trim() || !txtNickname.trim() || !txtPassword.trim() || !txtRePassword.trim() || (txtPassword.trim()!=txtRePassword.trim())) {
      setError(true); // Cambio el error a true ya que hay espacios vacíos
      console.log("ERROR : Hay errores en la escritura de los datos");
    }
    else{
      setError(false); // Cambio el error a false ya que los datos están bien puestos
      console.log("¡Campos validados!");
      console.log("Email : " + txtEmail);
      console.log("Nickname : " + txtNickname);
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

      <form onSubmit={handleSubmit}> {/* Le paso el hook a la referencia y le adjunto el evento onSubmit */}
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
    </>
  )
}

export default FormularioRegistro