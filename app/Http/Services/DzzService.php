<?php

namespace App\Http\Services;

use App\Models\Dzz;

use App\Http\Services\Dto\DtoInterface;
use App\Http\Services\Dto\DzzDto;
use App\Http\Services\Dto\SearchDto;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class DzzService
{
    public function get(SearchDto $searchDto)
    {
        $dzzs = Dzz::all()
            ->whereBetween('date', [$searchDto->startDate, $searchDto->endDate])
            ->whereBetween('cloudiness', [$searchDto->startCloudiness, $searchDto->endCloudiness])
            ->whereIn('sensor_id', $searchDto->satelites);

        // print_r($dzzs[0]->processingLevel);


        $data = [];

        foreach ($dzzs as $dzz) {
            if (in_array(intval(date('m', strtotime($dzz->date))), $searchDto->months)) {
                $file = Storage::get('files/2_ДЗЗ 2/снимок2.json');
                $dzz_bounds = \GeoJson\GeoJson::jsonUnserialize(json_decode($file))->getGeometry()->getCoordinates()[0];
                // get bbox
                // print_r(\GeoJson\GeoJson::jsonUnserialize(json_decode($file))->getGeometry()->getBoundingBox());

                $json = json_decode($searchDto->polygon);
                $feature = \GeoJson\GeoJson::jsonUnserialize($json);
                $polygon = $feature->getGeometry();
                $bounds = $polygon->getCoordinates()[0];
                printf(doHaveCross($dzz_bounds, $bounds) ? 'IN' : 'OUT');
                
                $dto = new DzzDto([
                    'id'              => $dzz->id,
                    "name"            => $dzz->name,
                    "date"            => $dzz->date,
                    "round"           => $dzz->round,
                    "route"           => $dzz->route,
                    "cloudiness"      => $dzz->cloudiness,
                    "processingLevel" => $dzz->processingLevel->name,
                    "sensor"          => $dzz->sensor->name,
                    "files"           => $dzz->files,
                    "tasks"           => $dzz->tasks
                ]);
                array_push($data, $dzz);
            };
        }

        return $data;
    }
}

function doHaveCross($polygonInside, $polygonOutside) {
    $contains = false;
    foreach ($polygonInside as $point) {
       if(containsPoint($point, $polygonOutside)) {
         $contains = true;
         break;
       }
    }
    if ($contains) {
        return true;
    }

    foreach ($polygonOutside as $point) {
        if(containsPoint($point, $polygonInside)) {
         $contains = true;
         break;
       }
    }
    return $contains;
    
}
function containsPoint($point, $polygon)
{
    if ($polygon[0] != $polygon[count($polygon) - 1])
        $polygon[count($polygon)] = $polygon[0];
    $j = 0;
    $oddNodes = false;
    $y = $point[0];
    $x = $point[1];
    $n = count($polygon);
    for ($i = 0; $i < $n; $i++) {
        $j++;
        if ($j == $n) {
            $j = 0;
        }

        if ((($polygon[$i][0] < $y) && ($polygon[$j][0] >= $y)) ||
             (($polygon[$j][0] < $y) && ($polygon[$i][0] >= $y))) {
            if ($polygon[$i][1] + ($y - $polygon[$i][0]) / ($polygon[$j][0] - $polygon[$i][0]) * ($polygon[$j][1] - $polygon[$i][1]) < $x) {
                $oddNodes = !$oddNodes;
            }
        }
    }
    return $oddNodes;
}
