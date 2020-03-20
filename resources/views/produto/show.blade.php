@extends('layouts.app')
@section('title', $produto->titulo)
@section('content')
    <h1>{{ $produto->titulo }}</h1>
    <div class="row">
        <div class="col-md-6 col-md-3">
            <ul>
                <li>Referência: {{ $produto->referencia }}</li>
                <li>Preço: {{$produto->preco}}</li>
            <li>Adicionado em: {{$produto->created_at}}</li>
    </ul>
        </div>
    </div>
    <p>{{$produto->descricao}}</p>

    @if(file_exists('./img/produtos/'.md5($produto->id).'.jpg'))
        <div class="col-md-6 col-md-3">
            {{Html::image(asset('img/produtos/'.md5($produto->id).'.jpg'))}}
        </div>
    @endif
    <a href="javascript:history.go(-1)">Voltar</a>
@endsection
