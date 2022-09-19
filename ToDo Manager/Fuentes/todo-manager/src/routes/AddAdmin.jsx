import { useContext, useEffect } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
// Importaciones de componentes personalizados :
import FormularioAddAdmin from "../components/FormularioAddAdmin";

/**
 * Página referente a la creación de un nuevo usuario administrador
 */
const AddUsuario = () => {
  const {comprobarLoginAdmin} = useContext(UserContext); // Consigo la variable del contexto
  
  useEffect(()=>{
    comprobarLoginAdmin(); // Añado comprobación para saber si el usuario loggeado es un administrador
  }, []);

  return (
    <FormularioAddAdmin />
  )
}

export default AddUsuario