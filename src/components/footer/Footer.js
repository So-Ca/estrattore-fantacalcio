import React from "react";
import style from "./footer.module.scss";
import logoSoCa from "../../assets/img/logo-so-ca.png";

const Footer = () => {
  return(
    <footer>
      <p id={style["copyright"]}>This app has been made by <a id={style["portfolio"]} href="https://sonny-caputo-portfolio.netlify.app/" target="_blank" rel="noopener noreferrer">Sonny Caputo</a> &copy;</p>
      <img id={style["logo"]} src={logoSoCa} alt="Logo So-Ca"/>
    </footer>
  )
}

export default Footer