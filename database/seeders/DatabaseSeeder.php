<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\Dzz;
use App\Models\File;
use App\Models\FileType;
use App\Models\Plan;
use App\Models\PlanData;
use App\Models\PlanDataType;
use App\Models\PlanRequirement;
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
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
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
            'name'         => 'img.png',
            'file_type_id' => 2,
            'path'         => 'files/Plan1/img.png',
        ]);
        File::Create([
            'name'         => 'img.png',
            'file_type_id' => 2,
            'path'         => 'files/Plan2/img.png',
        ]);

        Plan::Create([
            'title' => 'Формирование температурных карт 1',
            'description'  => 'Построение карты температур по тепловым каналам КА Landsat-8 производится с целью вычисления значений температур поверхности в градусах Цельсия, выявления тепловых аномалий. Для определения температуры поверхности производятся вычисления спектральной интенсивности излучения, поверхностной яркостной температуры, спектрального коэффициента излучения, значений температур поверхности в градусах Цельсия. Результатом обработки является векторная карта температур и отчетная форма с информацией об используемом изображении. Для более наглядного представления результата используется универсальная температурная шкала [-100; +100]. Красным отображаются области высоких температур (очаги пожаров), синим – области низких температур.',
            'excerpt' => 'Построение карты температур по тепловым каналам КА Landsat-8 производится с целью вычисления значений температур поверхности в градусах Цельсия, выявления тепловых аномалий.',
            'file_id' => 1
        ]);
        Plan::Create([
            'title' => 'Формирование температурных карт 2',
            'description'  => 'Построение карты температур по тепловым каналам КА Landsat-8 производится с целью вычисления значений температур поверхности в градусах Цельсия, выявления тепловых аномалий. Для определения температуры поверхности производятся вычисления спектральной интенсивности излучения, поверхностной яркостной температуры, спектрального коэффициента излучения, значений температур поверхности в градусах Цельсия. Результатом обработки является векторная карта температур и отчетная форма с информацией об используемом изображении. Для более наглядного представления результата используется универсальная температурная шкала [-100; +100]. Красным отображаются области высоких температур (очаги пожаров), синим – области низких температур.',
            'excerpt' => 'Построение карты температур по тепловым каналам КА Landsat-8 производится с целью вычисления значений температур поверхности в градусах Цельсия, выявления тепловых аномалий.',
            'file_id' => 2
        ]);

        PlanDataType::Create([
            'name' => 'Rastr',
        ]);
        PlanDataType::Create([
            'name' => 'Vector',
        ]);

        PlanData::Create([
            'title' => 'Архивный снимок',
            'plan_data_type_id' => 1,
            'plan_id' => 1
        ]);
        PlanData::Create([
            'title' => 'Актуальный снимок',
            'plan_data_type_id' => 1,
            'plan_id' => 1
        ]);
        PlanData::Create([
            'title' => 'Зона интереса',
            'plan_data_type_id' => 2,
            'plan_id' => 1
        ]);

        PlanData::Create([
            'title' => 'Снимок',
            'plan_data_type_id' => 1,
            'plan_id' => 2
        ]);
        PlanData::Create([
            'title' => 'Зона интереса',
            'plan_data_type_id' => 2,
            'plan_id' => 2
        ]);

        PlanRequirement::Create([
            'title' => 'Данные',
            'description' => 'мультиспектральные оптические материалы с космического аппарата (КА) Landsat 8.',
            'plan_id' => 1
        ]);
        PlanRequirement::Create([
            'title' => 'Сезон съемки',
            'description' => 'с мая по сентябрь включительно',
            'plan_id' => 1
        ]);
        PlanRequirement::Create([
            'title' => 'Облачность',
            'description' => 'не более 20%',
            'plan_id' => 1
        ]);

        PlanRequirement::Create([
            'title' => 'Данные',
            'description' => 'мультиспектральные оптические материалы с космического аппарата (КА) Landsat 1.',
            'plan_id' => 2
        ]);
        PlanRequirement::Create([
            'title' => 'Сезон съемки',
            'description' => 'с февраля по август включительно',
            'plan_id' => 2
        ]);
        PlanRequirement::Create([
            'title' => 'Облачность',
            'description' => 'не более 30%',
            'plan_id' => 2
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
        $json1 = json_decode(Storage::get('files/1_ДЗЗ 1/снимок1.json'));
        $polygon1 = json_encode(\GeoJson\GeoJson::jsonUnserialize($json1)->getGeometry()->jsonSerialize());
        Dzz::Create([
            'name'                => 'ДЗЗ 1',
            'date'                => now(),
            'round'               => 23121,
            'route'               => 1,
            'cloudiness'          => 10,
            'geography'           => DB::raw("ST_GeomFromGeoJSON('$polygon1')"),
            'description'         => 'lorem ipsum',
            'processing_level_id' => 1,
            'sensor_id'           => 1
        ]);
        $json2 = json_decode(Storage::get('files/2_ДЗЗ 2/снимок2.json'));
        $polygon2 = json_encode(\GeoJson\GeoJson::jsonUnserialize($json2)->getGeometry()->jsonSerialize());
        Dzz::Create([
            'name'                => 'ДЗЗ 2',
            'date'                => now(),
            'round'               => 23121,
            'route'               => 2,
            'cloudiness'          => 20,
            'geography'           => DB::raw("ST_GeomFromGeoJSON('$polygon2')"),
            'description'         => 'lorem ipsum',
            'processing_level_id' => 2,
            'sensor_id'           => 2
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
