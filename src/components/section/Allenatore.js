import React, { useState, useEffect } from "react";
export default function Allenatore(props) {


    const [puntata, setPuntata] = useState(props.ultimoEstratto['Qt.A']);
    const [giocatoriAssegnati, setGiocatoriAssegnati] = useState(props.giocatoriAssegnati);

    useEffect(() => {
        setPuntata(props.ultimoEstratto['Qt.A'])
        setGiocatoriAssegnati(props.giocatoriAssegnati)
    }, [props.ultimoEstratto['Id'], props.giocatoriAssegnati.length]);


    let totaleSpeso = 0;
    props.giocatoriAssegnati.forEach(function(giocatore) {
        totaleSpeso += giocatore.Prezzo;
    });

    return (<div className={props.style["squadra-container"]} key={props.allenatore.Id}>
        <h3 className={props.style["nome-squadra"]}>{props.allenatore.Squadra}</h3>
        {props.sforato && <span className={props.style["sforato"]}>Hai Sforato Testa di Cazzo!</span>}
        <p className={`${props.style["crediti"]} ${props.pochiCreditiRimasti}`}>Crediti Spesi: {totaleSpeso}</p>
        <h3 className={props.style["nome-allenatore"]}>Allenatore: <b>{props.allenatore.Nome}</b></h3>

        {/* Inizio "form" del singolo allenatore */}
        <input type="number" value={puntata} min={props.ultimoEstratto['Qt.A']} onChange={(e) => setPuntata(e.target.value)}/>
        <button className={props.style["btn-assegna-giocatore"]} onClick={props.assegnaGiocatore(props.allenatore.Id, props.ultimoEstratto.Id, puntata)}>Assegna a {props.allenatore.Nome}</button>
        {/* Fine "form" del singolo allenatore */}
        
        <div className={props.style["giocatori-acquistati"]}>
            <ol className={props.style["lista-squadra"]}>
                {giocatoriAssegnati.map((giocatore, index) => (
                    <li key={giocatore.Id}>
                        {giocatore.Nome} -&nbsp;
                        {giocatore.R} -&nbsp;
                        {giocatore.Prezzo ? giocatore.Prezzo : "Prezzo non Stabilito"}
                        <span style={{color:"red", fontSize: "2rem", cursor : "pointer"}} onClick={() => alert('svincola giocatore '+giocatore.Id + ' attualmente dell\'allenatore '+props.allenatore.Id)}>&times;</span>
                    </li>
                ))}
            </ol>
        </div>
        {props.listaFinita &&
            <div className={props.style["box-aggiungi-manualmente"]}>
                <input
                    className={props.style["input-aggiungi-manualmente"]}
                    type="text"
                    name="nome"
                    value={props.nuovoGiocatore.nome}
                    onChange={props.gestisciInput}
                    placeholder="Nome" />
                <input
                    className={props.style["input-aggiungi-manualmente"]}
                    type="text"
                    name="ruolo"
                    value={props.nuovoGiocatore.ruolo}
                    onChange={props.gestisciInput}
                    placeholder="Ruolo" />
                <button className={props.style["btn-aggiungi-manualmente"]} onClick={props.aggiungiManualmente(props.idSquadra)}>Aggiungi</button>
            </div>
        }
    </div>)
}
