import { Outlet } from "react-router-dom"; // Importaciones de módulos de React Router DOM
// Importaciones de componentes :
import Navbar from "./components/Navbar"; 

/**
 * Aplicación principal, desde aquí se mostrará toda la información pertinente después de que un usuario se logguee
 */
const App = () => {
  return (
    <div>
      <Navbar /> {/* Llamo al componente antes del título porque es un Navbar */}

      <div style={{height: "85vh", overflowX: "none"}}>
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