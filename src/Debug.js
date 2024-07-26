// fetch con axios
import axios from "axios";

// creazione array vuoto dove mettere i dati
const [dati, setDati] = useState([]);

// istanza axios client
const client = axios.create({ baseURL: "https://URLBase" });

// prende dati dall'api
async function fetchData(){
  const response = await client.get("aggiuntaAllURLBase");
  const data = await response.data; // vedere formato json se è ".data" oppure non c'è niente
  setDati(data)
}

// richiamo il fetch
useEffect( () => {
    try{
      fetchData();
    }catch(error){
      console.log("C'è un problema:", error);
    }
}, []);