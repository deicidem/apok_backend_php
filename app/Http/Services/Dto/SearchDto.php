<?php

namespace App\Http\Services\Dto;

use App\Http\Services\Dto\Base\AbstractDto;
use App\Http\Services\Dto\Base\DtoInterface;

class SearchDto extends AbstractDto implements DtoInterface
{
    /* @var string */
    public $startDate;
    public $endDate;
    public $startCloudiness;
    public $endCloudiness;
    public $months;
    public $satelites;
    public $polygon;
    /* @return array */
    protected function configureValidatorRules(): array
    {
        return [
            'startDate' => 'required',
            'endDate' => 'required',
            'startCloudiness' => 'required',
            'endCloudiness' => 'required',
            'months' => 'required',
            'satelites' => 'required',
            'polygon' => 'required'
        ]; 
    }

    /**
     * @inheritDoc
     */
    protected function map($data): bool
    {
        $this->startDate = $data['startDate'];
        $this->endDate = $data['endDate'];
        $this->startCloudiness = $data['startCloudiness'];
        $this->endCloudiness = $data['endCloudiness'];
        $this->months = $data['months'];
        $this->satelites = $data['satelites'];
        $this->polygon = $data['polygon'];
        
        return true;
    }
}
