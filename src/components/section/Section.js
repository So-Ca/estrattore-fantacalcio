import React, { useState, useEffect } from "react";
import allenatoriData from "../../json/allenatori.json";
import style from "./section.module.scss";
import Allenatore from "../sideComponents/Allenatore";
import GiocatoreEstratto from "../sideComponents/GiocatoreEstratto";

const Section = () => {

// Configurazioni globali
  const creditiPerAllenatore = 500;
  const apiHost = "https://cryptic-fjord-66661-3e659dd64751.herokuapp.com";

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
  }, [isDoingRequest]);

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
      alert("Se non scegli un allenatore alla quale assegnare il giocatore, dove cazzo credi che lo possa inserire?!!");
      return;
    }

    const giaAssegnato = Object.values(gAssegnati).some((giocatori) => giocatori.some((giocatore) => giocatore.Id === giocatoreId));

    if (!giaAssegnato) {
      if (totaleSpeso >= creditiPerAllenatore) {
        alert('😱 I tuoi cash sono finiti. Non puoi più comprare nessuno...Chiama Lapo magari ti può dare qualche idea.');
        return;
      } else if ((Number(totaleSpeso) + Number(puntata)) > creditiPerAllenatore) {
        alert('Wei poveraccio, ma che vuoi fare? Non hai abbastanza crediti per comprare questo giocatore. 💸 La prossima volta pensaci bene prima di riempirti la rosa di scarponi.');
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
      alert(`😡🤬Ehi Ehi Non barare infame!!😡🤬` + ultimoEstratto.Nome + ` è gia stato assegnato a ${Object.keys(gAssegnati).find(s => gAssegnati[s].some(g => g.Nome === ultimoEstratto.Nome))}`);
      return;
    }
  }

  function riponiGiocatore(giocatoreId, giocatoreNome) {
    if(window.confirm('Sei sicuro di voler rimettere nel listone ' + giocatoreNome + '?')) {
      setIsDoingRequest(true);
      fetch(apiHost + "/api/riponi", { // Salvare estratto nel db
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id_giocatore: giocatoreId
        })
      })
      .then(response => response.json())
      .then((data) => {
          setIsDoingRequest(false);
      });
    }
    
  }

// Funzione per svincolare un giocatore già assegnato ad una squadra
  function svincolaGiocatore(allenatoreId, giocatoreId) {
    let giocatoreCorrente = gAssegnati[allenatoreId].find((giocatore) => giocatore.Id === giocatoreId);
    let allenatoreCorrente = allenatoriData.find((allenatore) => allenatore.Id === allenatoreId);

    if (typeof giocatoreCorrente !== 'undefined' && typeof allenatoreCorrente !== 'undefined' && window.confirm('Hai fatto la cazzata eh!?!😒' + allenatoreCorrente.Nome + ' vuoi davvero svincolare quellla pippa di ' + giocatoreCorrente.Nome + '?')) {
      setIsDoingRequest(true);
      fetch(apiHost + "/api/svincola", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id_giocatore: giocatoreId,
          id_allenatore: allenatoreId
        })
      })
      .then(response => response.json())
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
  let acquistato = Object.values(gAssegnati).filter(allenatore => allenatore.filter(giocatore =>giocatore.Id == ultimoEstratto.Id).length).length > 0;

  return (
    <div className={style["section"]}>
      <div className={style["btn-section"]}>
        <button disabled={isDoingRequest} onClick={estrai} className={style["btn-estrai"]}>Estrai</button>
        <div className={style["box-contatori"]}>
          <div className={style["contatore-estratti"]}>{'Estratti: ' + estratti.length + ' / ' + (estratti.length + nonEstratti.length)}</div>
          <div className={style["contatore-acquistati"]}>{'Acquistati: ' + Object.values(gAssegnati).map((allenatore) => allenatore.length).reduce((total, num) => total + num) + ' / ' + estratti.length}</div>
        </div>
        <button onClick={toggleEstratti} className={style["btn-mostra-estratti"]}>{estrattiVisibile ? "Nascondi Lista Estratti" : "Mostra Lista Estratti"}</button>
      </div>

      <div className={style["ultimo-estratto"]}>
        <h3 className={style["h3-estratto"]}>Giocatore Estratto:</h3> {ultimoEstratto && (
          <>
          <p className={style["p-estratto"]}>
            <b>Nome:</b> {ultimoEstratto.Nome},&nbsp;
            <b>Squadra:</b> {ultimoEstratto.Squadra},&nbsp;
            <b>Prezzo:</b> {ultimoEstratto["Qt.A"]},&nbsp;
            <b>Ruolo:</b> {ultimoEstratto.R}
          </p>
          { !acquistato && /* !Object.values(gAssegnati).filter((giocatore) => giocatore.Id == ultimoEstratto.Id).length && */ 
          <button onClick={() => riponiGiocatore(ultimoEstratto.Id, ultimoEstratto.Nome)}>Rimetti nel listone</button>
        }
          </>
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
            <input className={style["input-ricerca"]} type="text" name="search" id="search" placeholder="Cerca il ciabattaro che non hai avuto il coraggio di comprare subito" onChange={(e) => setSearchText(e.target.value)} />
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
              riponiGiocatore={riponiGiocatore}
            />
          ))}
        </div>
      )}
    </div>
  )
}

export default Section