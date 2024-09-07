export default function GiocatoreEstratto(props) {
    return (<div className={`${props.style["singolo-estratto"]} ${props.giocatore.AllenatoreId ? props.style["gia-assegnato"] : ""}`}>
        <p className={props.style["p-lista-intera"]}>
            <b>Nome:</b> {props.giocatore.Nome},&nbsp;
            <b>Squadra:</b> {props.giocatore.Squadra},&nbsp;
            <b>Ruolo:</b> {props.giocatore.R},&nbsp;
            <b>Prezzo Base:</b> {props.giocatore["Qt.A"]}
            {props.giocatore.AllenatoreId && (<span className={props.style["span-gia-assegnato"]}>---&nbsp;&nbsp;&nbsp;Assegnato&nbsp;&nbsp;&nbsp;---</span>)}
            {!props.giocatore.AllenatoreId && (<input type="number" min={props.giocatore["Qt.A"]} value={props.giocatore["Qt.A"]} />)}
            {!props.giocatore.AllenatoreId && (<select>
                <option value="0">Scegli un Allenatore</option>
                {props.allenatori.map((allenatore) => (
                    <option key={allenatore.Id} value={allenatore.Id}>{allenatore.Nome}</option>
                ))}
            </select>)}
            {!props.giocatore.AllenatoreId && (<button>Assegna</button>)}
        </p>
    </div>)
}