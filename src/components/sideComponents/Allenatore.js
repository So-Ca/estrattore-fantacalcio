import React, { useState, useEffect } from "react";
import GiocatoreAssegnato from "./GiocatoreAssegnato";
export default function Allenatore(props) {


    const [puntata, setPuntata] = useState(props.ultimoEstratto['Qt.A']);
    const [giocatoriAssegnati, setGiocatoriAssegnati] = useState(props.giocatoriAssegnati);
    const [showBio, setShowBio] = useState(false);

    useEffect(() => {

        setPuntata(props.ultimoEstratto['Qt.A'])
        setGiocatoriAssegnati(props.giocatoriAssegnati)
    }, [props.ultimoEstratto['Id'], props.giocatoriAssegnati.length, props.isDoingRequest] );

    function toggleBio(){
        setShowBio(prevShow => !prevShow);
    }
 
    return (
        <div className={props.style["squadra-container"]} key={props.allenatore.Id}>
            <h3 className={props.style["nome-squadra"]}>{props.allenatore.Squadra}</h3>
            <h3 className={props.style["nome-allenatore"]}>Allenatore: <b>{props.allenatore.Nome}</b></h3>
            <button className={props.style["btn-biografia"]} onClick={toggleBio}>{showBio ? "Nascondi" : "Biografia"}</button>
            {showBio && (<p className={props.style["biografia"]}>{props.allenatore.Commento}</p>)}
            {props.sforato && <span className={props.style["sforato"]}>Hai Sforato Testa di Cazzo!</span>}
            <p className={`${props.style["crediti"]} ${props.pochiCreditiRimasti}`}>Crediti Spesi: {props.totaleSpeso}</p>

            {/* Inizio "form" del singolo allenatore */}
            <div className={props.style["box-input-prezzo"]}>
                <p className={props.style["testo-prezzo"]}>Crediti da pagare: </p>
                <input className={props.style["input-prezzo"]} type="number" value={puntata} min={props.ultimoEstratto['Qt.A']} onChange={(e) => setPuntata(e.target.value)}/>
            </div>
            <button disabled={props.isDoingRequest} className={props.style["btn-assegna-giocatore"]} onClick={() => props.assegnaGiocatore(props.allenatore.Id, props.ultimoEstratto.Id, puntata, props.totaleSpeso)}>Assegna a {props.allenatore.Nome}</button>
            {/* Fine "form" del singolo allenatore */}

            <div className={props.style["giocatori-acquistati"]}>
                <ol className={props.style["lista-squadra"]}>
                    {giocatoriAssegnati.map((giocatore) => (
                        <GiocatoreAssegnato
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
