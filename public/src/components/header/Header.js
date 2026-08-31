import React from "react";
import { Link } from "react-router-dom";
import { useLocation } from "react-router-dom";
import style from "./header.module.scss";
import logoFavaro from "../../assets/img/logo-fantafavaro.png";

const Header = () => {

  const location = useLocation();
  // const isHome = location.pathname === "/";
  const path = location.pathname;
  return (
    <><header>
      <div className={style["header"]}>
        <img className={style["logo-favaro"]} src={logoFavaro} alt="Logo Fantafavaro" />
        <div className={style["title-box"]}>
          <h1 className={style["titolo"]}>FANTAFAVARO</h1>
          {path === "/" && (<span className={style["sottotitolo"]}>Estrattore</span>)}
          {path === "/regolamento" && (<span className={style["sottotitolo"]}>Regolamento</span>)}
          {path === "/storico" && (<span className={style["sottotitolo"]}>Storico</span>)}
        </div>

      </div>
    </header>
      <div className={style["link-box"]}>
        <a href="/" className={style["link"]} title="Torna alla Homepage" rel="noopener noreferrer">Estrattore</a>
        <a href="/report" className={style["link"]} title="Report" rel="noopener noreferrer">Report</a>
        {/* <Link to="/" className={style["link"]} title="Torna alla Homepage" rel="noopener noreferrer">Estrattore</Link>
          <Link to="/report" className={style["link"]} title="Report" rel="noopener noreferrer">Report</Link> */}
        <a href="/regolamento" className={style["link"]} title="Leggi le regole per poter partecipare, ignorante!" el="noopener noreferrer">Regolamento</a>
        <a href="/storico" className={style["link"]} title="Guarda la Hall of Fame del Fantafavaro" rel="noopener noreferrer">Storico</a>
      </div></>
  )
}

export default Header 