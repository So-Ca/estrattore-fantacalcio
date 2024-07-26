import React from "react"
import style from "./header.module.scss"
import logoFavaro from "../../assets/img/logo-fantafavaro.png";

const Header = () => {
  return(
    <header>
      <div className={style["header"]}>
        <img className={style["logo-favaro"]} src={logoFavaro} alt="Logo Fantafavaro"/>
        <h1 className={style["titolo"]}>FANTAFAVARO</h1>
        <h2 className={style["sottotitolo"]}>Estrattore</h2>
      </div>
    </header>
  )
}

export default Header 