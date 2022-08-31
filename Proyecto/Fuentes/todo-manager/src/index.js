import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './App';
import { BrowserRouter, Routes, Route } from "react-router-dom";
import UserProvider  from './context/UserProvider'; // Importo el contexto del usuario
// Importo el servicio de rutas
import { RUTA_ADMIN, RUTA_EDITAR_PROYECTO, RUTA_CREAR_PROYECTO, RUTA_EDITAR_TAREA, RUTA_MAIN, RUTA_PERFIL, RUTA_REGISTRO, RUTA_CREAR_TAREA, RUTA_LISTA_TAREAS } from './services/Rutas';
// Importaciones de rutas
import Login from './routes/Login';
import Registro from './routes/Registro';
import Main from './routes/Main';
import Perfil from './routes/Perfil';
import Admin from './routes/Admin';
import PageNotFound from './routes/PageNotFound';
import EditarProyecto from './routes/EditarProyecto';
import EditarTarea from './routes/EditarTarea';
import AddProyecto from './routes/AddProyecto';
import AddTarea from './routes/AddTarea';
import ListaTareas from './routes/ListaTareas';

/**
 * Desde este archivo se enrutan todos los demás
 */
const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
  <React.StrictMode>
    <BrowserRouter>
      <UserProvider> {/* Incorporo el contexto para las variables referentes al usuario que ha iniciado sesión */}
        <Routes> {/* Aquí van todas las rutas */}
          <Route path="/" element={<Login />} /> {/* Ruta referente al inicio de sesión */}
          <Route path={RUTA_REGISTRO} element={<Registro />} /> {/* Ruta referente al registro del usuario */}
          {/* Este componente se mostrará cada vez que se ponga en la URL algo diferente a las páginas declaradas */}
          <Route path='*' element={<PageNotFound />} />

          {/* Vista de todas las rutas excepto login y registro */}
          <Route path={RUTA_MAIN} element={<App />} >
            <Route index element={<Main />} /> {/* Este componente comparte la ruta de App - Referente a la vista principal */}
            <Route path={RUTA_LISTA_TAREAS} element={<ListaTareas />} /> {/* La lista de las tareas según el proyecto que se clicke */}
            <Route path={RUTA_EDITAR_PROYECTO} element={<EditarProyecto />} /> {/* Para editar un proyecto en la BBDD */}
            <Route path={RUTA_CREAR_PROYECTO} element={<AddProyecto />} /> {/* Para editar un proyecto en la BBDD */}
            <Route path={RUTA_EDITAR_TAREA} element={<EditarTarea />} /> {/* Para editar una tarea en la BBDD */}
            <Route path={RUTA_CREAR_TAREA} element={<AddTarea />} /> {/* Para crear una tarea en la BBDD */}
            <Route path={RUTA_PERFIL} element={<Perfil />} /> {/* Este componente comparte la ruta de App - Referente a la vista principal */}
            <Route path={RUTA_ADMIN} element={<Admin />} /> {/* Ruta referente al menú de admin del usuario */}
          </Route>
        </Routes>
      </UserProvider>
    </BrowserRouter>
  </React.StrictMode>
);