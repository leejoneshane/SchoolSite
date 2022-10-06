<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ClubNotification;
use App\Notifications\ClubEnrollNotification;
use App\Notifications\ClubEnrolledNotification;
use Carbon\CarbonPeriod;

class ClubEnroll extends Model
{
    use Notifiable;

    protected $table = 'students_clubs';

    protected static $weekMap = [
        0 => '日',
        1 => '一',
        2 => '二',
        3 => '三',
        4 => '四',
        5 => '五',
        6 => '六',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'year',
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

    protected $appends = [
		'studytime',
    ];

    public function getStudytimeAttribute()
    {
        $str ='';
        $str .= substr($this->club->startDate, 0, 10);
        $str .= '～';
        $str .= substr($this->club->endDate, 0, 10);
        $str .= ' 每週';
        if ($this->club->self_defined) {
            foreach ($this->weekdays as $d) {
                $str .= self::$weekMap[$d];
            }
        } else {
            foreach ($this->club->weekdays as $d) {
                $str .= self::$weekMap[$d];
            }
        }
        $str .= ' ';
        $str .= $this->club->startTime;
        $str .= '～';
        $str .= $this->club->endTime;
        return $str;
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'need_lunch' => 'boolean',
        'weekdays' => 'array',
        'accepted' => 'boolean',
        'audited_at' => 'datetime:Y-m-d H:i:s',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function($model) {
            if (empty($model->year)) {
                $model->year = self::current_year();
            }
        });
    }

    public static function current_year()
	{
    	if (date('m') > 7) {
        	$year = date('Y') - 1911;
    	} else {
        	$year = date('Y') - 1912;
    	}
    	return $year;
	}

    public function club()
    {
        return $this->belongsTo('App\Models\Club', 'club_id');
    }

    public function kind()
    {
        return $this->club->kind;
    }

    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'uuid');
    }

    public static function current()
    {
        return ClubEnroll::where('year', CLubEnroll::current_year())->get();
    }

    public static function repetition()
    {
        return ClubEnroll::select('uuid')
            ->where('year', CLubEnroll::current_year())
            ->groupBy('uuid')
            ->havingRaw('count(*) > ?', [1])
            ->get();
    }

    public static function findBy($uuid = null, $club_id = null, $year = null)
    {
        $query = ClubEnroll::query();
        if ($uuid) {
            $query = $query->where('uuid', $uuid);
        }
        if ($club_id) {
            $query = $query->where('club_id', $club_id);
        }
        if ($year) {
            $query = $query->where('year', $year);
        } else {
            $query = $query->where('year', CLubEnroll::current_year());
        }
        return $query->first();
    }

    public function year_order()
    {
        return ClubEnroll::where('club_id', $this->club_id)
            ->where('year', $this->year)
            ->where('created_at', '<', $this->created_at)
            ->count();
    }

    public function sendClubNotification($message)
    {
        $this->notify(new ClubNotification($message));
    }

    public function sendClubEnrollNotification()
    {
        $order = $this->year_order() + 1;
        $this->notify(new ClubEnrollNotification($order));
    }

    public function sendClubEnrolledNotification()
    {
        $this->notify(new ClubEnrolledNotification);
    }

    public function conflict($club, $weekdays = null)
    {
        $old = $this->club;
        $date_period_old = new CarbonPeriod($old->startDate, $old->endDate);
        $date_period_new = new CarbonPeriod($club->startDate, $club->endDate);
        if ($date_period_old->overlaps($date_period_new)) {
            if ($old->self_defined) {
                $weekdays_old = $this->weekdays;
            } else {
                $weekdays_old = $old->weekdays;
            }
            if ($weekdays) {
                $weekdays_new = $weekdays;
            } else {
                $weekdays_new = $club->weekdays;
            }
            $overlap = array_intersect($weekdays_new, $weekdays_old);
            if (!empty($overlap)) {
                $time_period_old = new CarbonPeriod($old->startTime, $old->endTime);
                $time_period_new = new CarbonPeriod($club->startTime, $club->endTime);
                if ($time_period_old->overlaps($time_period_new)) {
                    return true;
                }
            }
        }
        return false;
    }

}
