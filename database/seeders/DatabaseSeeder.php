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
use App\Models\TaskData;
use App\Models\TaskDataType;
use App\Models\TaskResult;
use App\Models\TaskResultView;
use App\Models\TaskResultViewType;
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
            'name'  => 'Test User',
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
            'name'         => 'Оценка геом',
            'type_id' => 2,
            'path'         => 'files/plans_previews/Оценка геом.png',
        ]);
        File::Create([
            'name'         => 'Оценка лин раз оптика',
            'type_id' => 2,
            'path'         => 'files/plans_previews/Оценка лин раз оптика.png',
        ]);
        File::Create([
            'name'         => 'Оценка лин раз радар',
            'type_id' => 2,
            'path'         => 'files/plans_previews/Оценка лин раз радар.png',
        ]);
        File::Create([
            'name'         => 'Оценка рад хар оптика',
            'type_id' => 2,
            'path'         => 'files/plans_previews/Оценка рад хар оптика.png',
        ]);
        File::Create([
            'name'         => 'Оценка рад хар радар',
            'type_id' => 2,
            'path'         => 'files/plans_previews/Оценка рад хар радар.png',
        ]);

        PlanDataType::Create([
            'name' => 'Rastr',
        ]);
        PlanDataType::Create([
            'name' => 'Vector',
        ]);
        PlanDataType::Create([
            'name' => 'Text',
        ]);

        Plan::Create([
            'title'       => 'Оценка координатно-измерительных характеристик',
            'description' => '
            <p>В ходе выполнения задачи определяется уровень измерительных свойств тестируемых изображений и точность их геодезической привязки на основе набора опорных точек.</p>
            <p>Материалы наблюдения должны иметь геодезическую привязку, с целью их последующего совмещения с набором опорных точек.</p>
            <p>Для оценки геометрического качества изображения необходимо иметь набор опорных точек с известными геодезическими координатами (x_i, y_i). Примерами точек привязки могут служить перекрестки дорог, характерные особенности рек, углы объектов на поверхности земли и другие объекты, которые хорошо видны на изображении, и координаты которых известны пользователю программного комплекса.</p>
            
            <p>
            Выполнение задачи возможно в двух режимах: 
            </p>
            <ul>
                <li>Оценка по опорному снимку. В данном режиме происходит автоматическое определение опорных точек на опорном снимке.</li>
                <li>Оценка по набору опорных точек. В данном режиме в качестве опорных точек выступают центральные точки небольших геопривязанных изображений. </li>
            </ul>
            
            ',
            'excerpt'     => 'Оценка измерительных свойств и точности геодезической привязки материалов наблюдения КА ДЗЗ видимого, ближнего ИК и радиолокационного диапазонов электромагнитного излучения.',
            'preview_id' => 1
        ]);        

        PlanRequirement::Create([
            'title'       => 'Данные',
            'description' => 'мультиспектральные оптические  и радиолокационные материалы с КА ДЗЗ',
            'plan_id'     => 1
        ]);
        PlanRequirement::Create([
            'title'       => 'Сезон съемки',
            'description' => 'с марта по ноябрь включительно',
            'plan_id'     => 1
        ]);
        PlanRequirement::Create([
            'title'       => 'Облачность',
            'description' => 'не более 20%',
            'plan_id'     => 1
        ]);

        PlanData::Create([
            'title'             => 'Опорный снимок',
            'type_id' => 1,
            'plan_id'           => 1
        ]);
        PlanData::Create([
            'title'             => 'Оцениваемый снимок',
            'type_id' => 1,
            'plan_id'           => 1
        ]);
        
        
        Plan::Create([
            'title'       => 'Оценка пространственно-частотных характеристик (оптика)',
            'description' => '
            <p>В ходе выполнения данной задачи осуществляется измерение пространственно-частотных  характеристик по нескольким направлениям:</p>
            <ul>
                <li>Направление под углом 45 градусов к горизонтали.</li>
                <li>Направление по вертикали.</li> 
                <li>Направление под углом -45 градусов к горизонтали.</li>
                <li>Направление по горизонтали.</li>
            </ul>
            <p>Производится расчет следующих характеристик:</p>
            <ul>
                <li>Линейное разрешение оптического снимка, рассчитываемое по каждому направлению в пикселах и метрах.</li>
                <li>Среднеквадратическое отклонение полученных измерений по каждому из направлений.</li>
                <li>Доверительный интервал, которому с вероятностью 90% принадлежит значение оцениваемого параметра.</li>
                <li>Значение частотно-контрастной характеристики на частоте Найквиста.</li>
                <li>Количество использованных для расчетов объектов по каждому направлению.</li>
            </ul>
            <p>Для оценки геометрического качества изображения необходимо иметь набор опорных точек с известными геодезическими координатами (x_i, y_i). Примерами точек привязки могут служить перекрестки дорог, характерные особенности рек, углы объектов на поверхности земли и другие объекты, которые хорошо видны на изображении, и координаты которых известны пользователю программного комплекса.</p>
            <p>
            При оценивании пространственно-частотных характеристик используется информация об условиях получения анализируемого изображения, приводимая в паспорте, сопровождающем анализируемое изображение. Поэтому для получения корректных результатов по оценке данных характеристик в качестве входных данных необходимо загружать паспорт снимка.
            </p>
            <p>
            <i>
            *Примечание: Расчет пространственно-частотных характеристик производится по первому каналу снимка.
            </i>
            </p>
            ',
            'excerpt'     => 'Оценка таких пространственно-частотных характеристик оптических космических снимков, как линейное разрешение на местности в пикселах и метрах, а также частотно-контрастная характеристика на частоте Найквиста.',
            'preview_id' => 2
        ]);        

        PlanRequirement::Create([
            'title'       => 'Данные',
            'description' => 'мультиспектральные оптические материалы с космических аппаратов (КА) ',
            'plan_id'     => 2
        ]);
        PlanRequirement::Create([
            'title'       => 'Сезон съемки',
            'description' => 'любой',
            'plan_id'     => 2
        ]);
        PlanRequirement::Create([
            'title'       => 'Облачность',
            'description' => 'не более 20%',
            'plan_id'     => 2
        ]);

        PlanData::Create([
            'title'             => 'Паспорт снимка',
            'type_id' => 1,
            'plan_id'           => 2
        ]);


        Plan::Create([
            'title'       => 'Оценка пространственно-частотных характеристик (радиолокация)',
            'description' => '
            <p>Реальные характеристики радиолокационной системы оцениваются по снимкам путем статистической обработки радиолокационной информации.</p>
            <p>В ходе выполнения данной задачи осуществляется измерение пространственно-частотных  характеристик по нескольким направлениям:</p>
            <ul>
                <li>Направление под углом 45 градусов к горизонтали.</li>
                <li>Направление по вертикали.</li> 
                <li>Направление под углом -45 градусов к горизонтали.</li>
                <li>Направление по горизонтали.</li>
            </ul>
            <p>
            Производится расчет следующих характеристик:
            </p>
            <ul>
            <li>Линейное разрешение снимка, полученного радаром с синтезированной апертурой. Линейное разрешение рассчитывается по каждому направлению в пикселах и метрах.</li> 
            <li>Среднеквадратичное отклонение полученных измерений по каждому из направлений.</li>
            <li>Доверительный интервал, которому с вероятностью 90% принадлежит значение оцениваемого параметра.</li>
            <li>Средний уровень боковых лепестков в децибелах.</li>
            <li>Количество оцениваемых объектов типа «Точка».</li>
            <li>Количество оцениваемых объектов типа «Край».</li> 
            </ul>
            <p>При оценивании пространственно-частотных характеристик используется информация об условиях получения анализируемого изображения, приводимая в паспорте, сопровождающем анализируемое изображение. Поэтому для получения корректных результатов по оценке данных характеристик в качестве входных данных необходимо загружать паспорт снимка.</p>
            ',
            'excerpt'     => 'Оценка таких пространственно-частотных характеристик радиолокационных космических снимков, как линейное разрешение на местности в пикселах и метрах, а также средний уровень боковых лепестков в децибелах. ',
            'preview_id' => 3
        ]);        

        PlanRequirement::Create([
            'title'       => 'Данные',
            'description' => 'материалы с радиолокационных космических аппаратов',
            'plan_id'     => 3
        ]);
        PlanRequirement::Create([
            'title'       => 'Сезон съемки',
            'description' => 'любой',
            'plan_id'     => 3
        ]);
        

        PlanData::Create([
            'title'             => 'Паспорт радиолокационного снимка',
            'type_id' => 1,
            'plan_id'           => 3
        ]);

        Plan::Create([
            'title'       => 'Оценка спектрорадиометрических характеристик (оптика)',
            'description' => '
            <p>Абсолютная радиометрическая калибровка аппаратуры ДЗЗ позволяет напрямую проводить измерения яркости по снимкам, а также сравнивать снимки с различных космических аппаратов. </p>
            <p>Но перед тем как производить радиометрическую калибровку, необходимо оценить радиометрическое качество космических изображений.</p>
            <p>
            В ходе выполнения данной задачи производится расчет коэффициента отражения по однородной области снимка. Результат вычислений сравнивается со значением коэффициента отражения, измеренным на Земле.
            </p>
            <p>
            Для выполнения данной задачи пользователю необходимо выбрать на снимке, либо подгрузить самостоятельно, однородную область на снимке, по которой будет рассчитан коэффициент отражения, а также необходимо ввести эталонное значение коэффициента отражения для данной области.
            </p>
            <p>
            При расчете коэффициента отражения по снимку используется информация об условиях получения анализируемого изображения, приводимая в паспорте. Поэтому для получения корректных результатов по оценке спектрорадиометрических характеристик в качестве входных данных необходимо загружать паспорт снимка.</p>
            <p><i>*Примечание: Расчет коэффициента отражения производится по первому каналу снимка.</i></p>
            ',
            'excerpt'     => 'Расчет отклонения коэффициента отражения, рассчитанного по снимку, от эталонного значения коэффициента отражения. ',
            'preview_id' => 4
        ]);        

        PlanRequirement::Create([
            'title'       => 'Данные',
            'description' => 'мультиспектральные оптические материалы с космических аппаратов',
            'plan_id'     => 4
        ]);
        PlanRequirement::Create([
            'title'       => 'Сезон съемки',
            'description' => 'любой',
            'plan_id'     => 4
        ]);
        PlanRequirement::Create([
            'title'       => 'Облачность',
            'description' => 'не более 20%',
            'plan_id'     => 4
        ]);

        PlanData::Create([
            'title'             => 'Паспорт снимка с оптического космического аппарата',
            'type_id' => 1,
            'plan_id'           => 4
        ]);
        PlanData::Create([
            'title'             => 'Зона интереса',
            'type_id' => 2,
            'plan_id'           => 4
        ]); 
        PlanData::Create([
            'title'             => 'Коэффициент отражения',
            'type_id' => 3,
            'plan_id'           => 4
        ]);


        Plan::Create([
            'title'       => 'Оценка спектрорадиометрических характеристик (радиолокация)',
            'description' => '
            <p>В ходе выполнения данной задачи производится расчет следующих спектрорадиометрических характеристик:</p>
            <ul>
                <li>Динамический диапазон в децибелах.</li>
                <li>Радиометрическое разрешение снимка в децибелах.</li>
            </ul>
            <p>При расчете спектрорадиометрических характеристик используется информация об условиях получения анализируемого изображения, приводимая в паспорте. Поэтому для получения корректных результатов в качестве входных данных необходимо загружать паспорт снимка.</p>
            ',
            'excerpt'     => 'Расчет таких спектрорадиометрических характеристик как динамический диапазон и радиометрическое разрешение снимка в децибелах. ',
            'preview_id' => 5
        ]);        

        PlanRequirement::Create([
            'title'       => 'Данные',
            'description' => 'материалы с радиолокационных космических аппаратов',
            'plan_id'     => 5
        ]);
        PlanRequirement::Create([
            'title'       => 'Сезон съемки',
            'description' => 'любой',
            'plan_id'     => 5
        ]);


        PlanData::Create([
            'title'             => 'Паспорт радиолокационного снимка',
            'type_id' => 1,
            'plan_id'           => 5
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
            'type_id' => 1
        ]);
        Satelite::Create([
            'name'             => 'Канопус-В 2',
            'description'      => 'Lorem ipsum',
            'type_id' => 1
        ]);
        Satelite::Create([
            'name'             => 'Ресурс-П 1',
            'description'      => 'Lorem ipsum',
            'type_id' => 2
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



        File::Create([
            'name'         => 'снимок1.png',
            'type_id' => 2,
            'path'         => 'files/1_ДЗЗ 1/снимок1.png',
        ]);
        File::Create([
            'name'         => 'снимок2.png',
            'type_id' => 2,
            'path'         => 'files/2_ДЗЗ 2/снимок2.png',
        ]);

        File::Create([
            'name'         => 'a',
            'type_id' => 3,
            'path'         => 'files/1_ДЗЗ 1',
        ]);
        File::Create([
            'name'         => 'a',
            'type_id' => 3,
            'path'         => 'files/2_ДЗЗ 2',
        ]);

        $json1    = json_decode(Storage::get('files/1_ДЗЗ 1/снимок1.json'));
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
            'sensor_id'           => 1,
            'preview_id' => 3,
            'directory_id' => 5
        ]);
        $json2    = json_decode(Storage::get('files/2_ДЗЗ 2/снимок2.json'));
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
            'sensor_id'           => 2,
            'preview_id' => 4,
            'directory_id' => 6
        ]);

        TaskStatus::Create([
            'name' => 'Создана'
        ]);
        TaskStatus::Create([
            'name' => 'Запущена'
        ]);
        TaskStatus::Create([
            'name' => 'Завершена'
        ]);
        TaskStatus::Create([
            'name' => 'Завершена и отправлено уведомление'
        ]);
        TaskStatus::Create([
            'name' => '10'
        ]);
        TaskStatus::Create([
            'name' => '20'
        ]);
        TaskStatus::Create([
            'name' => '30'
        ]);
        TaskStatus::Create([
            'name' => '40'
        ]);
        TaskStatus::Create([
            'name' => '50'
        ]);
        TaskStatus::Create([
            'name' => '60'
        ]);
        TaskStatus::Create([
            'name' => '70'
        ]);
        TaskStatus::Create([
            'name' => '80'
        ]);
        TaskStatus::Create([
            'name' => '90'
        ]);
        

        TaskResult::Create([
            'task_id' => 1
        ]);
        TaskResult::Create([
            'task_id' => 2
        ]);
        TaskResult::Create([
            'task_id' => 3
        ]);

        TaskResultViewType::Create([
            'title' => 'raster'
        ]);
        TaskResultViewType::Create([
            'title' => 'vector'
        ]);



        File::Create([
            'name'                => 'Отчет',
            'type_id'        => 2,
            'path'                => 'files/result/Отчет Мониторниг состояния посевов .jpg',
        ]);
        File::Create([
            'name'                => 'Подложка',
            'type_id'        => 1,
            'path'                => 'files/result/подложка.png',
        ]);
        File::Create([
            'name'                => 'Векторы',
            'type_id'        => 1,
            'path'                => 'files/result/векторы.png',
        ]);
        File::Create([
            'name'           => 'Отчет',
            'type_id'   => 1,
            'path'           => 'files/result/Отчет Мониторниг состояния посевов .jpg',
            'task_result_id' => 1
        ]);
        File::Create([
            'name'           => 'Подложка',
            'type_id'   => 1,
            'path'           => 'files/result/подложка.png',
            'task_result_id' => 1
        ]);
        File::Create([
            'name'           => 'Архив',
            'type_id'   => 3,
            'path'           => 'files/result',
            'task_result_id' => 1
        ]);

        TaskResultView::Create([
            'title'                    => 'Отчет',
            'type_id' => 1,
            'task_result_id'           => 1,
            'preview_id' => 10
        ]);
        $json3    = json_decode(Storage::get('files/result/preview.json'));
        $polygon3 = json_encode(\GeoJson\GeoJson::jsonUnserialize($json3)->getFeatures()[0]->getGeometry()->jsonSerialize());
        TaskResultView::Create([
            'title'                    => 'Подложка',
            'type_id' => 2,
            'geography'                => DB::raw("ST_GeomFromGeoJSON('$polygon3')"),
            'task_result_id'           => 1,
            'preview_id' => 11
        ]);
        TaskResultView::Create([
            'title'                    => 'Векторы',
            'type_id' => 2,
            'geography'                => DB::raw("ST_GeomFromGeoJSON('$polygon3')"),
            'task_result_id'           => 1,
            'preview_id' => 12
        ]);


        Task::Create([
            'title'          => 'Формирование температурных карт 1',
            'plan_id'         => 1,
            'status_id' => 6,
        ]);
        Task::Create([
            'title'          => 'Формирование температурных карт 2',
            'plan_id'         => 1,
            'status_id' => 2,
        ]);
        Task::Create([
            'title'          => 'Формирование температурных карт 3',
            'plan_id'         => 2,
            'status_id' => 10,
        ]);


        TaskDataType::Create([
            'title' => 'Текст',
        ]);
        TaskDataType::Create([
            'title' => 'Снимок',
        ]);
        TaskDataType::Create([
            'title' => 'Вектор',
        ]);

        TaskData::Create([
            'title' => 'Архивный снимок',
            'task_id' => 1,
            'type_id' => 2,
            'file_id' => 3,
            'plan_data_id' => 1
        ]);
        TaskData::Create([
            'title' => 'Актуальный снимок',
            'task_id' => 1,
            'type_id' => 2,
            'file_id' => 4,
            'plan_data_id' => 2
        ]);
        $json3    = json_decode(Storage::get('files/result/preview.json'));
        $polygon3 = json_encode(\GeoJson\GeoJson::jsonUnserialize($json3)->getFeatures()[0]->getGeometry()->jsonSerialize());
        TaskData::Create([
            'title' => 'Зона интереса',
            'task_id' => 1,
            'type_id' => 3,
            'geography' =>  DB::raw("ST_GeomFromGeoJSON('$polygon3')"),
            'plan_data_id' => 3
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
