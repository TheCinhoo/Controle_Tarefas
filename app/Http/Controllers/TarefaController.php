<?php

namespace App\Http\Controllers;

use Mail;
use App\Mail\NovaTarefaMail;
use App\Models\Tarefa;
use App\Exports\TarefasExoirt;
use App\Exports\TarefasExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class TarefaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;

        $tarefa = Tarefa::where('user_id', $user_id)->paginate(10);
        return view('tarefa.index', ['tarefas' => $tarefa]);


        // if (auth()->check()) {
        //     $id = auth()->user()->id;
        //     $nome = auth()->user()->name;
        //     $email = auth()->user()->email;
        //     return "Chegamos! $id - $nome - $email";
        // } else {
        //     return "Não Está Logado no Sistema!";
        // }

        // if (Auth::check()) {
        //     $id = Auth::user()->id;
        //     $nome = Auth::user()->name;
        //     $email = Auth::user()->email;
        //     return "Chegamos! $id - $nome - $email";
        // } else {
        //     return "Não Está Logado no Sistema!";
        // }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tarefa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dados = $request->all();
        $dados['user_id'] = auth()->user()->id;
        $tarefa = Tarefa::create($dados);
        $destinatario = auth()->user()->email;
        Mail::to($destinatario)->send(new NovaTarefaMail($tarefa));
        return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tarefa $tarefa)
    {
        return view('tarefa.show', ['tarefa' => $tarefa]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tarefa $tarefa)
    {
        $user_id = auth()->user()->id;

        if ($tarefa->user_id == $user_id) {
            return view('tarefa.edit', ['tarefa' => $tarefa]);
        }

        return view('acesso-negado');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarefa $tarefa)
    {
        $user_id = auth()->user()->id;

        if ($tarefa->user_id == $user_id) {
            $tarefa->update($request->all());
            return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);
        }
        return view('acesso-negado');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarefa $tarefa)
    {
        $user_id = auth()->user()->id;

        if ($tarefa->user_id == $user_id) {
            $tarefa->delete();
            return redirect()->route('tarefa.index');
        }
        return view('acesso-negado');
    }

    public function exportacao($extensao)
    {
        $nome_arquivo = 'lista_de_tarefas.';
        if (in_array($extensao, ['xlsx', 'csv', 'pdf'])) {
            $nome_arquivo .=  $extensao;
            return Excel::download(new TarefasExport, $nome_arquivo);
        }
        return redirect()->route('tarefa.index');
    }

    public function exportar()
    {
        $tarefas = auth()->user()->tarefas()->get();
        $pdf = PDF::loadView('tarefa.pdf', ['tarefas' => $tarefas]);
        $pdf->setPaper('a4', 'portrait');
        // return $pdf->download('invoice.pdf');
        return $pdf->stream('invoice.pdf');
    }
}
