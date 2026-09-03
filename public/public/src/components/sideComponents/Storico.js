import React from 'react';
import { useState } from 'react';
import { Helmet } from 'react-helmet-async';
import storico from "../../json/storico.json";
import style from "./storico.module.scss";

function Storico() {

  const leagueHistory = storico.competitions.league;
  const relegations = storico.relegations;
  const palmares = {};

  return (
    <div>

      <Helmet><title>Fantafavaro | Storico</title></Helmet>

        {Object.entries(leagueHistory).map(([season, data]) => (
          <div key={season}>
            <h2>{season}</h2>
            <p> {data.first_place.team_name} - {data.first_place.manager_name}</p>
            <p> {data.second_place.team_name} - {data.second_place.manager_name}</p>
            <p> {data.third_place.team_name} - {data.third_place.manager_name}</p>
          </div>
        ))}

        <div>
          <h2>Palmares</h2>
          {Object.entries(palmares).map(([manager, stats]) => (
            <div key={manager}>
              <h3>{manager}</h3>
              <p>Campionati Vinti: {stats.league.first_places}</p>
              <p>Secondi Posti: {stats.league.second_places}</p>
              <p>Terzi Posti: {stats.league.third_places}</p>
              <p>Coppe Vinte: {stats.cup.wins}</p>
              <p>Retrocessioni: {stats.relegations}</p>
            </div>
          ))}
        </div>

      <p>
      Beh che c'è?
      </p>
      <p>
      Volevi vedere se c'era il tuo nome tra i campioni di sempre?
      </p>
      <p>
      Non c'è!! ..e come mai?... ..Forse perchè sei scarso e non vinci mai un cazzo.
      </p>
      <p>
      ..e comunque non c'è il nome di nessuno perchè devo ancora scrivere i file .json, .jsx, fare il .css, incorporarli, renderizzarli, collegarli.. e tutto un lavoraccio madonn!!!
      </p>
      <p>
         ..e tu cosa hai fatto nel mentre eh?<br/>
         Hai fatto cagare insieme alla tua squadra!<br/>
         Bravo complimenti, tu si che sei indispensabile.
      </p>
      <p>
      Torna a leggerti le regole al posto di intasare il gruppo "Amministrazione" a gennaio chiedendo se si usa il mantra o il classic.
      </p>
      <p>
      | ..se sei Daniele, fai schifo al fantacalcio hai solo culo, stai muto, maledetta puttanella di Dio!<br/>
       Signor "vinco 66 a 65.5" per eccellenza...e parli pure! |
      </p>
      <p>
        Se sei Sonny, cazzo apri sta pagina tanto lo sai cosa c'è scritto. Ti piace ricevere insulti? - Cialtrone Piangina -
      </p>
    </div>
  )
}

export default Storico