<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
use App\Servicio;
use App\Repetidor;

class LogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return redirect()->route('dashboard');
    }

    /**
     * Obtener los Logs del Serivicio
     *
     * @param  int  $request
     * @return \Illuminate\Http\Response
     */
    protected function logsByType($logs, $type)
    {
      $model = $type == 'servicio' ? Servicio::find($logs) : Repetidor::find($logs);

      $logs = [
        'all' => $model->formatLogs('logsAll'),
        'success' => $model->formatLogs('logsSuccess'),
        'error' => $model->formatLogs('logsError'),
      ];

      return response()->json($logs);
    }
}
