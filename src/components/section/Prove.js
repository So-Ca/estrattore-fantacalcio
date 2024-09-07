import React, { useState, useEffect } from "react";
import allenatoriData from "../../json/allenatori.json";
import style from "./section.module.scss";
import Allenatore from "./Allenatore";

import GiocatoreEstratto from "./GiocatoreEstratto";

const Section = () => {

  // Configurazioni globali
  const creditiPerAllenatore = 500;
  const apiHost = "http://localhost:8000";

  // Liste giocatori
  const [nonEstratti, setNonEstratti] = useState([]);
  const [estratti, setEstratti] = useState([]);

  // Ultimo giocatore estratto
  const [ultimoEstratto, setUltimoEstratto] = useState(null);

  // Flag che governa lo show/hide della lista dei giocatori estratti
  const [estrattiVisibile, setEstrattiVisibile] = useState(false);

  // Oggetto che contiene un array per ciascun allenatore.
  // Ogni array contiene i giocatori assegnati all'allenatore corrispondente
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

  const [isDoingRequest, setIsDoingRequest] = useState(false);
  /*  const [nuovoGiocatore, setNuovoGiocatore] = useState({
     numero: "",
     nome: "",
     ruolo: "",
     prezzo: ""
   }); */
  const [searchText, setSearchText] = useState("");

  const listaFinita = nonEstratti.length === 0;

  // Fetch dei giocatori estratti e nonEstratti al caricamento della pagina
  useEffect(() => {
    const fetchData = async () => {
      try {
        const estrattiResponse = await fetch(apiHost + "/api/giocatori/estratti");
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


        const nonEstrattiResponse = await fetch(apiHost + "/api/giocatori/non-estratti");
        const nonEstrattiData = await nonEstrattiResponse.json();

        setNonEstratti(nonEstrattiData);

        const allenatoriResponse = await fetch(apiHost + "/api/allenatori");
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
  //function gestisciInput(e) {
  // const { name, value } = e.target;
  //setNuovoGiocatore(prevValue => ({ ...prevValue, [name]: value }));
  //}

  // Calcolo dei crediti spesi
  function calcolaTotaleSpeso(idAllenatore) {
    const giocatori = gAssegnati[idAllenatore] || [];
    return giocatori.reduce((somma, giocatore) => somma + (parseInt(giocatore.Prezzo) || 0), 0);
  }

  // Componente Allenatori
  const allenatori = allenatoriData.map(allenatore => {
    const idSquadra = allenatore.Id;
    const totaleSpeso = calcolaTotaleSpeso(idSquadra);
    const pochiCreditiRimasti = totaleSpeso > creditiPerAllenatore ? style["crediti-esauriti"] : totaleSpeso === creditiPerAllenatore ? style["cinquecento"] : totaleSpeso >= (creditiPerAllenatore * 0.9) ? style["crediti-bassi"] : style["crediti"];
    const sforato = totaleSpeso > creditiPerAllenatore;

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
        //nuovoGiocatore={nuovoGiocatore}
        //gestisciInput={gestisciInput}
        ultimoEstratto={ultimoEstratto}
        totaleSpeso={totaleSpeso}
        isDoingRequest={isDoingRequest}
      />
    )
  });

  // Funzione per l'estrazione
  function estrai() {

    if (nonEstratti.length > 0) {
      const indiceCasuale = Math.floor(Math.random() * nonEstratti.length);
      const giocatoreEstratto = nonEstratti.splice(indiceCasuale, 1)[0];
      setUltimoEstratto(giocatoreEstratto);

      setIsDoingRequest(true);
      fetch(apiHost + "/api/estrai", { // Salvare estratto nel db
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id_giocatore: giocatoreEstratto.Id
        })
      })
        .then(response => response.json())
        .then(data => {

          fetch(apiHost + "/api/giocatori/estratti")
            .then(response => response.json())
            .then(data => {
              setEstratti(data);
              console.log("Lista giocatori estratti fino ad ora: ", data);
            });
          fetch(apiHost + "/api/giocatori/non-estratti")
            .then(response => response.json())
            .then(data => {
              setNonEstratti(data);
              setIsDoingRequest(false);
            })
        })
        .catch((error) => {
          console.error("Ci sono problemi con l'estrazione: ", error);
          setIsDoingRequest(false);
        });
    } else {
      console.log("Lista finita");
    }
  }

  // Funzione per assegnare l'ultimo estratto ad una squadra
  function assegnaGiocatore(allenatoreId, giocatoreId, puntata, totaleSpeso) {

    allenatoreId = Number(allenatoreId);
    giocatoreId = Number(giocatoreId);
    puntata = Number(puntata);


    if (Object.keys(gAssegnati).map((id) => Number(id)).indexOf(Number(allenatoreId)) === -1) {
      alert("Scegli un allenatore");
      return;
    }

    const giaAssegnato = Object.values(gAssegnati).some((giocatori) => giocatori.some((giocatore) => giocatore.Id === giocatoreId));

    if (!giaAssegnato) {
      if (totaleSpeso >= creditiPerAllenatore) {
        alert('Hai finito i crediti');
        return;
      } else if ((Number(totaleSpeso) + Number(puntata)) > creditiPerAllenatore) {
        alert('Non hai abbastanza crediti per fare questa puntata');
        return;
      }
      setIsDoingRequest(true);
      fetch(apiHost + "/api/acquista", {
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

          setGAssegnati(prevAssegnati => {
            return ({
              ...prevAssegnati,
              [data.giocatore.AllenatoreId]: [...prevAssegnati[data.giocatore.AllenatoreId] || [], data.giocatore]
            });
          });
          setIsDoingRequest(false);
        })
        .catch(error => {
          console.error("Ci no problemi con l'aggiunta del giocatore: ", error)
          setIsDoingRequest(false);
        })

      console.log(`Giocatore assegnato a ${allenatoreId}: `, ultimoEstratto);
      return;
    } else {
      alert(`Giocatore già assegnato!`);
      return;
    }

  }

  function svincolaGiocatore(allenatoreId, giocatoreId) {

    let giocatoreCorrente = gAssegnati[allenatoreId].find((giocatore) => giocatore.Id === giocatoreId);
    let allenatoreCorrente = allenatoriData.find((allenatore) => allenatore.Id === allenatoreId);

    if (typeof giocatoreCorrente !== 'undefined' && typeof allenatoreCorrente !== 'undefined' && window.confirm('Sei sicuro di voler svincolare il giocatore ' + giocatoreCorrente.Nome + ' della squadra ' + allenatoreCorrente.Squadra + '?')) {
      setIsDoingRequest(true);
      fetch(apiHost + "/api/svincola", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id_giocatore: giocatoreId,
          id_allenatore: allenatoreId
        })
      }).then(response => response.json())
        .then(data => {
          setGAssegnati(prevAssegnati => {
            return ({
              ...prevAssegnati,
              [allenatoreId]: prevAssegnati[allenatoreId].filter((giocatore) => giocatore.Id != giocatoreId)
            });
          });
          setIsDoingRequest(false);
        })
        .catch(error => {
          console.error("Ci no problemi con l'aggiunta del giocatore: ", error);
          setIsDoingRequest(false);
        })
    }

  }

  // funzione per mostrare tutti i giocatori estratti
  function toggleEstratti() {
    setEstrattiVisibile(!estrattiVisibile);
  }

  return (
    <div className={style["section"]}>
      <div className={style["btn-section"]}>
        <button disabled={isDoingRequest} onClick={estrai} className={style["btn-estrai"]}>Estrai</button>
        <div>
          <div>{'Estratti: ' + estratti.length + ' / ' + (estratti.length + nonEstratti.length)}</div>
          <div>{'Acquistati: ' + Object.values(gAssegnati).map((allenatore) => allenatore.length).reduce((total, num) => total + num) + ' / ' + estratti.length}</div>
        </div>
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
          <div>
            <input type="text" name="search" id="search" placeholder="Cerca giocatore" onChange={(e) => setSearchText(e.target.value)} />
          </div>
          {estratti.map((giocatore, index) => (

            (!searchText.length || searchText.toLowerCase() == giocatore.Nome.slice(0, searchText.length).toLowerCase()) &&
            <GiocatoreEstratto
              style={style}
              giocatore={giocatore}
              key={giocatore.Id}
              allenatori={allenatoriData}
              assegnaGiocatore={assegnaGiocatore}
              calcolaTotaleSpeso={calcolaTotaleSpeso}
              gAssegnati={gAssegnati}
              isDoingRequest={isDoingRequest}
            />
          ))}
        </div>
      )}
    </div>
  )
}

export default Section