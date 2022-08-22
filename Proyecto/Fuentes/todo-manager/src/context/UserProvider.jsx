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

  return (
    <div> {/* Uso el contexto para pasar la info que quiero que los demás componentes puedan acceder */}
        <UserContext.Provider value={{id, nickname, rol, signOut}}>
          {props.children} {/* Meto a los demás componentes en el Provider */}
        </UserContext.Provider>
    </div>
  )
}

export default UserProvider