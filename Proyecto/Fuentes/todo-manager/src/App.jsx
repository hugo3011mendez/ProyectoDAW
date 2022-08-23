import { Outlet } from "react-router-dom";
import Navbar from "./components/Navbar";

// Aplicación principal
const App = () => {
  return (
    <div>
      <Navbar /> {/* Llamo al componente antes del título porque es un Navbar */}

      <div className="container">
        <Outlet /> {/* Con esta etiqueta indicamos que aquí se debe pintar el componente referente a la ruta en la que esté */}
      </div>

      {/* Añadido footer */}
      <footer className="bg-light text-center text-lg-start">
        <div className="text-center p-3"> Hugo Méndez © 2022 </div>
      </footer>
    </div>
  )
}

export default App