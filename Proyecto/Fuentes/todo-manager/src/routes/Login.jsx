import { useEffect } from "react"; // Importación de hooks de React
import { useNavigate } from "react-router-dom"; // Importación de componentes de React Router DOM
import { RUTA_MAIN } from '../services/Rutas'; // Importo el servicio de rutas
// Importaciones de componentes personalizados
import FormularioLogin from '../components/FormularioLogin';

// Ruta referente a la página de Login del usuario
const Login = () => {
  const navigate = useNavigate(); // Establezco el hook referente a cambiar de ruta
  // Meto la comprobación del inicio de sesión dentro de este hook para que lo compruebe cada vez que se tenga que renderizar la ruta
  useEffect(()=>{
    if (localStorage.getItem("ID") && localStorage.getItem("nickname")) { // TODO : Mejorar la redirección
      navigate(RUTA_MAIN);
    }
  }, []);

  return (
    <div style={{background: "linear-gradient(30deg, rgba(136,200,255,1) 0%, rgba(112,252,211,1) 100%)"}}>
      <h2>ToDo Manager</h2>
      <div className="row justify-content-center align-items-center">
        <FormularioLogin />
      </div>
    </div>
  )
}

export default Login