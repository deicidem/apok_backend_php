<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\Dzz;
use App\Models\File;
use App\Models\FileType;
use App\Models\Plan;
use App\Models\ProcessingLevel;
use App\Models\Satelite;
use App\Models\SateliteType;
use App\Models\Sensor;
use App\Models\Spector;
use App\Models\Task;
use App\Models\TaskStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Plan::Create([
            'title' => 'Формирование температурных карт 1',
            'text'  => 'Построение карты температур по тепловым каналам КА Landsat-8 производится с целью вычисления значений температур поверхности в градусах Цельсия, выявления тепловых аномалий.'
        ]);
        Plan::Create([
            'title' => 'Формирование температурных карт 2',
            'text'  => 'Построение карты температур по тепловым каналам КА Landsat-8 производится с целью вычисления значений температур поверхности в градусах Цельсия, выявления тепловых аномалий.'
        ]);
        Plan::Create([
            'title' => 'Формирование температурных карт 3',
            'text'  => 'Построение карты температур по тепловым каналам КА Landsat-8 производится с целью вычисления значений температур поверхности в градусах Цельсия, выявления тепловых аномалий.'
        ]);
        Plan::Create([
            'title' => 'Формирование температурных карт 4',
            'text'  => 'Построение карты температур по тепловым каналам КА Landsat-8 производится с целью вычисления значений температур поверхности в градусах Цельсия, выявления тепловых аномалий.'
        ]);

        SateliteType::Create([
            'name' => 'Канопус-В',
        ]);
        SateliteType::Create([
            'name' => 'Ресурс-П',
        ]);

        Satelite::Create([
            'name'             => 'Канопус-В 1',
            'description'      => 'Lorem ipsum',
            'satelite_type_id' => 1
        ]);
        Satelite::Create([
            'name'             => 'Канопус-В 2',
            'description'      => 'Lorem ipsum',
            'satelite_type_id' => 1
        ]);
        Satelite::Create([
            'name'             => 'Ресурс-П 1',
            'description'      => 'Lorem ipsum',
            'satelite_type_id' => 2
        ]);

        Sensor::Create([
            'name'        => 'Сенсор 1',
            'description' => 'Lorem ipsum',
            'satelite_id' => 1
        ]);
        Sensor::Create([
            'name'        => 'Сенсор 2',
            'description' => 'Lorem ipsum',
            'satelite_id' => 2
        ]);
        Sensor::Create([
            'name'        => 'Сенсор 3',
            'description' => 'Lorem ipsum',
            'satelite_id' => 3
        ]);

        Spector::Create([
            'name'      => 'Спектор 1',
            'start_w'   => 100,
            'end_w'     => 200,
            'sensor_id' => 1
        ]);
        Spector::Create([
            'name'      => 'Спектор 1',
            'start_w'   => 100,
            'end_w'     => 200,
            'sensor_id' => 2
        ]);
        Spector::Create([
            'name'      => 'Спектор 1',
            'start_w'   => 100,
            'end_w'     => 200,
            'sensor_id' => 3
        ]);

        ProcessingLevel::Create([
            'name' => 'Уровень 1'
        ]);
        ProcessingLevel::Create([
            'name' => 'Уровень 2'
        ]);
        ProcessingLevel::Create([
            'name' => 'Уровень 3'
        ]);

        Dzz::Create([
            'name'                => 'ДЗЗ 1',
            'date'                => now(),
            'round'               => 23121,
            'route'               => 1,
            'cloudiness'          => 10,
            'geography'           => Storage::get('files/1_ДЗЗ 1/снимок1.json'),
            'description'         => 'lorem ipsum',
            'processing_level_id' => 1,
            'sensor_id'           => 1
        ]);
        Dzz::Create([
            'name'                => 'ДЗЗ 2',
            'date'                => now(),
            'round'               => 23121,
            'route'               => 2,
            'cloudiness'          => 20,
            'geography'           => Storage::get('files/2_ДЗЗ 2/снимок2.json'),
            'description'         => 'lorem ipsum',
            'processing_level_id' => 2,
            'sensor_id'           => 2
        ]);

        FileType::Create([
            'name' => 'geography'
        ]);
        FileType::Create([
            'name' => 'preview'
        ]);
        FileType::Create([
            'name' => 'data'
        ]);

        File::Create([
            'name'         => 'снимок1.png',
            'file_type_id' => 2,
            'path'         => 'files/1_ДЗЗ 1/снимок1.png',
            'dzz_id'       => 1
        ]);
        File::Create([
            'name'         => 'снимок2.png',
            'file_type_id' => 2,
            'path'         => 'files/2_ДЗЗ 2/снимок2.png',
            'dzz_id'       => 2
        ]);

        TaskStatus::Create([
            'name' => 'Статус 1'
        ]);
        TaskStatus::Create([
            'name' => 'Статус 2'
        ]);
        TaskStatus::Create([
            'name' => 'Статус 3'
        ]);

        Task::Create([
            'title'          => 'Формирование температурных карт 1',
            'result'         => null,
            'dzz_id'         => 1,
            'task_status_id' => 1
        ]);
        Task::Create([
            'title'          => 'Формирование температурных карт 2',
            'result'         => null,
            'dzz_id'         => 2,
            'task_status_id' => 2
        ]);
        Task::Create([
            'title'          => 'Формирование температурных карт 3',
            'result'         => null,
            'dzz_id'         => 3,
            'task_status_id' => 3
        ]);

        Alert::Create(
            [
                'title'       => 'Уведомление 1',
                'description' => 'Lorem ipsum'
            ]
        );
        Alert::Create(
            [
                'title'       => 'Уведомление 2',
                'description' => 'Lorem ipsum'
            ]
        );
        Alert::Create(
            [
                'title'       => 'Уведомление 3',
                'description' => 'Lorem ipsum'
            ]
        );
    }
}
