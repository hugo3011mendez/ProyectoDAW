import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './App';
import { BrowserRouter, Routes, Route } from "react-router-dom";
// Importaciones de rutas
import Login from './routes/Login';
import Perfil from './routes/Perfil';
import Registro from './routes/Registro';
import PageNotFound from './routes/PageNotFound';


const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
  <React.StrictMode>
    <BrowserRouter>
      <Routes>
        {/* Aquí van las rutas */}
        {/* TODO : Guardar URLs en constantes */}
        <Route path='/' element={<App />}> {/* Para que se muestren las rutas dentro de App, hay que anidarlas y en App.jsx escribir <Outlet /> donde se quieran mostrar */}
          <Route index element={<Login />} /> {/* Este componente comparte la ruta de App - Referente al inicio de sesión */}
          <Route path='/registro' element={<Registro />} /> {/* Ruta referente al registro del usuario */}
          <Route path='/perfil' element={<Perfil />} /> {/* Ruta referente al perfil del usuario */}
          <Route path='*' element={<PageNotFound />} /> {/* Este componente se mostrará cada vez que se ponga en la URL algo diferente a las páginas declaradas */}
        </Route>
      </Routes>
    </BrowserRouter>
  </React.StrictMode>
);