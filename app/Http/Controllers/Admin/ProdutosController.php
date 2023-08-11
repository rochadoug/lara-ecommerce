<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Produto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProdutosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $filterDate = false;
        $ftrPage = $request->input('ftrPage') ?? 20;

        if ($request->input('ftrDate')) {
            $filterDate = true;
            $date = explode(' - ', $request->input('ftrDate'));
        } else {
            $date = array(date('Y-m-d', strtotime('-7 day')), date('Y-m-d'));
        }

        $listRecords = Produto::withoutGlobalScopes();

        if ($filterDate)
            $listRecords->whereDate('created_at', '>=', $date[0])
                        ->whereDate('created_at', '<=', $date[1]);

        $listRecords = $listRecords->sortable(['id' => 'desc'])->paginate($ftrPage);
        $links = $listRecords->appends(request()->except('page'))->links();

        $params = array(
            //'action'        => 'index',
            'listRecords'   => $listRecords,
            'record'        => new Produto,
            'links'         => $links,
            'ftrPage'       => $ftrPage,
            'ftrDate'       => $filterDate ? (implode('/', array_reverse(explode('-', $date[0]))).' - '.implode('/', array_reverse(explode('-', $date[1])))) : ''
        );

        return view('admin/produtos', $params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->except('_token', '_method');

        //
        $data['valor'] = str_replace(['.', ','], ['', '.'], $data['valor']);
        $data['ativo'] = !!(($data['ativo']??'')=='on');

        // Valida campos
        //$this->validator($data);

        try {

            if ($request->hasFile('imagem')) {
                $imgPath    = 'public/produtos';
                $imagem     = $data['imagem'];
                $imgName    = $imagem->getClientOriginalName();
                //$imgExt     = pathinfo($imgName, PATHINFO_EXTENSION);

                $imgPathSaved = $imagem->storeAs($imgPath, $imgName);
            } else {
                $imgPathSaved = null;
            }

            $produto = new Produto;

            $produto->nome      = $data['nome'];
            $produto->descricao = $data['descricao'];
            $produto->valor     = $data['valor'];
            $produto->ativo     = $data['ativo'];
            $produto->imagem    = $imgPathSaved;

            $produto->save();

            return redirect('admin/produtos')->withSuccess('Registro salvo com sucesso!');
        } catch (Exception $e) {
            Storage::delete($imgPathSaved);
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $record = Produto::find($id);
        //d( $record );

        $params = array(
            //'action'        => 'show',
            'listRecords'   => Produto::paginate(0),
            'links'         => '',
            'record'        => $record,
        );

        return view('admin/produtos', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = $request->except('_token', '_method');

        //
        $data['valor'] = str_replace(['.', ','], ['', '.'], $data['valor']);
        $data['ativo'] = !!(($data['ativo']??'')=='on');

        // Valida campos
        //$this->validator($data);

        try {

            if ($request->hasFile('imagem')) {
                $imgPath    = 'public/produtos';
                $imagem     = $data['imagem'];
                $imgName    = $imagem->getClientOriginalName();
                //$imgExt     = pathinfo($imgName, PATHINFO_EXTENSION);

                $imgPathSaved = $imagem->storeAs($imgPath, $imgName);
            } else {
                $imgPathSaved = null;
            }

            $produto = Produto::find($id);

            $oldImg = $produto->imagem;
            $trueImage = $imgPathSaved ?? $oldImg;

            $produto->nome      = $data['nome'];
            $produto->descricao = $data['descricao'];
            $produto->valor     = $data['valor'];
            $produto->imagem    = $trueImage;
            $produto->ativo     = $data['ativo'];
            $produto->save();

            if ($produto->imagem == $imgPathSaved)
                Storage::delete($oldImg);
            else
                Storage::delete($imgPathSaved);

            return redirect('admin/produtos')->withSuccess('Alterações feitas com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $produto = $id;

        try {

            $produto = Produto::find($id);

            if (!$produto)
                redirect()->back()->withErrors('Produto não encontrado!');

            $produto->delete();

            return redirect('admin/produtos');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    protected function validator($data, $record=null)
    {
        //$customAttributes = ['' => '', '' => ''];

        return Validator::make($data, [
            ''          => ['required'],
        ], []/* , $customAttributes */)->validate();
    }


    public function ajaxSearch(Request $request)
    {
        //
        //dd( $request->produto, $request->input('produto') );

        //$this->validator($request);

        $term = $request->produto;

        if ($term === null)
            return [];

        if (ctype_digit($term)) {
            $term = ltrim($term, '0');
            $produtos = [Produto::select('id', 'nome', 'valor', 'imagem')->find($term)];
        } else
            $produtos = Produto::select('id', 'nome', 'valor', 'imagem')->where('nome', 'ilike', $term.'%')->get();

        return $produtos;
    }
}
