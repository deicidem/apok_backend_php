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
    public function get($input)
    {
        $json    = json_decode($input['polygon'], true);
        $polygon = json_encode(\GeoJson\GeoJson::jsonUnserialize($json)
            ->getGeometry()->jsonSerialize());

        $placeholders = implode(",", array_fill(0, count($input['months']), '?'));

        $query = Dzz::query();

        $query->whereBetween('date', [$input['startDate'], $input['endDate']])
            ->whereBetween('cloudiness', [$input['startCloudiness'], $input['endCloudiness']])
            ->whereIn('satelite_id', $input['satelites'])
            ->whereRaw("EXTRACT(MONTH FROM date) IN ($placeholders)", $input['months'])
            ->whereRaw("ST_Intersects(geography, ST_GeomFromGeoJSON(?))", $polygon)
            ->selectRaw("*, ST_AsGeoJSON(ST_SimplifyPreserveTopology(geography::geometry, 1), 5, 1) as geography");


        $query->when(isset($input['sortBy']), function ($q) use ($input) {
            $descending = false;
            if (isset($input['desc'])) {
                $descending = filter_var($input['desc'], FILTER_VALIDATE_BOOLEAN);
            }

            $sortBy = $input['sortBy'];
            if ($sortBy == 'name') {
                return $q->orderBy('name', $descending ? 'desc' : 'asc');
            } else if ($sortBy == 'date') {
                return $q->orderBy('date', $descending ? 'desc' : 'asc');
            }else if ($sortBy == 'cloudiness') {
                return $q->orderBy('cloudiness', $descending ? 'desc' : 'asc');
            }else if ($sortBy == 'satelite_id') {
                return $q->orderBy('satelite', $descending ? 'desc' : 'asc');
            } else {
                return $q->orderBy('id', $descending ? 'desc' : 'asc');
            }
        }, function ($q) {
            return $q->orderBy('id');
        });

        $paginationSize = 2;
        if (isset($input['size'])) {
            if ($input['size'] > 50) {
                $paginationSize = 50;
            } else if ($input['size'] < 1) {
                $paginationSize = 1;
            } else {
                $paginationSize = $input['size'];
            }
        }

        return $query->paginate($paginationSize);;
    }
}
