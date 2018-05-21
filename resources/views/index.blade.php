@extends('layouts.base')
@section('title', 'Home')
@section('page-title', 'Home')
@section('content')
<div class="col-6 mx-auto mb-4">
  @if(!Auth::check())
    <p class="mb-3 text-center"><a href="{{ url('/login/amazon') }}" class="btn btn-primary" target="_blank"  >Amazonでログイン</a></p>
  @else
     <p class="mb-3 text-center"><a href="{{ url('/logout') }}" class="btn btn-primary" target="_blank"  >ログアウト</a></p>
  @endif
  <h2>タイトルを入力して下さい</h2>
  <form method="get" action="{{ action('ResultController@index') }}">
  <div class="form-group">
    <label for="book-title">書籍のタイトル</label>
    @if (count($errors) > 0)
      <div class="alert alert-warning">
        {{ $errors->first('t') }}
      </div>
    @endif
    <input type="text" class="form-control mb-3" id="book-title" name="t" data-required-error=”” required>
     <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>
@if(count($user_histories))
  <div class="col-6 mx-auto mb-4">
    <h3>あなたの履歴</h3>
    <ul class="list-group">
    @foreach($user_histories as $h)
      @php
        $encoded = urlencode($h->word);
      @endphp
      <li class="list-group-item"><a class="d-block p-auto" href="/result?t={{ $encoded }}">{{ $h->word }}</a>
      <form method="post" action="/delete/{{ $h->id }}">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <button type="submit" class="delete button button-rounded button-raised button-caution">×</button>
      </form>
      </li>
    @endforeach
  </ul>
</div>
@endif
@if(count($histories))
<div class="col-6 mx-auto">
  <h3>みんなの履歴</h3>
  @php
    //dd($history)
  @endphp
  <ul class="list-group">
    @foreach($histories as $h)
      @php
        $encoded = urlencode($h->word);
      @endphp
      <li class="list-group-item"><a class="d-block p-auto" href="/result?t={{ $encoded }}">{{ $h->word }}</a></li>
    @endforeach
  </ul>
</div>
@endif
</div>
@endsection