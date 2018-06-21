<div> <!-- Questo div vuoto serve per raggruppare assieme la colonna centrale e il bottone per tornare in cima -->
    <div class="container w-100">
        <div id="replaceableContent"> {{-- TODO intervenire su questo div per realizzare la single-page-application --}}
            <div id="middleColHeader" class="mb-3">
                {{-- Inserire qui gli elementi da visualizzare in testa alla colonna centrale, prima del blocco con
                le tracce audio. --}}
                @yield('middlecol_header')
            </div>
            <div id="allAudioElements">
                {{-- Insieme di tracce audio. --}}
                @include('tricol.elements.allaudioelements')
            </div>
        </div>
        <div class="mx-auto w-100 text-center">
            {{-- Bottone invisibile avente le stesse dimensioni del bottone "torna su" effettivamente visibile,
            utilizzato per far sì che il bottone visibile non vada a finire sopra ad un player audio. --}}
            <button class="btn btn-primary mb-2 invisible">Top button placeholder</button>
            {{-- Elemento invisibile all'utente utilizzato per cambiare traccia quando si clicca su una barra che
            non sia quella della traccia attualmente in riproduzione. --}}
            <span class="d-none amplitude-skip-to" amplitude-song-index="1" amplitude-location="1" id="skipper"></span>
        </div>
    </div>
    <div class="mx-auto w-100 fixed-bottom text-center" id="backBtnContainer">
        <button class="btn btn-primary mb-2 invisible" id="backBtn" title="Torna in cima alla pagina">Torna su</button>
    </div>
</div>