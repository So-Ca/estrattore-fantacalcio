export default function GiocatoreAssegnato(props) {
    return (
        <li>
            {props.nome} -&nbsp;
            {props.ruolo} -&nbsp;
            {props.prezzo}
            <span style={{ color: "red", fontSize: "2rem", cursor: "pointer" }} onClick={() => props.svincolaGiocatore(props.allenatore, props.id) }>&times;</span>
        </li>
    )
}