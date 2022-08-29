import { useContext, useEffect } from "react"; // Importamos módulos de React
import { UserContext } from '../context/UserProvider'; // Importo el contexto del usuario
// Importaciones de componentes personalizados :
import FormularioAddProyecto from "../components/FormularioAddProyecto";


const AddProyecto = () => {
    const {comprobarLogin} = useContext(UserContext); // Consigo la variable del contexto
  
    useEffect(()=>{
      comprobarLogin(); // Añado comprobación del login
    }, []);
      

  return (
    <FormularioAddProyecto />
  )
}

export default AddProyecto