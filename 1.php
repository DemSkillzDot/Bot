<?php
    //Questo serve per prendere i dati del server, specialmente i giocatori connessi
    //Il file bootstrap è obbligatorio
    require __DIR__ . '/SourceQuery/bootstrap.php';

    //Usa questa liberia
    use xPaw\SourceQuery\SourceQuery;
    //Salvo il path del bot
    $path = "https://api.telegram.org/bot1196273675:AAHThzQXtZKXXDIHQFLSKJCq3BtAqSffmnI";
    //Prendo i dati del messaggio inviato e li salvo in $update, decodificando il json e trasformandolo in un oggetto
    $update = json_decode(file_get_contents("php://input"), TRUE);

    //Prendo l'id della chat dall'array
    $chatId = $update["message"]["chat"]["id"];
    //Prendo il nome del gruppo dall'array
    $nomeGruppo = $update["message"]["chat"]["username"];
    //Prendo il nome di chi ha inviato il messaggio dall'array
    $nome = $update["message"]["chat"]["first_name"];
    //Prendo l'username di chi ha inviato il messaggio dall'array
    $username = $update["message"]["chat"]["username"];
    //Prendo il testo del messaggio dall'array
    $message = $update["message"]["text"];
    //Salvo il path per prendere la chat (serve per il messaggio pinnato)
    $pathChat = $path."/getChat?chat_id=@".$nomeGruppo;
    //Prendo i dati della chat e li salvo in $chat, decodificando il json e trasformandolo in un oggetto
    $chat = json_decode(file_get_contents($pathChat), TRUE);
    //Prendo il testo del messaggio pinnato
    $pinned = $chat["result"]["pinned_message"]["text"];
    
    //Prendo i contenuti della pagina con alcuni dati del server (è un JSON)
    $page = file_get_contents('http://gmod-servers.com/api/?object=servers&element=detail&key=ztuDAmH6riHNJueqGgR85JSFUoqbte5mk');
    //Li trasformo in un array
    $array = json_decode($page, true);
    
    //Se l'utente ha scritto /giocatori all'inizio del messaggio
    if (strpos($message, "/giocatori") === 0) {
        //Salvo in $info quello che restituisce getInfo(un'array)
        $info = getInfo();

        //Mando un messaggio, id $chatId, nel text i giocatori nel server
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Ora ci sono ".$info["Players"]." giocatori nel server.");
    }

    //Se l'utente ha scritto /ip all'inizio del messaggio
    if (strpos($message, "/ip") === 0) {
        //Mando un messaggio, id $chatId, nel text l'ip del server
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Indirizzo IP: ".$array["address"].":".$array["port"]);
    }

    //Se l'utente ha scritto /mappa all'inizio del messaggio
    if (strpos($message, "/mappa") === 0) {
        //Salvo in $info quello che restituisce getInfo(un'array)
        $info = getInfo();

        //Mando un messaggio, id $chatId, nel text la mappa del server
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Stiamo giocando su: ".$info["Map"]);
    }

    //Se l'utente ha scritto /online all'inizio del messaggio
    if (strpos($message, "/online") === 0) {
        //Se il server è online [ovvero se is_online nell'array $array(dati di gmodservers) è settato a 1]
        if ($array["is_online"] == 1) {
            //Mando un messaggio, id $chatId, nel text il server è online
            file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Il server è online!");
        }
        else {
            //Mando un messaggio, id $chatId, nel text il server è offline
            file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Il server è offline!");
        }
    }
    
    //Se l'utente ha scritto /slot all'inizio del messaggio
    if (strpos($message, "/slot") === 0) {
        //Salvo in $info quello che restituisce getInfo(un'array)
        $info = getInfo();

        //Mando un messaggio, id $chatId, nel text il numero massimo di player che possono entrare
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Possono entrare al massimo ".$info["MaxPlayers"]." giocatori nel server.");
    }

    //Se l'utente ha scritto /info all'inizio del messaggio
    if (strpos($message, "/info") === 0) {
        //Salvo in $info quello che restituisce getInfo(un'array)
        $info = getInfo();
        //Salvo in $players quello che restituisce getPlayers(un'array)
        $players = getPlayers();

        //Se il server è online [ovvero se is_online nell'array $array(dati di gmodservers) è settato a 1]
        if ($array["is_online"] == 1) {
            //Mando un messaggio, id $chatId, nel text tutte le informazioni del server
            file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Indirizzo IP ".$array["address"].":".$info["GamePort"]."%0AMappa: ".$info["Map"]."%0AGiocatori: ".$info["Players"]."/".$info["MaxPlayers"]."%0A".$players);
        }
        else {
            //Mando un messaggio, id $chatId, nel text il server è offline
            file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Il server è offline!");
        }
        
    }

    //Se l'utente ha scritto /descrizione all'inizio del messaggio
    if (strpos($message, "/descrizione") === 0) {
        //Mando un messaggio, id $chatId, nel text la descrizione del bot
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Questo è il bot del server di TTT2 \"Trouble in Platynum Town\", in cui potrete trovare alcune informazioni utili sul server senza entrarci, come il numero di giocatori, la mappa, l'indirizzo, gli slot e sapere se è online o no.");
    }
    
    //Se l'utente ha scritto /steam all'inizio del messaggio
    if (strpos($message, "/steam") === 0) {
        //Mando un messaggio, id $chatId, nel text il link del gruppo Steam
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Gruppo Steam: https://steamcommunity.com/groups/platynumtown");
    }

    //Se l'utente ha scritto /discord all'inizio del messaggio
    if (strpos($message, "/discord") === 0) {
        //Mando un messaggio, id $chatId, nel text il link del discord
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Discord: https://discord.com/invite/57Skf8D");
    }

    //Se l'utente ha scritto /sito all'inizio del messaggio
    if (strpos($message, "/sito") === 0) {
        //Mando un messaggio, id $chatId, nel text il link del sito
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Sito web: https://www.troubleinplatynumtown.it/");
    }

    //Se l'utente ha scritto /pin all'inizio del messaggio
    if (strpos($message, "/pin") === 0) {
        //Mando un messaggio, id $chatId, nel text il messaggio pinnato (FUNZIONA SOLO NEL GRUPPO PRINCIPALE)
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=".$pinned);
    }

    //Se l'utente ha scritto /lista all'inizio del messaggio
    if (strpos($message, "/lista") === 0) {
        //Salvo in $players quello che restituisce getPlayers(un'array)
        $messaggio = getPlayers();

        //Mando un messaggio, id $chatId, nel text tutti i player connessi
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=".$messaggio);
    }

    //Se l'utente ha scritto /contatta all'inizio del messaggio
    if (strpos($message, "/contatta") === 0) {
        //DA CAMBIARE IL CHAT ID MIO CON QUELLO DI PLATYNUM

        //Mando un messaggio, id $chatId, nel text un messaggio che avvisa Platynum che un utente ha bisogno di aiuto
        file_get_contents($path."/sendmessage?chat_id=106684754&text=@".$username." ha bisogno di aiuto, vuole contattarti.");
    }


    //             EASTER EGG



    //Se l'utente ha scritto /evvai, /Evvai o /EVVAI all'inizio del messaggio
    if (strpos($message, "/evvai") === 0 || strpos($message, "/Evvai") === 0 || strpos($message, "/EVVAI") === 0) {
        //Mando un messaggio, id $chatId, nel text Evvai
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Evvai");
    }
    
    //Se l'utente ha scritto /godo all'inizio del messaggio
    if (strpos($message, "/godo") === 0) {
        //Mando un messaggio, id $chatId, nel text Göööööödo
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Göööööödo");
    }

    //Se l'utente ha scritto /paolinomorto all'inizio del messaggio
    if (strpos($message, "/paolinomorto") === 0) {
        //Mando un messaggio, id $chatId, nel text EVVAI!
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=EVVAI!");
    }

    //Se l'utente ha scritto /sesso all'inizio del messaggio
    if (strpos($message, "/sesso") === 0) {
        //Mando un messaggio, id $chatId, nel text il nome dell'utente e poi dammi 50€ e ne parliamo.
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=".$nome.", dammi 50€ e ne parliamo.");
    }

    //Se l'utente ha scritto /dio all'inizio del messaggio
    if (strpos($message, "/dio") === 0) {
        //MANDARE LA FOTO NON FUNZIONA, PER ORA MANDA SOLAMENTE UN MESSAGGIO

        //Mando un messaggio, id $chatId, nel text Sono io, PinoMartirio
        //file_get_contents($path."/sendPhoto?chat_id=".$chatId."&photo=../img/PinoMartirio.png&caption=Eccomi.");
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Sono io, PinoMartirio.");
    }

    //Se l'utente ha scritto /hero o /Hero05IT4 all'inizio del messaggio
    if (strpos($message, "/hero") === 0 || strpos($message, "/Hero05IT4") === 0) {
        //Mando un messaggio, id $chatId, nel text Porcoddio.
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Porcoddio.");
    }

    //Se l'utente ha scritto /snake o /SnakeEater32 all'inizio del messaggio
    if (strpos($message, "/snake") === 0 || strpos($message, "/SnakeEater32") === 0) {
        //Mando un messaggio, id $chatId, nel text Forse volevi cercare NoMercyGuy
        file_get_contents($path."/sendmessage?chat_id=".$chatId."&text=Forse volevi cercare NoMercyGuy");
    }
	
	//Se l'utente ha scritto /Viva la figa all'inizio del messaggio
    if (strpos($message, "/VivaLaFiga") === 0 || strpos($message, "/vivalafiga") === 0) {
        //Mando un messaggio, id $chatId, nel text Forse volevi cercare NoMercyGuy
        file_get_contents($path."/sendVoice?chat_id=".$chatId."&audio=https://drive.google.com/file/d/1YodB3Evl2IbBtOeyXTS_sXAoUonhjJ90/view?usp=sharing");
    }
  

    //            FUNZIONI
    function getPlayers() {
        //Cose necessarie per queta funzione
        Header( 'Content-Type: text/plain' );
        Header( 'X-Content-Type-Options: nosniff' );

        //Definisco alcune costanti
        define( 'SQ_SERVER_ADDR', '195.201.199.40' );
        define( 'SQ_SERVER_PORT', 27245 );
        define( 'SQ_TIMEOUT',     1 );
        define( 'SQ_ENGINE',      SourceQuery::SOURCE );
        
        //Crea un oggetto della classe SourceQuery (Per fare le query sui server che usano il Source Engine)
        $Query = new SourceQuery();

        //Mi connetto al server usando le costanti dichiarate prima
        $Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
        
        //Salvo in $players (array) tutti i giocatori che trova
        $players = $Query->GetPlayers();

        //Inizializzo $mess a 0
        $mess = "";
        //Se non ci sono elementi nell'array (nessun player connesso)
        if (count($players) == 0) {
            //In $mess salvo Nessun player connesso
            $mess = "Nessun player connesso";
        }
        else {
            //Per ogni $player che sta in $players
            foreach ($players as $player) {
                //Se il nome ($player["Name"]) non è vuoto
                if ($player["Name"] != "") {
                    //Concateno a $mess il nome ($player["Name"]) e il tempo giocato in minuti ($player["TimeF"]), con uno spazio (%0A)
                    $mess = $mess.$player["Name"]. " ".$player["TimeF"]. " min%0A";
                }
                else {
                    //Concateno a $mess In caricamento (non so il nome) e il tempo giocato in minuti ($player["TimeF"]), con uno spazio (%0A)
                    $mess = $mess."In caricamento ".$player["TimeF"]. " min%0A";
                }
                
            }
        }

        //Ritorno $mess (array)
        return $mess;
    }

    function getInfo() {
        //Cose necessarie per queta funzione
        Header( 'Content-Type: text/plain' );
        Header( 'X-Content-Type-Options: nosniff' );

        //Definisco alcune costanti
        define( 'SQ_SERVER_ADDR', '195.201.199.40' );
        define( 'SQ_SERVER_PORT', 27245 );
        define( 'SQ_TIMEOUT',     1 );
        define( 'SQ_ENGINE',      SourceQuery::SOURCE );
        
        //Crea un oggetto della classe SourceQuery (Per fare le query sui server che usano il Source Engine)
        $Query = new SourceQuery();

        //Mi connetto al server usando le costanti dichiarate prima
        $Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
        
        //Salvo in $info (array) tutte le informazioni del server
        $info = $Query->GetInfo();

        //Ritorno $info (array)
        return $info;
    }
?>