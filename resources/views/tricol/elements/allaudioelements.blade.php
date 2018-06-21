@php($counter = 0)
{{-- Nota: È necessaria una variabile contatore per fare in modo che alcuni elementi della pagina associati ad una
traccia possano avere ID pari a [stringa]+[indice della traccia audio]. --}}
@foreach($songs as $song)
    @include('tricol.elements.singleaudioelement')
    @php($counter++)
@endforeach

{{--
TODO aggiungere qualcosa da visualizzare se non c'è nemmeno una traccia
(Nota importante: ricordarsi che questa pagina potrebbe essere richiamata anche da un "carica altro", in quel caso anche
se non viene caricato niente non bisogna visualizzare qualcosa di particolare (ma probabilmente conviene fare il controllo
direttamente nel controller, su offset(XX)->exists() prima di caricare i dati?) )
--}}