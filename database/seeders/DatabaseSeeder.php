<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plans')->insert([[
            'title' => 'Формирование температурных карт',
            'text' => 'Построение карты температур по тепловым каналам КА Landsat-8 производится с целью вычисления значений температур поверхности в градусах Цельсия, выявления тепловых аномалий.'
        ],[
            'title' => 'Формирование температурных карт',
            'text' => 'Построение карты температур по тепловым каналам КА Landsat-8 производится с целью вычисления значений температур поверхности в градусах Цельсия, выявления тепловых аномалий.'
        ],[
            'title' => 'Формирование температурных карт',
            'text' => 'Построение карты температур по тепловым каналам КА Landsat-8 производится с целью вычисления значений температур поверхности в градусах Цельсия, выявления тепловых аномалий.'
        ],[
            'title' => 'Формирование температурных карт',
            'text' => 'Построение карты температур по тепловым каналам КА Landsat-8 производится с целью вычисления значений температур поверхности в градусах Цельсия, выявления тепловых аномалий.'
        ]]
        );
        DB::table('satelite_types')->insert([
            [
                'name' => 'Канопус-В',
            ],
            [
                'name' => 'Ресурс-П',
            ]
        ]);
        DB::table('satelites')->insert([
            [
                'name' => 'Канопус-В 1',
                'description' => 'Lorem ipsum',
                'satelite_type_id' => 1
            ],
            [
                'name' => 'Канопус-В 2',
                'description' => 'Lorem ipsum',
                'satelite_type_id' => 1
            ],
            [
                'name' => 'Ресурс-П 1',
                'description' => 'Lorem ipsum',
                'satelite_type_id' => 2
            ]
        ]);
        
        DB::table('sensors')->insert([
            [
                'name' => 'Сенсор 1',
                'description' => 'Lorem ipsum',
                'satelite_id' => 1
            ],
            [
                'name' => 'Сенсор 2',
                'description' => 'Lorem ipsum',
                'satelite_id' => 2
            ],
            [
                'name' => 'Сенсор 3',
                'description' => 'Lorem ipsum',
                'satelite_id' => 3
            ]
        ]);
        DB::table('spectors')->insert([
            [
                'name' => 'Спектор 1',
                'start_w' => 100,
                'end_w' => 200,
                'sensor_id' => 1
            ],
            [
                'name' => 'Спектор 1',
                'start_w' => 100,
                'end_w' => 200,
                'sensor_id' => 2
            ],
            [
                'name' => 'Спектор 1',
                'start_w' => 100,
                'end_w' => 200,
                'sensor_id' => 3
            ]
        ]);
        DB::table('processing_levels')->insert([
            [
                'name' => 'Уровень 1'
            ],
            [
                'name' => 'Уровень 2'
            ],
            [
                'name' => 'Уровень 3'
            ]
        ]);
        DB::table('dzzs')->insert([
            [
                'name' => 'ДЗЗ 1',
                'date' => now(),
                'round' => 23121,
                'route' => 1,
                'cloudiness' => 10,
                'description' => 'lorem ipsum',
                'processing_level_id' => 1,
                'sensor_id' => 1
            ],
            [
                'name' => 'ДЗЗ 2',
                'date' => now(),
                'round' => 23121,
                'route' => 2,
                'cloudiness' => 20,
                'description' => 'lorem ipsum',
                'processing_level_id' => 2,
                'sensor_id' => 2
            ],
            [
                'name' => 'ДЗЗ 3',
                'date' => now(),
                'round' => 23121,
                'route' => 3,
                'cloudiness' => 30,
                'description' => 'lorem ipsum',
                'processing_level_id' => 3,
                'sensor_id' => 3
            ]
        ]);
        DB::table('file_types')->insert([
            [
                'name' => 'geography'
            ],
            [
                'name' => 'preview'
            ],
            [
                'name' => 'data'
            ]
        ]);
        DB::table('files')->insert([
            [
                'name' => 'снимок1.png',
                'file_type_id' => 2,
                'path' => 'C:\\Users\\Почитаев Андрей\\Desktop\\ASP_NET\\Практика\\MoviesAppWithServices\\ApokBackEnd\\wwwroot\\files\\1_ДЗЗ 1\\снимок1.png',
                'dzz_id' => 1
            ],
            [
                'name' => 'снимок2.png',
                'file_type_id' => 2,
                'path' => 'C:\\Users\\Почитаев Андрей\\Desktop\\ASP_NET\\Практика\\MoviesAppWithServices\\ApokBackEnd\\wwwroot\\files\\2_ДЗЗ 2\\снимок2.png',
                'dzz_id' => 2
            ],
            [
                'name' => 'снимок1.json',
                'file_type_id' => 1,
                'path' => 'C:\\Users\\Почитаев Андрей\\Desktop\\ASP_NET\\Практика\\MoviesAppWithServices\\ApokBackEnd\\wwwroot\\files\\1_ДЗЗ 1\\снимок1.json',
                'dzz_id' => 1
            ],
            [
                'name' => 'снимок2.json',
                'file_type_id' => 2,
                'path' => 'C:\\Users\\Почитаев Андрей\\Desktop\\ASP_NET\\Практика\\MoviesAppWithServices\\ApokBackEnd\\wwwroot\\files\\1_ДЗЗ 1\\снимок2.json',
                'dzz_id' => 2
            ]
        ]);
        DB::table('task_statuses')->insert([
            [
                'name' => 'Статус 1'
            ],
            [
                'name' => 'Статус 2'
            ],
            [
                'name' => 'Статус 3'
            ]
        ]);
        DB::table('tasks')->insert([
            [
                'title' => 'Формирование температурных карт 1',
                'result' => null,
                'dzz_id' => 1,
                'task_status_id' => 1
            ],
            [
                'title' => 'Формирование температурных карт 2',
                'result' => null,
                'dzz_id' => 2,
                'task_status_id' => 2
            ],
            [
                'title' => 'Формирование температурных карт 3',
                'result' => null,
                'dzz_id' => 3,
                'task_status_id' => 3
            ]
        ]);
        DB::table('alerts')->insert([
            [
                'title' => 'Уведомление 1',
                'description' => 'Lorem ipsum'
            ],
            [
                'title' => 'Уведомление 2',
                'description' => 'Lorem ipsum'
            ],
            [
                'title' => 'Уведомление 3',
                'description' => 'Lorem ipsum'
            ]
        ]);

    }
}
