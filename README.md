  <h1>Estrattore Fantafavaro</h1>

  <h2>Estrattore per Asta Fantacalcio</h2>
  
  <h3>Indicazioni</h3>

  <p>
    Questa applicazione funziona come estrattore per i giocatori del Listone del fantacalcio, basta scaricare il Listone come file "json" e sostituirlo alla vecchia lista.
  </p>

  <h3>Contenuti</h3>
  <ul>
  <li><a href="#structure">Struttura</a></li>
  <li><a href="#overview">Panoramica</a></li>
  <li><a href="#commands">Comandi e Modifiche</a></li>
  <li><a href="#responsive">Responsive</a></li>
  <li><a href="#contacts">Contatti</a></li>
  </ul>

  <a name="structure"></a>
  <h3>Struttura</h3>

  <p>
    L'applicazione è costruita con React, ed è strutturata in maniera classica: i file di sviluppo nella cartella "src", mentre i file di distribuzione sono nella cartella public.<br/>
  </p>

  <ul>Struttura Cartelle
    <li>estrattore-sonny: cartella sorgente</li>
    <li>src: cartella principale sviluppo</li>
    <li>assets: file accessori</li>
    <li>components: contiene i componenti in jsx/js ed i loro relativi file css/scss della applicazione</li>
    <li>header: render della sezione alta della pagina con intestazione e logo</li>
    <li>section: parte centrale della pagina comprendente sia la logica che il render</li>
    <li>footer: render della sezione bassa della pagina con copyright e/o link all'autore</li>
    <li>json: file json</li>
    <li>App: contiene il routing ed i render dei componenti</li>
    <li>index.js: render dell'intera applicazione tramite il componente App</li>
    <li>index.css: regole generali di css valide per tutti i componenti</li>
  </ul>

  <a name="overview"></a>
  <h3>Panoramica</h3>

  <h4>Interazioni</h4>

  <p>
    Una volta aperta la pagina, è possbile visualizzare 3 sezioni:<br/>
    la prima con due pulsanti "estrai" e "mostra lista estratti",<br/>
    la seconda con il giocatore estratto e le sue caratteristiche, <br/>
    la terza con una serie di riquadri rappresentanti le squadre degli allenatori ed i rispettivi giocatori acquistati se ce ne sono.<br/>
    È possibile far comparire una quarta sezione cliccando sul tasto "mostra lista estratti" che mostrerà a schermo tutti i giocatori che sono stati estratti, evidenziando in automatico quelli comprati dalle squadre.<br/>
    Cliccando su "estrai" si estrae un giocatore che è poi possibile assegnare ad una squadra cliccando su "assegna a...", si dovrà immettere il prezzo pagato all'interno dell'input che compare quando si clicca sul pulsante.<br/>
    È presente un contatore di crediti spesi sotto ogni nome di squadra partecipante, in maniera da tenere sotto controllo i crediti utilizzati.
    Dopo aver concluso la lista ed aver fatto gli acquisti, compariranno degli input ed un pulsante per assegnare altri giocatori non comprati. Semplicemente si scriva nome e ruolo negli input e poi si prema su "aggiungi" per dare il giocatore ad una squadra. Ricordarsi di inserire il prezzo del giocatore nell'input!<br/>
  </p>

  <h4>Errori</h4>

  <p>
    Se si cerca di assegnare un giocatore già comprato ad un altra squadra, comparirà un alert che metterà in guardia sul fatto che questa azione è proibita.<br/>
    Se si prova ad assegnare un giocatore senza estrarlo compare un alert che avverte che prima di assegnare un giocatore bisogna estrarlo.<br/>
    Quando ci si avvicina alla spesa massima impostata (500 crediti) il contatore si evidenzia di rosso. Se si supera il valore massimo di 500 compare un messaggio che avverte che si è sforato con i crediti<br/>
  </p>

  <a name="commands"></a>
  <h3>Comandi e Modifiche</h3>

  <p>
    npm start --> apre il server ed anche in browser con la pagina caricata<br/>
    Nel caso si vogliano modificare i nomi delle squadre o la lista dei giocatori, si vada nella cartella "json" e si modifichino i file relativi agli allenatori o ai giocatori.<br/>
    Per cambiare il limite di crediti o le enfasi sul limite crediti si vada nella cartella "section", nel file "Section.js", nella sezione del rendering del componente "allenatori" e si modifichino le variabili "pochiCreditiRimasti" e "sforato"<br/>
    Per modificare il logo ed il nome si vada nella cartella "header", nel componente "Header.js"<br/>
    Per modificare il copyright e/o i link all'autore si vada nella cartella "footer", nel componente "Footer.js".
  </p>

  <a name="responsive"></a>
  <h3>Responsive</h3>

  <p>
    L'app è completamente responsive grazie ai "Media Queries".<br/>
    I media queries sono scritti manualmente nei file scss. 
  </p>

  <a name="contacts"></a>
  <h3>Contatti</h3>

  <a href="https://fantafavaro.netlify.app/">Estrattore Fantafavaro</a><br>
  <a href="https://sonny-caputo-portfolio.netlify.app/">Portfolio</a><br>
  <a href="https://github.com/So-Ca">GitHub</a><br>
  <a href="https://www.linkedin.com/in/sonny-caputo-554315185">Likedin</a><br>
