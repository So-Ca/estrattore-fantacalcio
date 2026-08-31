import React, { useState, useEffect } from "react";
import GiocatoreAssegnato from "./GiocatoreAssegnato";
// import noLogo from "../../assets/img/loghi-squadre/no-logo.png";

export default function Allenatore(props) {
    console.log(props)
    const [puntata, setPuntata] = useState(props.ultimoEstratto ? props.ultimoEstratto['Qt.A'] : null);
    const [giocatoriAssegnati, setGiocatoriAssegnati] = useState(props.giocatoriAssegnati);
    const [showBio, setShowBio] = useState(false);
    const nomeLogo = props.allenatore.Squadra.replace(/ /g, "-") + ".png";

    useEffect(() => {

        setPuntata(props.ultimoEstratto ? props.ultimoEstratto['Qt.A'] : null)
        setGiocatoriAssegnati(props.giocatoriAssegnati)
    }, [props.ultimoEstratto ? props.ultimoEstratto['Id'] : null, props.giocatoriAssegnati.length, props.isDoingRequest] );

    function toggleBio(){
        setShowBio(prevShow => !prevShow);
    }
 
    return (
        <div className={props.style["squadra-container"]} key={props.allenatore.Id}>
            <img src={`/build/loghi-squadre/${nomeLogo}`} className={props.style["logo-squadra"]} alt="Logo Squadra"/>
            <h3 className={props.style["nome-squadra"]}>{props.allenatore.Squadra}</h3>
            <h3 className={props.style["nome-allenatore"]}>Allenatore: <b>{props.allenatore.Nome}</b></h3>
            <button className={props.style["btn-biografia"]} onClick={toggleBio}>{showBio ? "Nascondi" : "Biografia"}</button>
            {showBio && (<p className={props.style["biografia"]}>{props.allenatore.Commento}</p>)}
            {props.sforato && <span className={props.style["sforato"]}>Hai Sforato Testa di Cazzo!</span>}
            <p className={`${props.style["crediti"]} ${props.pochiCreditiRimasti}`}>Crediti Spesi: {props.totaleSpeso}</p>

            {/* Inizio "form" del singolo allenatore */}
            
            {props.ultimoEstratto && props.role === 'admin' && <><div className={props.style["box-input-prezzo"]}>
                <p className={props.style["testo-prezzo"]}>Crediti da pagare: </p>
                <input className={props.style["input-prezzo"]} type="number" value={puntata} min={props.ultimoEstratto ? props.ultimoEstratto['Qt.A'] : null} onChange={(e) => setPuntata(e.target.value)}/>
            </div><button disabled={props.isDoingRequest} className={props.style["btn-assegna-giocatore"]} onClick={() => props.assegnaGiocatore(props.allenatore.Id, props.ultimoEstratto ? props.ultimoEstratto.Id : null, puntata, props.totaleSpeso)}>Assegna a {props.allenatore.Nome}</button></>

            }
            
            {/* Fine "form" del singolo allenatore */}

            <div className={props.style["giocatori-acquistati"]}>
                <ol className={props.style["lista-squadra"]}>
                    {giocatoriAssegnati.map((giocatore) => (
                        <GiocatoreAssegnato
                            role={props.role}
                            key={giocatore.Id}
                            id={giocatore.Id}
                            nome={giocatore.Nome}
                            ruolo={giocatore.R}
                            prezzo={giocatore.Prezzo ? giocatore.Prezzo : "Prezzo non Stabilito"}
                            allenatore={props.allenatore.Id}
                            svincolaGiocatore={props.svincolaGiocatore}
                            style={props.style}
                        />
                    ))}
                </ol>
            </div>
        </div>
    )
}
