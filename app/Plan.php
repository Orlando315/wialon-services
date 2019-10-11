<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Flow;

class Plan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'planes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'nombre',
      'precio',
      'meses',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
      'meses' => 'integer',
    ];

    /**
     * Formatear precio.
     */
    public function precio()
    {
      return number_format($this->precio, 0, ',', '.');
    }

    /**
     * Generar PlanId para Flow
     */
    protected function generatePlanId()
    {
      $this->planId = str_random(15);
      return $this->planId;
    }

    /**
     * Obtener el estado del Plan
     */
    public function status()
    {
      return $this->deleted_at ? '<span class="badge badge-danger">Eliminado</span>' : '<span class="badge badge-success">Activo</span>';
    }

    /**
     * Crear Plan en Flow
     */
    public function createFlowPlan()
    {
      $flow = new Flow;

      $params = [
        'planId' => $this->generatePlanId(),
        'name' => $this->nombre,
        'currency' => 'CLP',
        'amount' => $this->precio,
        'interval' => 3,
        'interval_count' => $this->meses,
        'days_until_due' => 5,
        'urlCallback' => route('suscripciones.confirmation')
      ];

      $response = $flow->send('plans/create', $params, 'POST');

      if($response){
        return $this->save();
      }

      return false;
    }

    /**
     * Editar Plan en Flow
     */
    public function editFlowPlan()
    {
      $flow = new Flow;

      $params = [
        'planId' => $this->planId,
        'name' => $this->nombre,
        'currency' => 'CLP',
        'amount' => $this->precio,
        'interval' => 3,
        'interval_count' => $this->meses,
        'days_until_due' => 5,
        'urlCallback' => route('suscripciones.confirmation')
      ];

      $response = $flow->send('plans/edit', $params, 'POST');

      if($response){
        return $this->save();
      }

      return false;
    }

    /**
     * Eliminar Plan en Flow
     */
    public function deleteFlowPlan()
    {
      $flow = new Flow;

      $params = [
        'planId' => $this->planId,
      ];

      $response = $flow->send('plans/delete', $params, 'POST');

      if($response){
        $this->deleted_at = date('Y-m-d H:i:s');
        return $this->save();
      }

      return false;
    }

    /**
     * Obtener las Suscripciones del Plan
     */
    public function suscripciones()
    {
      return $this->hasMany('App\Suscripcion');
    }

    /**
     * Obtener los Servicios suscritos al Plan
     */
    public function servicios()
    {
      return $this->belongsToMany('App\Servicio', 'suscripciones')
                  ->withPivot(
                    'status',
                    'subscriptionId',
                    'subscription_start',
                    'period_start',
                    'period_end',
                    'status_flow',
                    'response'
                  );
    }
}
