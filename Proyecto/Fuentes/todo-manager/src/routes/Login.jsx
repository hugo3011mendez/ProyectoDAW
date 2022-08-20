// Importaciones de componentes personalizados
import FormularioLogin from '../components/FormularioLogin';

// Ruta referente a la página de Login del usuario
const Login = () => {
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