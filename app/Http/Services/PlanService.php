<?php

namespace App\Http\Services;

use App\Models\Plan;
use App\Http\Services\Dto\DtoInterface;
use App\Http\Services\Dto\DzzDto;
use App\Http\Services\Dto\PlanDto;
use App\Http\Services\Dto\SearchDto;
use InvalidArgumentException;

class PlanService
{
  public function getAll()
  {
    $plans  = Plan::all();
    $result = [];
    foreach ($plans as $plan) {
      $requirements = [];
      foreach ($plan->requirements as $req) {
        array_push($requirements, [
          'id' => $req->id,
          'title'  => $req->title,
          'description' => $req->description
        ]);
      }
      $data = [];
      foreach ($plan->data as $d) {
        array_push($data, [
          'id' => $d->id,
          'title'  => $d->title,
          'description' => $d->description,
          'type' => $d->type_id
        ]);
      }
      array_push($result, new PlanDto([
        'id'    => $plan->id,
        'title' => $plan->title,
        'excerpt' => $plan->excerpt,
        'description'  => $plan->description,
        'data' => $data,
        'requirements' => $requirements,
        'previewPath' => "/api/images?id=".$plan->preview_id
      ]));
    };
    return $result;
  }

  public function getOne($id)
  {
    $plan = Plan::find($id);
    if (!$plan) {
      return null;
    }
    return new PlanDto([
      'id'    => $plan->id,
      'title' => $plan->title,
      'description'  => $plan->description
    ]);
  }

  public function update(PlanDto $dto)
  {

    $plan = Plan::find($dto->id);
    if (!$plan) {
      return null;
    }

    $plan->title = $dto->title;
    $plan->description  = $dto->description;

    $plan->save();

    return true;
  }

  public function delete($id)
  {
    $plan = Plan::find($id);
    if (!$plan) {
      return null;
    }
    $plan->delete();

    return true;
  }

  public function post(PlanDto $dto)
  {
    Plan::create([
      'title' => $dto->title,
      'description'  => $dto->description
    ]);
    return true;
  }
}
