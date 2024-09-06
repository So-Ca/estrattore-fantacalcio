import React, { useState, useEffect } from "react";
import allenatoriData from "../../json/allenatori.json";
import style from "./section.module.scss";
import Allenatore from "./Allenatore";
import { all } from "axios";

const Section = () => {

  const [nonEstratti, setNonEstratti] = useState([]);
  const [estratti, setEstratti] = useState([]);
  const [ultimoEstratto, setUltimoEstratto] = useState(null);
  const [estrattiVisibile, setEstrattiVisibile] = useState(false);

  const [squadre, setSquadre] = useState([
    { nome: "1", giocatori: [] },
    { nome: "2", giocatori: [] },
    { nome: "3", giocatori: [] },
    { nome: "4", giocatori: [] },
    { nome: "5", giocatori: [] },
    { nome: "6", giocatori: [] },
    { nome: "7", giocatori: [] },
    { nome: "8", giocatori: [] },
    { nome: "9", giocatori: [] },
    { nome: "10", giocatori: [] }
  ]);
  const [gAssegnati, setGAssegnati] = useState(
    {
      "1": [],
      "2": [],
      "3": [],
      "4": [],
      "5": [],
      "6": [],
      "7": [],
      "8": [],
      "9": [],
      "10": []
    }
  );
  const [nuovoGiocatore, setNuovoGiocatore] = useState({
    numero: "",
    nome: "",
    ruolo: "",
    prezzo: ""
  });

  const listaFinita = nonEstratti.length === 0;

  // Fetch dei giocatori estratti e nonEstratti al caricamento della pagina
  useEffect(() => {
    const fetchData = async () => {
      try {
        const estrattiResponse = await fetch("http://localhost:8000/api/giocatori/estratti");
        let estrattiData = await estrattiResponse.json();

        if (!estrattiData.length) {
          setUltimoEstratto(null);
        } else {
          // dal più vecchio al più recente
          estrattiData = estrattiData.sort(function (a, b) {
            if (a.Order > b.Order) {
              return 1;
            } else if (a.Order < b.Order) {
              return -1;
            } else {
              return 0;
            }
          });
          setUltimoEstratto(estrattiData[estrattiData.length - 1]);
        }

        setEstratti(estrattiData);


        const nonEstrattiResponse = await fetch("http://localhost:8000/api/giocatori/non-estratti");
        const nonEstrattiData = await nonEstrattiResponse.json();

        setNonEstratti(nonEstrattiData);

        const allenatoriResponse = await fetch("http://localhost:8000/api/allenatori");
        const allenatori = await allenatoriResponse.json();

        let assegnati = {};

        allenatori.forEach(allenatore => {


          assegnati[allenatore.Id] = estrattiData.filter(giocatore => {

            return giocatore.AllenatoreId === allenatore.Id
          });
        });

        setGAssegnati(assegnati);

      } catch (error) {
        console.error("Errore nel fetch dei giocatori: ", error);
      }
    };

    fetchData();
  }, []);

  // funzione per gestire l'input di aggiungi giocatore
  function gestisciInput(e) {
    const { name, value } = e.target;
    setNuovoGiocatore(prevValue => ({ ...prevValue, [name]: value }));
  }

  // funzione per aggiungere un giocatore già estratto ma non assegnato
  function aggiungiManualmente(nomeSquadra) {
    return function () {
      const { nome, ruolo } = nuovoGiocatore;

      if (nome && ruolo) {
        const giocatore = {
          Nome: nome,
          R: ruolo,
        };

        fetch("http://localhost:8000/api/giocatori", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(giocatore)
        })
          .then(response => response.json())
          .then(data => {
            setGAssegnati(prevAssegnati => {
              console.log('gassegnati')
              console.log({
                ...prevAssegnati,
                [nomeSquadra]: [...prevAssegnati[nomeSquadra] || [], data]
              })
                ({
                  ...prevAssegnati,
                  [nomeSquadra]: [...prevAssegnati[nomeSquadra] || [], data]
                })
            });
          })
          .catch(error => console.error("Ci no problemi con l'aggiunta del giocatore: ", error))

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
  function calcolaTotaleSpeso(nomeSquadra) {
    const giocatori = gAssegnati[nomeSquadra] || [];
    return giocatori.reduce((somma, giocatore) => somma + (parseInt(giocatore.prezzo) || 0), 0);
  }

  // Componente Allenatori
  const allenatori = allenatoriData.map(allenatore => {
    const idSquadra = allenatore.Id;
    //const giocatoriAssegnati = gAssegnati[idSquadra] || [];
    const totaleSpeso = calcolaTotaleSpeso(idSquadra);
    const pochiCreditiRimasti = totaleSpeso > 500 ? style["crediti-esauriti"] : totaleSpeso === 500 ? style["cinquecento"] : totaleSpeso >= 450 ? style["crediti-bassi"] : style["crediti"];
    const sforato = totaleSpeso > 500;

    return (
      <Allenatore
        key={allenatore.Id}
        allenatore={allenatore}
        style={style}
        idSquadra={idSquadra}
        sforato={sforato}
        giocatoriAssegnati={gAssegnati[idSquadra] || []}
        pochiCreditiRimasti={pochiCreditiRimasti}
        listaFinita={listaFinita}
        assegnaGiocatore={assegnaGiocatore}
        svincolaGiocatore={svincolaGiocatore}
        nuovoGiocatore={nuovoGiocatore}
        gestisciInput={gestisciInput}
        aggiungiManualmente={aggiungiManualmente}
        ultimoEstratto={ultimoEstratto}
      />
    )
  });

  // Funzione per l'estrazione
  function estrai() {

    if (nonEstratti.length > 0) {
      const indiceCasuale = Math.floor(Math.random() * nonEstratti.length);
      const giocatoreEstratto = nonEstratti.splice(indiceCasuale, 1)[0];
      setUltimoEstratto(giocatoreEstratto);

      fetch("http://localhost:8000/api/estrai", { // Salvare estratto nel db
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id_giocatore: giocatoreEstratto.Id
        })
      })
        .then(response => response.json())
        .then(data => {

          fetch("http://localhost:8000/api/giocatori/estratti")
            .then(response => response.json())
            .then(data => {
              setEstratti(data);
              console.log("Lista giocatori estratti fino ad ora: ", data);
            });
          fetch("http://localhost:8000/api/giocatori/non-estratti")
            .then(response => response.json())
            .then(data => {
              setNonEstratti(data);

            })
        })
        .catch(error => console.error("Ci sono problemi con l'estrazione: ", error));
      //}
    } else {
      console.log("Lista finita");
    }
  }

  // Funzione per assegnare l'ultimo estratto ad una squadra
  function assegnaGiocatore(allenatoreId, giocatoreId, puntata, totaleSpeso) {

    if(totaleSpeso >= 400) {
      alert('Hai finito i crediti');
      return;
    }
    //return function (event) {

      if (ultimoEstratto) {
        const giaAssegnato = Object.values(gAssegnati).some((giocatori) => giocatori.some((giocatore) => giocatore.Nome === ultimoEstratto.Nome));
        if (!giaAssegnato) {
          // const prezzoGiocatore = parseInt(event.target.value, 10);

          /*  setSquadre((prevSquadre) => prevSquadre.map((s) =>
             s.Id === allenatoreId ? { ...s, giocatori: [...s.giocatori, ultimoEstratto] } : s
           )); */


          /* setGAssegnati(prevAssegnati => {
            const nuovaSquadra = Array.isArray(prevAssegnati[allenatoreId]) ? prevAssegnati[allenatoreId] : [];
            return { ...prevAssegnati, [allenatoreId]: [...nuovaSquadra, ultimoEstratto] };
          }); */

          //setEstratti((prevEstratti) => prevEstratti.map((giocatore) => giocatore.Nome === ultimoEstratto.Nome ? { ...giocatore, assegnato: true } : giocatore));

          fetch("http://localhost:8000/api/acquista", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              id_giocatore: giocatoreId,
              id_allenatore: allenatoreId,
              prezzo: puntata
            })
          })
            .then(response => response.json())
            .then(data => {

              setSquadre((prevSquadre) => prevSquadre.map((s) =>
                s.Id === allenatoreId ? { ...s, giocatori: [...s.giocatori, ultimoEstratto] } : s
              ));
              setGAssegnati(prevAssegnati => {
                return ({
                  ...prevAssegnati,
                  [data.giocatore.AllenatoreId]: [...prevAssegnati[data.giocatore.AllenatoreId] || [], data.giocatore]
                });
              });
              setEstratti((prevEstratti) => prevEstratti.map((giocatore) => giocatore.Nome === ultimoEstratto.Nome ? { ...giocatore, assegnato: true } : giocatore));
            })
            .catch(error => console.error("Ci no problemi con l'aggiunta del giocatore: ", error))

          console.log(`Giocatore assegnato a ${allenatoreId}: `, ultimoEstratto);
          return;
        } else {
          alert(`Giocatore già assegnato!`);
          return;
        }
      } else {
        alert("Estrai un giocatore prima di assegnarlo.");
        return;
      }
    //};
  }

  function svincolaGiocatore(allenatoreId, giocatoreId) {
    /* alert('svincola giocatore ' + allenatoreId + ' attualmente dell\'allenatore ' + giocatoreId)
    return */
    fetch("http://localhost:8000/api/svincola", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        id_giocatore: giocatoreId,
        id_allenatore: allenatoreId
      })
    }).then(response => response.json())
    .then(data => {

      /* setSquadre((prevSquadre) => prevSquadre.map((s) =>
        s.Id === allenatoreId ? { ...s, giocatori: [...s.giocatori, ultimoEstratto] } : s
      )); */
      /* setGAssegnati(prevAssegnati => {
        return ({
          ...prevAssegnati,
          [data.giocatore.AllenatoreId]: [...prevAssegnati[data.giocatore.AllenatoreId] || [], data.giocatore]
        });
      }); */
      //setEstratti((prevEstratti) => prevEstratti.map((giocatore) => giocatore.Nome === ultimoEstratto.Nome ? { ...giocatore, assegnato: true } : giocatore));
    })
    .catch(error => console.error("Ci no problemi con l'aggiunta del giocatore: ", error))

  }

  // BACKUP DA ELIMINARE
  // Gestione della pressione di Enter
  /* function gestioneInvio(e, giocatore, allenatore) {
    if ((e.type === "keypress" && e.key === "Enter") || e.type === "blur") {
      if (!e.target.value) {
        e.target.value = e.target.placeholder;
      }
      giocatore.prezzo = e.target.value;
      e.target.disabled = true;
      const spanE = document.createElement("span");
      spanE.textContent = giocatore.prezzo;
      e.target.parentNode.replaceChild(spanE, e.target);

      fetch("http://localhost:8000/api/acquista", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id_giocatore: giocatore.Id,
          id_allenatore: allenatore.Id,
          prezzo: 12
        })
      })
        .then(response => response.json())
        .then(data => {
          setGAssegnati(prevAssegnati => ({
            ...prevAssegnati,
            [data.AllenatoreId]: [...prevAssegnati[data.AllenatoreId] || [], data.giocatore]
          }));
        })
        .catch(error => console.error("Ci no problemi con l'aggiunta del giocatore: ", error))
    }
  } */

  // funzione per mostrare tutti i giocatori estratti
  function toggleEstratti() {
    setEstrattiVisibile(!estrattiVisibile);
    console.log("Inutile:", squadre);
  }



  return (
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
      {listaFinita && <div><h1 className={style["lista-finita"]}>LISTA FINITA</h1><br /><p className={style["commento"]}>😵...era ora...Dio Porco!😩</p></div>}
      <div className={style["allenatori-container"]}>
        {ultimoEstratto && allenatori}
      </div>
      {estrattiVisibile && (
        <div className={style["lista-intera"]}>
          <h3 className={style["h3-lista-intera"]}>Giocatori Estratti:</h3>
          {estratti.map((giocatore, index) => (
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