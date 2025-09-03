import React from 'react';
import { Helmet } from 'react-helmet-async';
import { useState } from "react";
import data from "../../json/regolamento.json";
import style from "./regolamento.module.scss";

function Regolamento() {

  const [sezioni, setSezioni] = useState(data.documento.sezioni);
  return (
    <div className={style["body"]}>
      <Helmet>
        <title>Fantafavaro | Regolamento</title>
      </Helmet>

      <h1 className={style["h1"]}>{data.documento.titolo}</h1>

      {sezioni.map((sezione) => (
        <div className={style["content-box"]} key={sezione.id}>
          <h2 className={style["h2"]}>{sezione.titolo}</h2>

          {/*CONTENUTO */}
          {sezione.contenuto?.map((text, i) => (
            <p className={style["p"]} key={`c-${i}`}>{i+1}.{text}</p>
          ))}

          {/*SOTTOSEZIONI*/}
          {sezione.sottosezioni?.map((sub, i) => (
            <div className={style["section-box"]} key={sub.id || i}>
              <h3 className={style["h3"]}>{sub.titolo}</h3>
              {sub.contenuto?.map((text, j) => (
                <p className={style["p"]} key={j}>- {text}</p>
              ))}
            </div>
          ))}

          {/*NOTE*/}
          {sezione.note?.map((note, i) => (
            <p className={style["p"]} key={`n-${i}`}>{note}</p>
          ))}

          {/*LISTE*/}
          <ul>
            {sezione.lista?.map((item, i) => (
              <li key={`l-${i}`}>{item}</li>
            ))}
          </ul>

          {/*PROPOSTE*/}
          {sezione.proposte?.map((proposta) => (
            <div className={style["proposal-box"]} key={proposta.id}>
              <div className={style["proposal-header"]}>
                <div className={style["proposal-text-box"]}>
                  <input
                    className={style["checkbox"]}
                    type="checkbox"
                    checked={proposta.approvata}
                    onChange={(e) => {
                      setSezioni(prev =>
                        prev.map(sec => {
                          if (sec.id !== sezione.id) return sec;
                          return {
                            ...sec,
                            proposte: sec.proposte.map(p => p.id === proposta.id ? {
                              ...p, approvata: e.target.checked
                              } : p
                            )
                          };
                        })
                      );
                    }}
                  />
                  <h3 className={style["h3"]}>{proposta.titolo}</h3>
                </div>
                <p className={style["proponents"]}>
                  Proponenti: {proposta.proponente.join(", ")}
                </p>
              </div>
              <textarea
                className={style["proposal-comment"]}
                placeholder="Scrivi pure qui le tue puttanate, tanto non verranno approvate..."
                value={proposta.commenti}
                onChange={(e) => {
                  setSezioni(prev =>
                    prev.map(sec => {
                      if (sec.id !== sezione.id) return sec;
                      return {
                        ...sec,
                        proposte: sec.proposte.map(p =>
                          p.id === proposta.id
                            ? {
                                ...p,
                                commenti: e.target.value
                              }
                            : p
                        )
                      };
                    })
                  );
                }}
              />
            </div>
          ))}

          {/*TABELLE*/}
          {sezione.tabelle?.map((tabella, i) => (
            <div className={style["tab-box"]} key={`t-${i}`}>
              <h3 className={style["h3"]}>{tabella.titolo}</h3>

              <table>
                <thead>
                  <tr>
                    {tabella.intestazioni.map((h, i) => (
                      <th className={style["cell-head"]} key={i}>{h}</th>
                    ))}
                  </tr>
                </thead>

                <tbody>
                  {tabella.righe.map((riga, i) => (
                    <tr className={style["table-row"]} key={i}>
                      {riga.map((cell, cidx) => (
                        <td className={style["table-data"]} key={cidx}>{cell}</td>
                      ))}
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          ))}

          {/*PREMI*/}
          {data.documento.premi?.tabelle?.map((tabella, i) => (
            <div className={style["tab-box"]} key={i}>
              <h3 className={style["h3"]}>{tabella.titolo}</h3>

              <table>
                <thead>
                  <tr>
                    {tabella.intestazioni.map((h,j) => (
                      <th className={style["cell-head"]} key={j}>{h}</th>
                    ))}
                  </tr>
                </thead>

                <tbody>
                  {tabella.righe.map((riga, i) => (
                    <tr className={style["table-row"]} key={i}>
                      {riga.map((cell, k) => (
                        <td className={style["table-data"]} key={k}>{cell}</td>
                      ))}
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          ))}

        </div>
      ))}
    </div>
  );
}

export default Regolamento