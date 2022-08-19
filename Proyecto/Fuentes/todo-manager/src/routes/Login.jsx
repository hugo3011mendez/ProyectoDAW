// Importaciones de componentes personalizados
import FormularioLogin from '../components/FormularioLogin';
import { URL_LEER_ROL } from '../services/API';


// Ruta referente a la página de Login del usuario
const Login = () => {
  return (
    <>
      <h2>{URL_LEER_ROL}</h2>
      <FormularioLogin />
    </>
  )
}

export default Login