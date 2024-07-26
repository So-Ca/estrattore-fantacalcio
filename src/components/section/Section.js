import React, {useState} from "react";
import listaGiocatori from "../../json/giocatori.json";
import allenatoriData from "../../json/allenatori.json";
import style from "./section.module.scss";

const Section = () => {

  const [nonEstratti, setNonEstratti] = useState(listaGiocatori);
  const [estratti, setEstratti] = useState([]);
  const [ultimoEstratto, setUltimoEstratto] = useState(null);
  const [estrattiVisibile, setEstrattiVisibile] = useState(false);
  const [squadre, setSquadre] = useState([
    { nome: "squadra1", giocatori: []},
    { nome: "squadra2", giocatori: []},
    { nome: "squadra3", giocatori: []},
    { nome: "squadra4", giocatori: []},
    { nome: "squadra5", giocatori: []},
    { nome: "squadra6", giocatori: []},
    { nome: "squadra7", giocatori: []},
    { nome: "squadra8", giocatori: []},
    { nome: "squadra9", giocatori: []},
    { nome: "squadra10", giocatori: []}
  ]);
  const [gAssegnati, setGAssegnati] = useState(
    {
      squadra1: [],
      squadra2: [],
      squadra3: [],
      squadra4: [],
      squadra5: [],
      squadra6: [],
      squadra7: [],
      squadra8: [],
      squadra9: [],
      squadra10: []
    }
  );
  const [nuovoGiocatore, setNuovoGiocatore] = useState({
    numero: "",
    nome: "",
    ruolo: "",
    prezzo: ""
  });

  const listaFinita = nonEstratti.length === 0;

// funzione per gestire l'input di aggiungi giocatore
  function gestisciInput(e){
    const { name, value } = e.target;
    setNuovoGiocatore(prevValue => ({...prevValue, [name]: value}));
  }

// funzione per aggiungere un giocatore già estratto ma non assegnato
  function aggiungiManualmente(nomeSquadra){
    return function(){
      const {nome, ruolo} = nuovoGiocatore;

      if(nome && ruolo){
        const giocatore = {
          Nome: nome,
          R: ruolo,
        }

        setGAssegnati(prevAssegnati => ( {...prevAssegnati, [nomeSquadra]: [...prevAssegnati[nomeSquadra] || [], giocatore] }));
        setNuovoGiocatore({
          numero: "",
          nome: "",
          ruolo: "",
          prezzo: ""
        });
      } else {
        alert("Non ti hanno insegnato a leggere? 😒\nCompleta tutti i cazzo di campi!")
      }
    }
  }

// Calcolo dei crediti spesi
  function calcolaTotaleSpeso(nomeSquadra){
    const giocatori = gAssegnati[nomeSquadra] || [];
    return giocatori.reduce((somma, giocatore) => somma + (parseInt(giocatore.prezzo) || 0), 0);
  }

// Componente Allenatori
  const allenatori = allenatoriData.map( allenatore => {
    const nomeSquadra = allenatore.Squadra;
    const giocatoriAssegnati = gAssegnati[nomeSquadra] || [];
    const totaleSpeso = calcolaTotaleSpeso(nomeSquadra);
    const pochiCreditiRimasti = totaleSpeso > 500 ? style["crediti-esauriti"] : totaleSpeso === 500 ? style["cinquecento"] : totaleSpeso >= 450 ? style["crediti-bassi"] : style["crediti"];
    const sforato = totaleSpeso > 500;

    return (
      <div className={style["squadra-container"]} key={allenatore.Id}>
        <h3 className={style["nome-squadra"]}>{allenatore.Squadra}</h3>
        {sforato && <span className={style["sforato"]}>Hai Sforato Testa di Cazzo!</span>}
        <p className={`${style["crediti"]} ${pochiCreditiRimasti}`}>Crediti Spesi: {totaleSpeso}</p>
        <h3 className={style["nome-allenatore"]}>Allenatore: <b>{allenatore.Nome}</b></h3>
        <button className={style["btn-assegna-giocatore"]} onClick={assegnaGiocatore(allenatore.Squadra)}>Assegna a {allenatore.Nome}</button>
          <div className={style["giocatori-acquistati"]}>
            <ol className={style["lista-squadra"]}>
              {giocatoriAssegnati.map( (giocatore, index) => (
                <li key={index}>
                  {giocatore.Nome} -&nbsp;
                  {giocatore.R} -&nbsp;
                  <input 
                    className={style["input-prezzo"]} 
                    type="number" 
                    placeholder={giocatore["Qt.A"]}
                    value={giocatore.prezzo} 
                    onChange={(e)=> modificaQuotazione(e, giocatore)} 
                    onKeyPress={(e)=> gestioneInvio(e, giocatore)}
                    onBlur={(e)=> gestioneInvio(e, giocatore)}
                  />
                </li>
              ))}
            </ol>
          </div>
        {listaFinita &&
          <div className={style["box-aggiungi-manualmente"]}>
            <input
              className={style["input-aggiungi-manualmente"]}
              type="text"
              name="nome"
              value={nuovoGiocatore.nome}
              onChange={gestisciInput}
              placeholder="Nome"/>
            <input
              className={style["input-aggiungi-manualmente"]}
              type="text"
              name="ruolo"
              value={nuovoGiocatore.ruolo}
              onChange={gestisciInput}
              placeholder="Ruolo"/>
            <button className={style["btn-aggiungi-manualmente"]} onClick={aggiungiManualmente(nomeSquadra)}>Aggiungi</button>
          </div>
        }
      </div>
    )
  });  

// Funzione per l'estrazione
  function estrai(){
    if(nonEstratti.length > 0) {
      const indiceCasuale = Math.floor( Math.random() * nonEstratti.length );
      const giocatoreEstratto = nonEstratti.splice(indiceCasuale, 1)[0];

      setEstratti( [...estratti, giocatoreEstratto] );
      setUltimoEstratto(giocatoreEstratto);
      setNonEstratti(nonEstratti); 

      console.log("Lista giocatori estratti fino ad ora: ", [...estratti, giocatoreEstratto]);
    } else {
      console.log("Lista finita");
    }
  }

// Funzione per assegnare l'ultimo estratto ad una squadra
  function assegnaGiocatore(squadra){
    return function (event){

      if(ultimoEstratto){
        const giaAssegnato = Object.values(gAssegnati).some( (giocatori) => giocatori.some( (giocatore) => giocatore.Nome === ultimoEstratto.Nome));

        if(!giaAssegnato){
          // const prezzoGiocatore = parseInt(event.target.value, 10);

          setSquadre( (prevSquadre) => prevSquadre.map( (s) => 
          s.nome === squadra ? {...s, giocatori: [...s.giocatori, ultimoEstratto]} : s
          ));

          setGAssegnati( prevAssegnati => {
            const nuovaSquadra = Array.isArray(prevAssegnati[squadra]) ? prevAssegnati[squadra] : [];
            return { ...prevAssegnati, [squadra] : [...nuovaSquadra, ultimoEstratto] };
          });

          setEstratti((prevEstratti) => prevEstratti.map((giocatore) => giocatore.Nome === ultimoEstratto.Nome ? {...giocatore, assegnato: true} : giocatore));

          console.log(`Giocatore assegnato a ${squadra}: `, ultimoEstratto);
        } else {
          alert(`😡 Ehi Ehi Non Barare Infame! 😡\nQuesto Giocatore è già stato assegnato a ${Object.keys(gAssegnati).find(s => gAssegnati[s].some(g => g.Nome ===ultimoEstratto.Nome))}`);
        }
      } else {
        alert("Ma sei Coglione? 🙄\nDevi estrarre un giocatore prima di poterlo assegnare ad una squadra.");
      }
    }
  }

// funzione per modificare il prezzo
  function modificaQuotazione(e, giocatore){
    const nuovoPrezzo = parseInt(e.target.value);
    const aggiornaGiocatoriAssegnati = Object.keys(gAssegnati).reduce((acc, squadra) => {
      const giocatori = gAssegnati[squadra].map( g => ( g === giocatore ? {...g, prezzo: nuovoPrezzo} : g));
      return {...acc, [squadra]: giocatori};
    }, []);
    setGAssegnati(aggiornaGiocatoriAssegnati);
  }

// Gestione della pressione di Enter
  function gestioneInvio(e, giocatore){
    if((e.type === "keypress" && e.key === "Enter") || e.type === "blur"){
      if(!e.target.value){
        e.target.value = e.target.placeholder;
      }
      giocatore.prezzo = e.target.value;
      e.target.disabled = true;
      const spanE = document.createElement("span");
      spanE.textContent = giocatore.prezzo;
      e.target.parentNode.replaceChild(spanE, e.target);
    }
  }

// funzione per mostrare tutti i giocatori estratti
  function toggleEstratti(){
    setEstrattiVisibile(!estrattiVisibile);
  }


  
  return(
    <div className={style["section"]}>
      <div className={style["btn-section"]}>
        <button onClick={estrai} className={style["btn-estrai"]}>Estrai</button>
        <button onClick={toggleEstratti} className={style["btn-mostra-estratti"]}>{estrattiVisibile ? "Nascondi Lista Estratti" : "Mostra Lista Estratti"}</button>
      </div>
      <div className={style["ultimo-estratto"]}>
        <h3 className={style["h3-estratto"]}>Giocatore Estratto:</h3> {ultimoEstratto && (
          <p className={style["p-estratto"]}>
            <b>Nome:</b> {ultimoEstratto.Nome},&nbsp;
            <b>Squadra:</b> {ultimoEstratto.Squadra},&nbsp;
            <b>Prezzo:</b> {ultimoEstratto["Qt.A"]},&nbsp;
            <b>Ruolo:</b> {ultimoEstratto.R}
          </p>
        )}
      </div>
      {listaFinita && <div><h1 className={style["lista-finita"]}>LISTA FINITA</h1><br/><p className={style["commento"]}>😵...era ora...Dio Porco!😩</p></div>}
      <div className={style["allenatori-container"]}>
        {allenatori}
      </div>
      {estrattiVisibile && (
        <div className={style["lista-intera"]}>
          <h3 className={style["h3-lista-intera"]}>Giocatori Estratti:</h3>
          {estratti.map( (giocatore, index) => (
            <div className={`${style["singolo-estratto"]} ${giocatore.assegnato ? style["gia-assegnato"] : ""}`} key={index}>
              <p className={style["p-lista-intera"]}>
                <b>Nome:</b> {giocatore.Nome},&nbsp;
                <b>Squadra:</b> {giocatore.Squadra},&nbsp;
                <b>Ruolo:</b> {giocatore.R},&nbsp;
                <b>Prezzo Base:</b> {giocatore["Qt.A"]}
                {giocatore.assegnato && (<span className={style["span-gia-assegnato"]}>---&nbsp;&nbsp;&nbsp;Assegnato&nbsp;&nbsp;&nbsp;---</span>)}
                </p>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}

export default Section