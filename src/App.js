import React from "react";
import Header from "./components/header/Header";
import Section from "./components/section/Section";
import Prove from "./components/section/Prove";
import Footer from "./components/footer/Footer";
import "./index.css";



const App = () => {

  return(
    <main>
      <Header/>
      <Section/>
      {/* <Prove/> */}
      <Footer/>
    </main>
  )
}

export default App