import React from "react";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Header from "./components/header/Header";
import Section from "./components/section/Section";
import Footer from "./components/footer/Footer";
import Regolamento from "./components/sideComponents/Regolamento";
import Storico from "./components/sideComponents/Storico";
import "./index.css";



const App = () => {

  return(
    <BrowserRouter>
      <main>
        <Header/>
        <Routes>
          <Route path="/" element={<Section/>}/>
          <Route path="/regolamento" element={<Regolamento/>}/>
          <Route path="/storico" element={<Storico/>}/>
        </Routes>
        <Footer/>
      </main>
    </BrowserRouter>
  )
}

export default App