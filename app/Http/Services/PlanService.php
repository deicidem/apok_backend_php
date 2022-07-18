<?php

namespace App\Http\Services;

use App\Models\Plan;
use App\Http\Services\Dto\DtoInterface;
use App\Http\Services\Dto\DzzDto;
use App\Http\Services\Dto\PlanDto;
use App\Http\Services\Dto\SearchDto;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class PlanService
{
  public function getAll()
  {
    $plans  = Plan::all();
    return $plans;
  }

  public function getOne($id)
  {
    $plan = Plan::find($id);
    if (!$plan) {
      return null;
    }
    return $plan;
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
