import { useEffect } from "react"; // Importación de hooks de React
import { useNavigate } from "react-router-dom"; // Importación de componentes de React Router DOM
import { RUTA_MAIN } from '../services/Rutas'; // Importo el servicio de rutas
// Importaciones de componentes personalizados
import FormularioLogin from '../components/FormularioLogin';

/**
 * Ruta referente a la página donde el usuario debe iniciar sesión en la app
 */
const Login = () => {
  const navigate = useNavigate(); // Establezco el hook referente a cambiar de ruta
  
  // Si existen los elementos en el localStorage significa que el usuario habrá iniciado sesión así que voy directamente hacia Main
  useEffect(()=>{ // Uso el hook para que se ejecute la primera vez que se renderice
    if (localStorage.getItem("ID") && localStorage.getItem("nickname")) {
      navigate(RUTA_MAIN);
    }
  }, []);

  return (
    <div style={{background: "linear-gradient(30deg, rgba(136,200,255,1) 0%, rgba(112,252,211,1) 100%)", height: "100vh", overflowX: "none"}}>
      <h2>ToDo Manager</h2>

      <div className="container-fluid">
        <div className="row justify-content-center align-items-center">
          <FormularioLogin />
        </div>
      </div>
    </div>
  )
}

export default Login