import { useState } from 'react'
import './App.css'
import Header from './components/header/header'
import 'bootstrap/dist/css/bootstrap.min.css';
import { BrowserRouter, Route, Routes } from 'react-router';
import Participantes from './pages/Participantes/participantes';
import Instrutores from './pages/Instrutores/instrutores';
import Oficinas from './pages/Oficinas/oficinas';
import Inscricoes from './pages/Inscricoes/inscricoes';
import { ToastContainer } from 'react-toastify';

function App() {
 

  return (
    <>
    <ToastContainer position='top-right' autoClose={3000}/>
    <BrowserRouter>
    <Header/>
    <Routes>
      <Route path='/participantes' element={<Participantes />}/>
      <Route path='/instrutores' element={<Instrutores />}/>
      <Route path='/oficinas' element={<Oficinas />}/>
      <Route path='/inscricoes' element={<Inscricoes />}/>

    </Routes>
    </BrowserRouter>
    </>
  )
}

export default App
