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
      array_push($result, new PlanDto([
        'id'    => $plan->id,
        'title' => $plan->title,
        'text'  => $plan->text
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
      'text'  => $plan->text
    ]);
  }

  public function update(PlanDto $dto)
  {

    $plan = Plan::find($dto->id);
    if (!$plan) {
      return null;
    }

    $plan->title = $dto->title;
    $plan->text  = $dto->text;

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
      'text'  => $dto->text
    ]);
    return true;
  }
}
