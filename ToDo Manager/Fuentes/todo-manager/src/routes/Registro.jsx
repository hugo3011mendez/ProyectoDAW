// Importaciones de componentes personalizados
import FormularioRegistro from "../components/FormularioRegistro"

/**
 * Ruta referente a la página de registro en la app
 */
const Registro = () => {
  return (
    <div style={{background: "linear-gradient(30deg, rgba(136,200,255,1) 0%, rgba(112,252,211,1) 100%)", height: "100vh", overflowX: "none"}}>
      <h2>ToDo Manager</h2>
      
      <div className="container-fluid">
        <div className="row justify-content-center align-items-center">
          <FormularioRegistro />
        </div>
      </div>
    </div>
  )
}

export default Registro