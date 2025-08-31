import React, { useState, useEffect } from "react";

export default function GiocatoreEstratto(props) {

    const [puntata, setPuntata] = useState(props.giocatore['Qt.A']);
    const [allenatoreScelto, setAllenatoreScelto] = useState(0);
    const [assegnato, setAssegnato] = useState(props.giocatore.AllenatoreId);

    
    useEffect(() => {
        let assegnato = props.giocatore.AllenatoreId;
        if(!assegnato) {
            Object.values(props.gAssegnati).forEach((allenatore) => {
                allenatore.forEach((giocatore) => {
                    if(giocatore.Id === props.giocatore.Id) {
                        setAssegnato(true);
                    }
                });
            });
        }
    }, [props.gAssegnati]);

    return (<div className={`${props.style["singolo-estratto"]} ${assegnato ? props.style["gia-assegnato"] : ""}`}>
        <p className={props.style["p-lista-intera"]}>
            <b>Nome:</b> {props.giocatore.Nome},&nbsp;
            <b>Squadra:</b> {props.giocatore.Squadra},&nbsp;
            <b>Ruolo:</b> {props.giocatore.R},&nbsp;
            <b>Prezzo Base:</b> {props.giocatore["Qt.A"]}
            {assegnato && (<span className={props.style["span-gia-assegnato"]}>---&nbsp;&nbsp;&nbsp;Assegnato&nbsp;&nbsp;&nbsp;---</span>)}
            {!assegnato && (<input className={props.style["input-prezzo-svincolato"]} type="number" min={props.giocatore["Qt.A"]} value={puntata} onChange={(e) => setPuntata(e.target.value)} />)}
            {!assegnato && (<select className={props.style["select-svincolato"]}onChange={(e) => setAllenatoreScelto(Number(e.target.value))}>
                <option value="0">Scegli un Allenatore</option>
                {props.allenatori.map((allenatore) => (
                    <option className={props.style["option-svincolato"]} key={allenatore.Id} value={allenatore.Id}>{allenatore.Nome}</option>
                ))}
            </select>)}
            {!assegnato && (<button className={props.style["btn-assegna-svincolato"]} disabled={props.isDoingRequest} onClick={() => props.assegnaGiocatore(allenatoreScelto, props.giocatore.Id, puntata, props.calcolaTotaleSpeso(allenatoreScelto))}>Assegna</button>)}
        </p>
    </div>)
}