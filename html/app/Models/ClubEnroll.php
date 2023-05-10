<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use App\Notifications\ClubNotification;
use App\Notifications\ClubEnrollNotification;
use App\Notifications\ClubEnrolledNotification;
use Carbon\CarbonPeriod;

class ClubEnroll extends Model
{
    /**
     * Notifiable: 可以接收通知
     */
    use Notifiable;

    protected $table = 'clubs_students';

    protected static $weekMap = [
        0 => '日',
        1 => '一',
        2 => '二',
        3 => '三',
        4 => '四',
        5 => '五',
        6 => '六',
    ];

    //以下屬性可以批次寫入
    protected $fillable = [
        'section',
        'uuid',
        'club_id',
        'need_lunch',
        'weekdays',
        'identity',
        'email',
        'parent',
        'mobile',
        'accepted',
        'audited_at',
    ];

    //以下屬性隱藏不顯示（toJson 時忽略）
    protected $hidden = [
        'club',
        'students',
    ];

    //以下屬性需進行資料庫欄位格式轉換
    protected $casts = [
        'weekdays' => 'array',
        'accepted' => 'boolean',
        'audited_at' => 'datetime:Y-m-d H:i:s',
    ];

    //以下為透過程式動態產生之屬性
    protected $appends = [
        'mark',
        'weekday',
        'studytime',
        'lunch',
    ];

    //提供學生身份註記字串
    public function getMarkAttribute()
    {
        if ($this->identity == 1) {
            return '安心就學';
        }
        if ($this->identity == 2) {
            return '身心障礙';
        }
        return '一般學生';
    }

    //提供上課日中文字串（包含家長自訂上課日）
    public function getWeekdayAttribute()
    {
        if (!($this->club)) return '';
        $str = '週';
        if ($this->club->self_defined) {
            foreach ($this->weekdays as $d) {
                $str .= self::$weekMap[$d];
            }
        } else {
            $section = $this->club->section($this->section); 
            foreach ($section->weekdays as $d) {
                $str .= self::$weekMap[$d];
            }
        }
        return $str;
    }

    //提供學生上課時間完整中文字串（包含家長自訂上課日）
    public function getStudytimeAttribute()
    {
        $str ='';
        $str .= substr($this->club->section($this->section)->startDate, 0, 10);
        $str .= '～';
        $str .= substr($this->club->section($this->section)->endDate, 0, 10);
        $str .= ' 週';
        if ($this->club->self_defined) {
            foreach ($this->weekdays as $d) {
                $str .= self::$weekMap[$d];
            }
        } else {
            foreach ($this->club->section($this->section)->weekdays as $d) {
                $str .= self::$weekMap[$d];
            }
        }
        $str .= ' ';
        $str .= $this->club->section($this->section)->startTime;
        $str .= '～';
        $str .= $this->club->section($this->section)->endTime;
        return $str;
    }

    //提供學生用餐選擇字串
    public function getLunchAttribute()
    {
        switch ($this->need_lunch) {
            case 1:
                return '葷食';
                break;
            case 2:
                return '素食';
        }
        return '自理';
    }

    //建立社團報名資訊時，若省略學年，則預設為目前學年
    public static function boot()
    {
        parent::boot();
        self::creating(function($model) {
            if (empty($model->section)) {
                $model->section = current_section();
            }
        });
    }

    //取得此報名資訊的學生社團
    public function club()
    {
        return $this->belongsTo('App\Models\Club', 'club_id');
    }

    //取得此報名資訊的社團分類
    public function kind()
    {
        return $this->club->kind;
    }

    //取得此報名資訊的報名學生
    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'uuid');
    }

    //篩選本學年所有的報名資訊，靜態函式
    public static function current()
    {
        return ClubEnroll::where('section', current_section())->get();
    }

    //篩選本學年所有已被錄取的報名資訊，靜態函式
    public static function current_accepted()
    {
        return ClubEnroll::where('section', current_section())->where('accepted', true)->get();
    }

    //篩選指定班級本學年所有的報名資訊，靜態函式
    public static function currentByClass($class_id)
    {
        return ClubEnroll::leftJoin('students', 'clubs_students.uuid', '=', 'students.uuid')
            ->select('clubs_students.*')
            ->where('clubs_students.accepted', true)
            ->where('clubs_students.section', current_section())
            ->where('students.class_id', $class_id)
            ->orderBy('students.seat')
            ->get();
    }

    //篩選本學年所有重複報名的學生 UUID，靜態函式
    public static function repetition()
    {
        return ClubEnroll::select('uuid')
            ->where('section', current_section())
            ->groupBy('uuid')
            ->havingRaw('count(*) > ?', [1])
            ->get();
    }

    //根據學生 UUID、社團編號、學年度，篩選符合的報名資訊，靜態函式
    public static function findBy($uuid = null, $club_id = null, $section = null)
    {
        $query = ClubEnroll::query();
        if ($uuid) {
            $query = $query->where('uuid', $uuid);
        }
        if ($club_id) {
            $query = $query->where('club_id', $club_id);
        }
        if ($section) {
            $query = $query->where('section', $section);
        } else {
            $query = $query->where('section', current_section());
        }
        return $query->first();
    }

    //取得有報名資訊的所有學年，傳回物件集合，靜態函式
    public static function sections()
    {
        return DB::table('clubs_students')->selectRaw('DISTINCT(section)')->orderBy('section', 'desc')->get()->map(function ($item) {
            $sec = $item->section;
            $seme = substr($sec, -1);
            if ($seme == 1) {
                $strseme = '上學期';
            } else {
                $strseme = '下學期';
            }
            return (object) [ 'section' => $sec, 'name' => '第'.substr($sec, 0, -1).'學年'.$strseme ];
        });
    }

    //取得此報名資訊的報名順位
    public function section_order()
    {
        return ClubEnroll::where('club_id', $this->club_id)
            ->where('section', $this->section)
            ->where('created_at', '<', $this->created_at)
            ->count();
    }

    //傳送社團注意事項給報名學生
    public function sendClubNotification($message)
    {
        $this->notify(new ClubNotification($message));
    }

    //傳送報名成功通知信給報名學生
    public function sendClubEnrollNotification()
    {
        $order = $this->section_order() + 1;
        $this->notify(new ClubEnrollNotification($order));
    }

    //傳送錄取通知信給報名學生
    public function sendClubEnrolledNotification()
    {
        $this->notify(new ClubEnrolledNotification);
    }

    //檢查上課時間是否與指定社團有衝突（可傳入家長自訂上課日，或直接檢查社團上課日）
    public function conflict($club, $weekdays = null)
    {
        $old = $this->club->section();
        $new = $club->section();
        $date_period_old = new CarbonPeriod($old->startDate, $old->endDate);
        $date_period_new = new CarbonPeriod($new->startDate, $new->endDate);
        if ($date_period_old->overlaps($date_period_new)) {
            if ($old->self_defined) {
                $weekdays_old = $this->weekdays;
            } else {
                $weekdays_old = $old->weekdays;
            }
            if ($weekdays) {
                $weekdays_new = $weekdays;
            } else {
                $weekdays_new = $new->weekdays;
            }
            if ($weekdays_new) {
                $overlap = array_intersect($weekdays_new, $weekdays_old);
                if (!empty($overlap)) {
                    $time_period_old = new CarbonPeriod($old->startTime, $old->endTime);
                    $time_period_new = new CarbonPeriod($new->startTime, $new->endTime);
                    if ($time_period_old->overlaps($time_period_new)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

}
