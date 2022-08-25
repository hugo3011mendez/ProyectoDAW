import { createContext } from 'react' // Importación de módulos de React
import { useNavigate } from "react-router-dom"; // Importación de componentes de React Router DOM

export const UserContext = createContext(); // Creo el contexto

// Contexto referente al usuario
const UserProvider = (props) => { // Los demás componentes que estemos viendo accederán aquí
  // Referente a la ID del usuario que haya iniciado sesión
  let id = localStorage.getItem("ID") ? localStorage.getItem("ID") : "";
  // Referente al nickname del usuario que haya iniciado sesión
  let nickname = localStorage.getItem("nickname") ? localStorage.getItem("nickname") : "";
  // Referente al rol del usuario
  let rol = localStorage.getItem("rol") ? localStorage.getItem("rol") : null;
  
  const navigate = useNavigate(); // Establezco el hook referente a cambiar de ruta

  /**
   * Referente al cierre de sesión por parte del usuario
   */
  const signOut = () => {
    // Establezco las propiedades a su estado inicial
    id ="";
    nickname="";
    rol = null;
    
    localStorage.clear(); // Limpio los elementos del localStorage
    navigate("/"); // Y vuelvo a la página de inicio
  };

  /**
   * Comprueba si están presentes los datos de inicio de sesión
   * Y en caso de que no lo estén, vuelve a la página de login
   */
  const comprobarLogin = () => {
    // Compruebo si no están guardados los datos de la sesión
    if (id == "" && nickname == "" && rol == null) {
      navigate("/"); // Y vuelvo a la página del login
    }
  }

  /**
   * Comprueba si están presentes los datos de inicio de sesión para la página del admin
   * Y en caso de que no lo estén, vuelve a la página de login
   */
   const comprobarLoginAdmin = () => {
    // Compruebo si no están guardados los datos de la sesión
    if (id == "" && nickname == "" && rol == null) {
      navigate("/"); // Y vuelvo a la página del login
    }
  }

  return (
    <div> {/* Uso el contexto para pasar la info que quiero que los demás componentes puedan acceder */}
        <UserContext.Provider value={{id, nickname, rol, signOut, comprobarLogin, comprobarLoginAdmin}}>
          {props.children} {/* Meto a los demás componentes en el Provider */}
        </UserContext.Provider>
    </div>
  )
}

export default UserProvider