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
        $json    = json_decode($searchDto->polygon, true);
        $polygon = json_encode(\GeoJson\GeoJson::jsonUnserialize($json)
            ->getGeometry()->jsonSerialize());

        $placeholders = implode(",", array_fill(0, count($searchDto->months), '?'));

        return Dzz::whereBetween('date', [$searchDto->startDate, $searchDto->endDate])
            ->whereBetween('cloudiness', [$searchDto->startCloudiness, $searchDto->endCloudiness])
            ->whereIn('satelite_id', $searchDto->satelites)
            ->whereRaw("EXTRACT(MONTH FROM date) IN ($placeholders)", $searchDto->months)
            ->whereRaw("ST_Intersects(geography, ST_GeomFromGeoJSON(?))", $polygon)
            ->selectRaw("*, ST_AsGeoJSON(ST_SimplifyPreserveTopology(geography::geometry, 1), 5, 1) as geography")
            ->paginate(10);

        // print_r($dzzs[0]->processingLevel);
        // $data = [];

        // foreach ($dzzs as $dzz) {

        //         $geography = Dzz::selectRaw('ST_AsGeoJSON(ST_SimplifyPreserveTopology(geography::geometry, 1), 5, 1) as geography, id')->whereRaw('id = ? and ST_Intersects(geography, ST_GeomFromGeoJSON(?))', [$dzz->id, $polygon])->get();

        //         if (count($geography) != 0) {
        //             $previewPath = Storage::url($dzz->preview->path);
        //             $dto = new DzzDto([
        //                 'id'              => $dzz->id,
        //                 "name"            => $dzz->name,
        //                 "date"            => $dzz->date,
        //                 "round"           => $dzz->round,
        //                 "route"           => $dzz->route,
        //                 "cloudiness"      => $dzz->cloudiness,
        //                 "processingLevel" => $dzz->processingLevel->name,
        //                 "satelite"        => $dzz->satelite->name,
        //                 "previewPath"     => '/public'.$previewPath,
        //                 "geography"       => json_decode($geography[0]->geography)
        //             ]);
        //             array_push($data, $dto);
        //         }
        // }

        //  $dzzs;
    }
}
