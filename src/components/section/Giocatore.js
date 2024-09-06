export default function Giocatore(props) {
    return (
        <li>
            {props.nome} -&nbsp;
            {props.ruolo} -&nbsp;
            {props.Prezzo}
            <span style={{ color: "red", fontSize: "2rem", cursor: "pointer" }} onClick={() => alert('svincola giocatore ' + props.Id + ' attualmente dell\'allenatore ' + props.allenatore)}>&times;</span>
        </li>
    )
}