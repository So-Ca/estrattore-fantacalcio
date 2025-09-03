import React from "react";
import {Link} from "react-router-dom";
import { useLocation } from "react-router-dom";
import style from "./header.module.scss";
import logoFavaro from "../../assets/img/logo-fantafavaro.png";

const Header = () => {

  const location = useLocation();
  // const isHome = location.pathname === "/";
  const path = location.pathname;
  return(
    <header>
      <div className={style["header"]}>
        <img className={style["logo-favaro"]} src={logoFavaro} alt="Logo Fantafavaro"/>
        <div className={style["title-box"]}>
        <h1 className={style["titolo"]}>FANTAFAVARO</h1>
        {path === "/" && (<span className={style["sottotitolo"]}>Estrattore</span>)}
        {path === "/regolamento" && (<span className={style["sottotitolo"]}>Regolamento</span>)}
        {path === "/storico" && (<span className={style["sottotitolo"]}>Storico</span>)}
        </div>
        <div className={style["link-box"]}>
          {path !== "/" && (<Link to="/" className={style["link"]} title="Torna alla Homepage" target="_blank" rel="noopener noreferrer">Estrattore</Link>)}
          {path !== "/regolamento" && (<Link to="/regolamento" className={style["link"]} title="Leggi le regole per poter partecipare, ignorante!" target="_blank" rel="noopener noreferrer">Regolamento</Link>)}
          {path !== "/storico" && (<Link to="/storico" className={style["link"]} title="Guarda la Hall of Fame del Fantafavaro" target="_blank" rel="noopener noreferrer">Storico</Link>)}
        </div>
      </div>
    </header>
  )
}

export default Header 