<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controllers;
use App\Models\Usuario;
use App\Models\PersonalRecord;
use App\Models\Movimento;


use Response;

class PersonalRecordContoller extends Controller
{
    public function exibirRanking()
    {
        $personal_record = DB::table('personal_record AS pr')
                            ->select(
                                DB::raw('DENSE_RANK() OVER (
                                        PARTITION BY mov.name
                                        ORDER BY MAX(pr.value) DESC) AS Colocação'),
                                'mov.name as Movimento',
                                'usr.name as Nome', 
                                DB::raw('MAX(pr.value) as Value'))
                            ->join('user as usr','usr.id','=','pr.user_id')
                            ->join('movement as mov','mov.id','=','pr.movement_id')
                            ->groupBy('usr.name','mov.name')
                            ->orderBy('mov.name','ASC')
                            ->orderBy(DB::raw('MAX(pr.value)'),'DESC')
                            ->get();

        if(isset($personal_record)){

            return $this->returnSuccess($personal_record);
        }else{
            return $this->returnError('Sem registros');

        }
    }


    private function returnSuccess($dados)
    {
        return [
            'code' => 200,
            'status' => 'success',
            'data' => $dados
        ];
    }

    private function returnError($retorno, $status = 403)
    {
        return Response::json(['code' => $status, 'status' => 'error', 'data' => $retorno], $status);
    }    
}
