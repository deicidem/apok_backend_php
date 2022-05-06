<?php

namespace App\Http\Services;

use App\Models\Alert;
use App\Http\Services\Dto\DtoInterface;
use App\Http\Services\Dto\DzzDto;
use App\Http\Services\Dto\AlertDto;
use App\Http\Services\Dto\SearchDto;
use InvalidArgumentException;

class AlertService
{
  public function getAll()
  {
    $alerts = Alert::all();
    $result = [];
    foreach ($alerts as $alert) {
      
      // array_push($result, new AlertDto(toArr($alert)));
      array_push($result, new AlertDto([
        'id'          => $alert->id,
        'title'       => $alert->title,
        'description' => $alert->description
      ]));
    };
    return $result;
  }

  public function getOne($id)
  {
    $alert = Alert::find($id);
    if (!$alert) {
      return null;
    }
    // return new AlertDto(toArr($alert));
    return new AlertDto([
      'title'       => $alert->title,
      'description' => $alert->description
    ]);
  }

  public function update(AlertDto $dto)
  {

    $alert = Alert::find($dto->id);
    if (!$alert) {
      return null;
    }

    $alert->title       = $dto->title;
    $alert->description = $dto->description;

    $alert->save();

    return true;
  }

  public function delete($id)
  {
    $alert = Alert::find($id);
    if (!$alert) {
      return null;
    }
    $alert->delete();

    return true;
  }

  public function post(AlertDto $dto)
  {
    // Alert::create(toArr($dto));
    Alert::create([
      'title'       => $dto->title,
      'description' => $dto->description
    ]);
    return true;
  }
}

function toArr($obj){
  return json_decode(json_encode($obj), true);
}