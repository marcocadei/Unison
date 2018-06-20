@extends('layouts.layout')

@section('title')
    Upload track
@endsection

@section('content')
    <!--<h1 class="display-3 text-center ">Pagina di login</h1>-->
    <div class="jumbotron jumbotron-fluid text-center bgLogin mt-5">
        <div class="container text-light">
            <h1 class="display-4 boldText">Fai sentire al mondo la tua musica</h1>
            <div class="d-none d-md-block">
                <p class="lead">Carica la tua track e diventa un'artista di successo</p>
            </div>
        </div>
    </div>
    <div class="container h-100 mb-5">
        <div class="row h-100 justify-content-center">
            <!-- Aggiungere al div anche la classe "align-items-center" se si vuole che l'immagine sia centrata
            anche rispetto al verticale -->
            <div class="col-sm-9 order-last col-md-6 order-md-first text-center">
                <img src="{{asset('images/upload.png')}}" alt="Non perdere altro tempo: iscriviti subito!" class="img-fluid mt-5 mt-md-0">
            </div>
            <div class="col-sm-12 col-md-6">
                <form class="p-5" action="/" method="post" id="upload">
                    {{ csrf_field() }}
                    <input type="hidden" class="form-control" id="formUpload">
                    <div class="invalid-feedback">
                        Parametri specificati incorretti
                    </div>

                    <div class="form-group mb-3">
                        <label for="inputGroupFile01">Canzone:</label>
                        <div class="custom-file">
                            <input type="file" accept=".mp3, .mp4" class="custom-file-input" id="inputGroupFile01">
                            <label class="custom-file-label" for="inputGroupFile01">Scegli file</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="passwordSI">Password:</label>
                        <input type="password" class="form-control" id="passwordSI" name="passwordSI" placeholder="Inserisci password...">
                        <div class="invalid-feedback">
                            Per favore specifica una password valida (lunghezza massima consentita 64 caratteri, solo caratteri ASCII stampabili).
                        </div>
                    </div>
                    <button type="submit" class="btn btn-block btn-primary mt-4" id="buttonSI">Accedi</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script_footer')
<script>
    $("#inputGroupFile01").on('change',function(){
        //get the file name
        let fileName = $(this).val();
        let lastSlash = fileName.lastIndexOf("\\");
        console.log(lastSlash);
        fileName = fileName.substring(lastSlash  + 1);
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    })
</script>
@endsection