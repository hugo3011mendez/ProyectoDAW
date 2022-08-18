import { Outlet } from "react-router-dom";

// Aplicación principal
const App = () => {
  return (
    <div>
        <div className="container">
          <Outlet /> {/* Con esta etiqueta indicamos que aquí se debe pintar el componente referente a la ruta en la que esté */}
        </div>
    </div>
  )
}

export default App