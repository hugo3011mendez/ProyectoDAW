import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './App';
import { BrowserRouter, Routes, Route } from "react-router-dom";
import UserProvider  from './context/UserProvider'; // Importo el contexto del usuario
import { RUTA_ADMIN, RUTA_MAIN, RUTA_PERFIL, RUTA_REGISTRO } from './services/Rutas'; // Importo el servicio de rutas
// Importaciones de rutas
import Login from './routes/Login';
import Registro from './routes/Registro';
import Main from './routes/Main';
import Perfil from './routes/Perfil';
import Admin from './routes/Admin';
import PageNotFound from './routes/PageNotFound';


const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
  <React.StrictMode>
    <BrowserRouter>
      <UserProvider> {/* Incorporo el contexto para las variables referentes al usuario que ha iniciado sesión */}
        <Routes>
          {/* Aquí van las rutas */}
          <Route path='/' element={<App />}> {/* Para que se muestren las rutas dentro de App, hay que anidarlas y en App.jsx escribir <Outlet /> donde se quieran mostrar */}
            <Route index element={<Login />} /> {/* Este componente comparte la ruta de App - Referente al inicio de sesión */}
            <Route path={RUTA_REGISTRO} element={<Registro />} /> {/* Ruta referente al registro del usuario */}
            <Route path={RUTA_MAIN} element={<Main />} /> {/* Ruta referente a la vista principal */}
            <Route path={RUTA_PERFIL} element={<Perfil />} /> {/* Ruta referente al perfil del usuario */}
            <Route path={RUTA_ADMIN} element={<Admin />} /> {/* Ruta referente al perfil del usuario */}
            <Route path='*' element={<PageNotFound />} /> {/* Este componente se mostrará cada vez que se ponga en la URL algo diferente a las páginas declaradas */}
          </Route>
        </Routes>
      </UserProvider>
    </BrowserRouter>
  </React.StrictMode>
);