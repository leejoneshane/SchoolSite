<?php

namespace App\Imports;

use App\Models\Club;
use App\Models\Unit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClubImport implements ToCollection, WithHeadingRow
{
    use Importable;

    public $kind;

    public function __construct($kind)
    {
        $this->kind = $kind;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (!isset($row['name']) || !isset($row['short'])) {
                return null;
            }
            $unit = Unit::findByName($row['dep']);
            $grades = [];
            for ($i=0; $i<6; $i++) {
                if (substr($row['grade'], $i, 1) == '1') {
                    $grades[] = $i + 1;
                }
            }
            if ($row['week'] == '00000') {
                $self_define = true;
                $weekdays = null;
            } else {
                $self_define = false;
                $weekdays = [];
                for ($i=0; $i<5; $i++) {
                    if (substr($row['week'], $i, 1) == '1') {
                        $weekdays[] = $i + 1;
                    }
                }
            }
            $self_remove = false;
            if (isset($row['remove']) && $row['remove'] == '1') {
                $self_remove = true;
            }
            $has_lunch = false;
            if (isset($row['lunch']) && $row['lunch'] == '1') {
                $has_lunch = true;
            }
            $sdate = str_replace('/', '-', $row['sdate']);
            $edate = str_replace('/', '-', $row['edate']);
            if (strlen($row['stime']) > 5) {
                $stime = substr($row['stime'], 0, 2).':'.substr($row['stime'], 3, 2);
                $etime = substr($row['etime'], 0, 2).':'.substr($row['etime'], 3, 2);
            } else {
                $stime = substr($row['stime'], 0, 2).':'.substr($row['stime'], -2);
                $etime = substr($row['etime'], 0, 2).':'.substr($row['etime'], -2);
            }
            Club::updateOrCreate(
                [
                    'name' => $row['name'],
                ],
                [
                    'short_name' => $row['short'],
                    'kind_id' => $this->kind,
                    'unit_id' => $unit->id,
                    'for_grade' => $grades,
                    'weekdays' => $weekdays,
                    'self_defined' => $self_define,
                    'self_remove' => $self_remove,
                    'has_lunch' => $has_lunch,
                    'stop_enroll' => false,
                    'startDate' => $sdate,
                    'endDate' => $edate,
                    'startTime' => $stime,
                    'endTime' => $etime,
                    'teacher' => $row['teacher'],
                    'location' => $row['place'],
                    'memo' => $row['memo'],
                    'cash' => $row['cash'],
                    'total' => $row['total'],
                    'maximum' => $row['maxnum'],
                ]
            );
        }
    }
}