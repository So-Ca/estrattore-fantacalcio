## Per avviare il server in locale:<br /><br />
Lanciare, all'interno di questa directory, il comando <br />
php artisan serve<br /><br />

## Gli url delle API<br />

### Le risorse accessibili con metodo GET prevedono i parametri all'interno del percorso dell'URL. Di seguito, i parametri (dinamici) sono indicati tra parentesi quadre
GET api/giocatori/[tipo]<br />
Se [tipo] è omesso, lista tutti i giocatori. Se [tipo] è "estratti" lista tutti i giocatori con attributo "Estratto": true, se [tipo] è "non-estratti" lista tutti i giocatori senza attributo "Estratto"<br /><br />

GET api/giocatore/[id_giocatore]<br />
Mostra un singolo giocatore che il valore dell'attributo "Id" uguale a [id_giocatore].<br /><br />

GET api/allenatori<br />
Lista tutti gli allenatori<br /><br />

GET api/allenatore/[id_allenatore]<br />
Mostra un singolo allenatore inserendo nel json anche i crediti spesi e un array contenente tutti i giocatori acquistati<br /><br />


### Le risorse accessibili con metodo POST prevedono all'interno del body della richiesta. Di seguito, i parametri (dinamici) sono indicati tra parentesi quadre
POST api/estrai<br />
{<br />
    id_giocatore : [id_giocatore]<br />
}<br />
Imposta l'attributo "Estratto": true al giocatore con id uguale a [id_giocatore]<br /><br />

POST api/riponi<br />
{<br />
    id_giocatore : [id_giocatore]<br />
}<br />
Rimuove l'attributo "Estratto" al giocatore con id uguale a [id_giocatore]<br /><br />

POST api/acquista<br />
{<br />
    id_allenatore: [id_allenatore],<br />
    id_giocatore : [id_giocatore],<br />
    prezzo: [prezzo]<br />
}<br />
Assegna il giocatore con id uguale a [id_giocatore] all'allenatore con id uguale a [id_allenatore]<br />[id_allenatore] al prezzo di acquisto [prezzo]<br /><br />

POST api/svincola<br />
{<br />
    id_giocatore : [id_giocatore]<br />
}<br />
Rimuove gli attributi "AllenatoreId" e "Prezzo" al giocatore con id uguale a [id_giocatore]