<?php

namespace App\Http\Services\Dto;

use App\Http\Services\Dto\Base\AbstractDto;
use App\Http\Services\Dto\Base\DtoInterface;

class DzzDto extends AbstractDto implements DtoInterface
{
    /* @var string */
    public $id;
    public $name;
    public $date;
    public $round;
    public $route;
    public $cloudiness;
    public $processingLevel;
    public $sensor;
    public $previewPath;
    public $geography;
    /* @return array */
    protected function configureValidatorRules(): array
    {
        return [
            'id'              => 'required',
            'name'            => 'required',
            'date'            => 'required',
            'round'           => 'required',
            'route'           => 'required',
            'cloudiness'      => 'required',
            'processingLevel' => 'required',
            'sensor'          => 'required',
            'previewPath'     => 'required',
            'geography'       => 'required',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function map(array $data): bool
    {
        $this->id              = $data['id'];
        $this->name            = $data['name'];
        $this->date            = $data['date'];
        $this->round           = $data['round'];
        $this->route           = $data['route'];
        $this->cloudiness      = $data['cloudiness'];
        $this->processingLevel = $data['processingLevel'];
        $this->sensor          = $data['sensor'];
        $this->previewPath     = $data['previewPath'];
        $this->geography       = $data['geography'];

        return true;
    }

     public function __toString() {
       return [
        'id'              => $this->id,
        'name'            => $this->name,
        'date'            => $this->date,
        'round'           => $this->round,
        'route'           => $this->route,
        'cloudiness'      => $this->cloudiness,
        'processingLevel' => $this->processingLevel,
        'sensor'          => $this->sensor
       ];
     }
}