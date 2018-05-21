<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use AmazonProduct;
use ApaiIO\Operations\Search;
use App\History;
use App\User;
use Auth;

class ResultController extends Controller
{ 
  /***
  * amazon api でたまに503が返ってくるのでリトライ用の関数
  */
  private function retry_api($arg, $page = 1, $retry_count=5, $retry_wait_ms=500){
      $result = false;
      $search = new Search();
      $search->setCategory('Books');
      $search->setKeywords($arg);
      $search->setSort('daterank');
      $search->setResponseGroup(['Large']);
      $search->setPage($page);
      while ( true ) {
          try{
          $result = AmazonProduct::run($search);
            if ( !$result && $retry_count > 0 ) {
              usleep($retry_wait_ms * 1000);
              $retry_count--;
            } else {
              break;
            }
          } catch ( \Exception $e ) {
            continue;
          }
      }
    return $result;
  }
  /***
  * 日本語以外の本を除外する関数
  */
  private function check_lang($book_title, $response, $page){
    $finish = false;
    foreach ( $response as $k => $v ) {
      if(isset($v['ItemAttributes']['Languages']['Language'])){
          $check_ary = $v['ItemAttributes']['Languages']['Language'];
        try{
          if( !isset( $check_ary['Name'] ) ){
            $lang = $check_ary[0]['Name'];
          } else {
            $lang = $v['ItemAttributes']['Languages']['Language']['Name'];
          }
        } catch ( \Exception $e ) {
         //   $finish = "検索結果".$e->getMessage();
        }
        if( $lang !== '日本語' ) {
            unset( $response[$k] );
        }
      } else {
        $finish = true;
      }
    }
    $response = array_splice( $response, 0, 10 );
    if ( count( $response ) < 10 && $page < 10 || $finish = false) {
      $page++;
      $push_res = $this->retry_api( $book_title, $page );
      if(isset($push_res['Items']['Item'])){
        $push_res = $push_res['Items']['Item']; 
        $response = array_merge( $response, $push_res );
        $response = $this->check_lang( $book_title, $response, $page );
      }
    }
    return $response;
  }
  /***
  * nullや配列になっている値を書き換える
  */
  private function check_res($response){
    foreach($response as &$res){
      if( !isset($res['ItemAttributes']['PublicationDate']) ){
        $res['ItemAttributes']['PublicationDate'] = '';
      }
      if( !isset($res['ItemAttributes']['Author']) ){
        $res['ItemAttributes']['Author'] = '';
      }
      if( !isset($res['LargeImage']['URL']) ){
        $res['LargeImage']['URL'] = '';
      }
      if( isset($res['OfferSummary']['LowestNewPrice']['Amount']) ){
          $res['OfferSummary']['LowestNewPrice']['Amount'] = "￥".$res['OfferSummary']['LowestNewPrice']['Amount'];
      }else{
          $res['OfferSummary']['LowestNewPrice']['Amount'] = "価格が取得できませんでした。";
      }
      if( is_array($res['ItemAttributes']['Author']) ){
          $res['ItemAttributes']['Author'] = implode(", ", $res['ItemAttributes']['Author']);
      }
    }
    return $response;
  }
  /***
  * メインの関数
  */
  public function index(Request $request){
    $this->validate($request, [
            't'  => 'required|string|max:255',
        ], [
            't.required' => '検索ワードを入力してください',
            't.between'  => ':attributeは:maxまでやねん',
        ], [
            't' => 'タイトル',
        ]);
    $book_title = $request->input('t');
    $page = 1;
    $loop_count = 1;
    $response = null;
    $error = null;
    try {
      $response = $this->retry_api( $book_title );
      if(isset($response['Items']['Item'])){
         History::firstOrCreate([
            'word' => $book_title, 
          ])->touch();
          $history = History::where('word', $book_title)->first();
        if(Auth::check()){
          $user = User::find(Auth::id());
          $user->histories()->attach($history->id);
        }
        $response = $response['Items']['Item'];
        $response = $this->check_lang( $book_title, $response, $page );
        $book_title = "「".$book_title."」";
      } elseif( isset($response['Items']['Request']['Errors']) ){
        $error = "検索結果がありません。";
      }
    } catch ( \Exception $e ) {
        $response[] = "エラーが発生しました。更新してください。a" .$e->getMessage();
    }
    $response = $this->check_res($response);
    return view('result.index', [
      'book_title' => $book_title,
      'response' => $response,
      'error' => $error,
      'loop_count' => $loop_count,
      ]);
  }
}
