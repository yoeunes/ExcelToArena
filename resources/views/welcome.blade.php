<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ONDA</title>
    <link rel="stylesheet" href="{{ asset('css/onda.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @toastr_css
</head>
<body>
    <div class="container onda-container full-height">
        <div class="row onda-header">
            <div class="logo-onda col-xs-4">
                <img src="{{ asset('images/logo-onda.png') }}" style="width: 250px;height: auto;">
            </div>
            <div class="infos col-xs-4">
                <ul class="list-group infos-ul">
                    <li class="list-group-item infos-li">
                        <span class="infos-keys">Prénom et Nom </span> <span class="infos-column">:</span>
                        <span class="infos-values">MOURAD SALMA</span>
                    </li>
                    <li class="list-group-item infos-li">
                        <span class="infos-keys">Ecole </span> <span class="infos-column">:</span>
                        <span class="infos-values">ENSEM</span>
                    </li>
                    <li class="list-group-item infos-li">
                        <span class="infos-keys">Theme </span> <span class="infos-column">:</span>
                        <span class="infos-values">Gestion des vols</span>
                    </li>
                </ul>
            </div>
            <div class="logo-ensem col-xs-4">
                <img src="{{ asset('images/logo-ensem.png') }}" style="width: 164px;height: 136px;">
            </div>
        </div>
        <div class="row onda-content">
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('converter.store') }}" method="post" enctype="multipart/form-data">
                
                {{ csrf_field() }}
                
                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-12">
                        <h4>Convertisseur</h4>
                        <div class="input-group">
                            <label class="input-group-btn">
                                <span class="btn btn-primary">
                                    Selectionner <input type="file" name="fichier_excel" style="display: none;">
                                </span>
                            </label>
                            <input type="text" class="form-control" readonly>
                        </div>
                        <span class="help-block">
                            Selectionner un fichier excel de votre ordinateur
                        </span>
                    </div>
    
                    <div class="col-lg-4 col-sm-4 col-12">
                        <h4>Selectionner le type</h4>
                        <div class="input-group">
                            <label><input style="margin-right: 10px;" type="radio" name="type" value="programme_vols">Convertisseur programme des vols</label>
                            <label><input style="margin-right: 10px;" type="radio" name="type" value="affectation_comptoirs">Convertisseur affectation des comptoirs</label>
                        </div>
                    </div>
    
                    <div class="col-lg-2 col-sm-4 col-12">
                        <button class="btn btn-info btn-convertir">Convertir</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row onda-footer">
            <div class="col-xs-4" style="flex: 1;text-align: center;">
                Realisé par <strong>MOURAD SALMA</strong>
            </div>
            <div class="col-xs-4" style="flex: 1;text-align: center;">
                Sous l'encadrement de <strong>HARTI ZAKARYA</strong>
            </div>
        </div>
    </div>
</body>
@toastr_js
@toastr_render
<script src="{{ asset('js/onda.js') }}"></script>
</html>
