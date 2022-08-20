import { Outlet } from "react-router-dom";

// Aplicación principal
const App = () => {
  return (
    <div> {/* TODO : Ver cómo ponerlo del tamaño de toda la ventana */}
      <Outlet /> {/* Con esta etiqueta indicamos que aquí se debe pintar el componente referente a la ruta en la que esté */}
    </div>
  )
}

export default App