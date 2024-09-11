export default function GiocatoreAssegnato(props) {
    return (
        <li>
            {props.nome} -&nbsp;
            {props.ruolo} -&nbsp;
            {props.prezzo}
            <span className={props.style["x-svincola"]} onClick={() => props.svincolaGiocatore(props.allenatore, props.id) }>&times;</span>
        </li>
    )
}