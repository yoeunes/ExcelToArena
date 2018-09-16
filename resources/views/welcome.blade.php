<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ONDA</title>
    <link rel="stylesheet" href="{{ asset('css/onda.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
                        <span class="infos-values">Salma Mourad</span>
                    </li>
                    <li class="list-group-item infos-li">
                        <span class="infos-keys">Ecole </span> <span class="infos-column">:</span>
                        <span class="infos-values">ENSEM</span>
                    </li>
                    <li class="list-group-item infos-li">
                        <span class="infos-keys">Theme </span> <span class="infos-column">:</span>
                        <span class="infos-values">Gestion de ******</span>
                    </li>
                </ul>
            </div>
            <div class="logo-ensem col-xs-4">
                <img src="{{ asset('images/logo-ensem.png') }}" style="width: 164px;height: 136px;">
            </div>
        </div>
        <div class="row onda-content">
            <div class="col-lg-6 col-sm-6 col-12">
                <h4>Convertisseur Excel</h4>
                <div class="input-group">
                    <label class="input-group-btn">
                    <span class="btn btn-primary">
                        Selectionner <input type="file" style="display: none;">
                    </span>
                    </label>
                    <input type="text" class="form-control" readonly>
                </div>
                <span class="help-block">
                    Selectionner un fichier excel de votre ordinateur
                </span>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('js/onda.js') }}"></script>
</html>
