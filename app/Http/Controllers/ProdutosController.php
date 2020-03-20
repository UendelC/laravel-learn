<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Produto;
use Illuminate\Support\Facades\Auth;

class ProdutosController extends Controller
{
    public function index() {
        $produtos = Produto::paginate(4);
        return view('produto.index', array('produtos' => $produtos, 'busca' => null));
    }

    public function show($id) {
        $produto = Produto::find($id);
        return view('produto.show', array('produto' => $produto));
    }

    public function create() {
        if(Auth::check()) {
            return view('produto.create');
        } else {
            return redirect('login');
        }
    }

    public function store(Request $request) {
        if(Auth::check()) {
            $produto = new Produto();
            $produto->referencia = $request->input('referencia');
            $produto->titulo =  $request->input('titulo');
            $produto->descricao = $request->input('descricao');
            $produto->preco = $request->input('preco');

            if($produto->save()) {
                return redirect('podutos');
            }
            $this->validate($request, [
                'referencia' => 'required|min:3',
            ]);
        } else {
            return redirect('login');
        }
    }

    public function edit($id){
        $produto = Produto::find($id);
        return view('produto.edit', array('produto' => $produto));
    }

    public function update($id, Request $request){
        if(Auth::check()) {
            $produto = Produto::find($id);
            $this->validate($request, [
                'referencia' => 'required|min:3',
                'titulo' => 'required|min:3',
            ]);
            if($request->hasFile('fotoproduto')){
                $imagem = $request = $request->file('fotoproduto');
                $nomearquivo = md5($id) . ".".$imagem->getClientOriginalExtension();
                $request->file('fotoproduto')->move(public_path('./img/produtos/'), $nomearquivo);
            }
            $produto->referencia = $request->input('referencia');
            $produto->titulo = $request->input('descricao');
            $produto->preco = $request->input('preco');
            $produto->save();
            Session::flash('mensagem', 'Produto alterado com sucesso.');
            return redirect()->back();
        } else {
            return redirect('login');
        }
    }

    public function destroy($id) {
        if(Auth::check()) {
            $produto = Produto::find($id);
            $produto->delete();
            Session::flash('mensagem', 'Produto excluído com sucesso.');
            return redirect()->back();
        } else {
            return redirect('login');
        }
    }

    public function buscar(Request $request) {
        $produtos = Produto::where('titulo', 'LIKE'
        , '%'.$request->input('busca').'%')->orwhere('descricao', 'LIKE',
        '%'.$request->input('busca').'%')->paginate(4);

        return view('produto.index', array('produtos' => $produtos,
        'busca' => $request->input('busca')));
    }
}
