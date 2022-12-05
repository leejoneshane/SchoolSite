<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unit;
use App\Models\Role;
use App\Models\Grade;
use App\Models\Domain;
use App\Models\Teacher;
use App\Models\OrganizeSettings;
use App\Models\OrganizeSurvey;
use App\Models\OrganizeVacancy;
use App\Models\Seniority;


class OrganizeController extends Controller
{

    public function index($year = null)
    {
        $user = Auth::user();
        if ($user->user_type == 'Student') {
            return redirect()->route('home')->with('error', '您沒有權限使用此功能！');
        }
        $teacher = Teacher::find($user->uuid);
        $reserve = DB::table('organize_reserved')->where('syear', current_year())->where('uuid', $user->uuid)->first();
        $reserved = ($reserve) ? true : false;
        $current = current_year();
        if (!$year) $year = $current;
        $years = OrganizeSettings::years();
        if (!in_array($current, $years)) $years[] = $current;
        rsort($years);
        $flow = OrganizeSettings::where('syear', $year)->first();
        $stage1 = OrganizeVacancy::current_stage(1);
        $stage2 = OrganizeVacancy::current_stage(2);
        return view('app.organize', ['current' => $current, 'year' => $year, 'years' => $years, 'flow' => $flow, 'reserved' => $reserved, 'teacher' => $teacher, 'stage1' => $stage1, 'stage2' => $stage2]);
    }

    public function survey(Request $request, $uuid)
    {
        $teacher = Teacher::find($uuid);
        $flow = OrganizeSettings::current();
        if ($flow->onSurvey()) {
            $age = ($teacher->birthdate->format('md') > date("md")) ? date("Y") - $teacher->birthdate->format('Y') - 1 : date("Y") - $teacher->birthdate->format('Y');
            OrganizeSurvey::UpdateOrCreate([
                'syear' => current_year(),
                'uuid' => $teacher->uuid,
            ],[
                'age' => $age,
                'exprience' => $request->input('exp'),
                'edu_level' => $request->input('edu_level'),
                'edu_school' => $request->input('edu_school'),
                'edu_division' => $request->input('edu_division'),
                'score' => $request->input('total'),
            ]);
        }
        if ($flow->onFirstStage()) {
            if ($request->has('special')) {
                OrganizeSurvey::where('syear', current_year())
                ->where('uuid', $teacher->uuid)
                ->update([
                    'admin1' => $request->input('admin1'),
                    'admin2' => $request->input('admin2'),
                    'admin3' => $request->input('admin3'),
                    'special' => $request->input('special'),
                ]);
            } else {
                OrganizeSurvey::where('syear', current_year())
                ->where('uuid', $teacher->uuid)
                ->update([
                    'admin1' => $request->input('admin1'),
                    'admin2' => $request->input('admin2'),
                    'admin3' => $request->input('admin3'),
                ]);
            }
        }
        if ($flow->onSecondStage()) {
            if ($request->has('special')) {
                OrganizeSurvey::where('syear', current_year())
                ->where('uuid', $teacher->uuid)
                ->update([
                    'special' => $request->input('special'),
                    'teach1' => $request->input('teach1'),
                    'teach2' => $request->input('teach1'),
                    'teach3' => $request->input('teach1'),
                    'teach4' => $request->input('teach1'),
                    'teach5' => $request->input('teach1'),
                    'teach6' => $request->input('teach1'),
                    'grade' => $request->input('grade'),
                    'overcome' => $request->input('overcome'),
                ]);
            } else {
                OrganizeSurvey::where('syear', current_year())
                ->where('uuid', $teacher->uuid)
                ->update([
                    'teach1' => $request->input('teach1'),
                    'teach2' => $request->input('teach1'),
                    'teach3' => $request->input('teach1'),
                    'teach4' => $request->input('teach1'),
                    'teach5' => $request->input('teach1'),
                    'teach6' => $request->input('teach1'),
                    'grade' => $request->input('grade'),
                    'overcome' => $request->input('overcome'),
                ]);
            }
        }
        return redirect()->route('organize')->with('success', '已為您儲存職務意願表，截止日前您仍然可以修改！');
    }

    public function setting()
    {
        $user = User::find(Auth::user()->id);
        $manager = $user->hasPermission('club.manager');
        if ($user->is_admin || $manager) {
            $seme = current_between_date();
            $settings = OrganizeSettings::current();
            return view('app.organize_setting', ['seme' => $seme, 'settings' => $settings]);
        } else {
            return redirect()->route('home')->with('error', '您沒有權限使用此功能！');
        }
    }

    public function saveSettings(Request $request)
    {
        $survey_at = $request->input('survey_at');
        $first_stage = $request->input('first_stage');
        $pause_at = $request->input('pause_at');
        $second_stage = $request->input('second_stage');
        $close_at = $request->input('close_at');
        OrganizeSettings::UpdateOrCreate([
            'syear' => current_year(),
        ],[
            'survey_at' => $survey_at,
            'first_stage' => $first_stage,
            'pause_at' => $pause_at,
            'second_stage' => $second_stage,
            'close_at' => $close_at,
        ]);
        return redirect()->route('organize.setting')->with('success', '職務編排流程設定完成！');
    }

    public function vacancy()
    {
        $user = User::find(Auth::user()->id);
        $manager = $user->hasPermission('club.manager');
        if ($user->is_admin || $manager) {
            if (OrganizeVacancy::current()->isEmpty()) {
                $this->vacancy_init();
            }
            $admins = OrganizeVacancy::current_type('admin');
            $tutors = OrganizeVacancy::current_type('tutor');
            $domains = OrganizeVacancy::current_type('domain');
            return view('app.organize_vacancy', ['admins' => $admins, 'tutors' => $tutors, 'domains' => $domains]);
        } else {
            return redirect()->route('home')->with('error', '您沒有權限使用此功能！');
        }
    }

    public function reset()
    {
        $user = User::find(Auth::user()->id);
        $manager = $user->hasPermission('club.manager');
        if ($user->is_admin || $manager) {
            DB::table('organize_original')->where('syear', current_year())->delete();
            DB::table('organize_reserved')->where('syear', current_year())->delete();
            DB::table('organize_swap')->where('syear', current_year())->delete();
            DB::table('organize_assign')->where('syear', current_year())->delete();
            OrganizeVacancy::where('syear', current_year())->delete();
            $this->vacancy_init();
            return redirect()->route('organize.vacancy');
        } else {
            return redirect()->route('home')->with('error', '您沒有權限使用此功能！');
        }
    }

    private function vacancy_init() {
        $managers = Role::director();
        foreach ($managers as $a) {
            $teachers = Teacher::where('role_id', $a->id)->get();
            if ($teachers->count() > 0) {
                $v = OrganizeVacancy::create([
                    'type' => 'admin',
                    'role_id' => $a->id,
                    'name' => $a->name,
                    'stage' => 1,
                    'shortfall' => $teachers->count(),
                    'filled' => $teachers->count(),
                    'assigned' => 0,
                ]);
                foreach ($teachers as $t) {
                    DB::table('organize_original')->insert([
                        'syear' => current_year(),
                        'uuid' => $t->uuid,
                        'vacancy_id' => $v->id,
                    ]);
                    DB::table('organize_reserved')->insert([
                        'syear' => current_year(),
                        'uuid' => $t->uuid,
                        'vacancy_id' => $v->id,
                    ]);
                }
            }
        }
        $admins = Role::organize();
        foreach ($admins as $a) {
            $teachers = Teacher::where('role_id', $a->id)->get();
            if ($teachers->count() > 0) {
                $v = OrganizeVacancy::create([
                    'type' => 'admin',
                    'unit_id' => $a->unit->uplevel()->id,
                    'role_id' => $a->id,
                    'name' => $a->name,
                    'stage' => 1,
                    'shortfall' => $teachers->count(),
                    'filled' => $teachers->count(),
                    'assigned' => 0,
                ]);
                foreach ($teachers as $t) {
                    DB::table('organize_original')->insert([
                        'syear' => current_year(),
                        'uuid' => $t->uuid,
                        'vacancy_id' => $v->id,
                    ]);
                    DB::table('organize_reserved')->insert([
                        'syear' => current_year(),
                        'uuid' => $t->uuid,
                        'vacancy_id' => $v->id,
                    ]);
                }
            }
        }
        $grades = Grade::all();
        foreach ($grades as $a) {
            $v = OrganizeVacancy::create([
                'type' => 'tutor',
                'grade_id' => $a->id,
                'name' => $a->name,
                'stage' => 2,
                'shortfall' => $a->classrooms->count(),
                'filled' => $a->classrooms->count(),
                'assigned' => 0,
            ]);
            foreach ($a->classrooms as $c) {
                $t = $c->tutors->first();
                DB::table('organize_original')->insert([
                    'syear' => current_year(),
                    'uuid' => $t->uuid,
                    'vacancy_id' => $v->id,
                ]);
                DB::table('organize_reserved')->insert([
                    'syear' => current_year(),
                    'uuid' => $t->uuid,
                    'vacancy_id' => $v->id,
                ]);
            }
        }
        $domains = Domain::all();
        foreach ($domains as $a) {
            if ($a->teachers->count()) {
                $v = OrganizeVacancy::create([
                    'type' => 'domain',
                    'domain_id' => $a->id,
                    'name' => $a->name,
                    'stage' => 2,
                    'shortfall' => $a->teachers->count(),
                    'filled' => $a->teachers->count(),
                    'assigned' => 0,
                ]);
                foreach ($a->teachers as $t) {
                    DB::table('organize_original')->insert([
                        'syear' => current_year(),
                        'uuid' => $t->uuid,
                        'vacancy_id' => $v->id,
                    ]);
                    DB::table('organize_reserved')->insert([
                        'syear' => current_year(),
                        'uuid' => $t->uuid,
                        'vacancy_id' => $v->id,
                    ]);
                }
            }
        }
    }

    public function stage(Request $request)
    {
        $vacancy_id = $request->input('vid');
        $stage = $request->input('stage');
        $vacancy = OrganizeVacancy::find($vacancy_id);
        $vacancy->stage = $stage;
        $vacancy->save();
        return response()->json($vacancy);
    }

    public function special(Request $request)
    {
        $vacancy_id = $request->input('vid');
        $special = $request->boolean('special');
        $vacancy = OrganizeVacancy::find($vacancy_id);
        $vacancy->special = $special;
        $vacancy->save();
        return response()->json($vacancy);
    }

    public function shortfall(Request $request)
    {
        $vacancy_id = $request->input('vid');
        $shortfall = (integer) $request->input('shortfall');
        $vacancy = OrganizeVacancy::find($vacancy_id);
        $vacancy->shortfall = $shortfall;
        $vacancy->save();
        return response()->json($vacancy);
    }

    public function release(Request $request)
    {
        $vacancy_id = $request->input('vid');
        $uuid = $request->input('uuid');
        $vacancy = OrganizeVacancy::find($vacancy_id);
        $vacancy->filled -= 1;
        $vacancy->save();
        DB::table('organize_reserved')
            ->where('syear', current_year())
            ->where('vacancy_id', $vacancy_id)
            ->where('uuid', $uuid)
            ->delete();
        return response()->json('success');
    }

    public function reserve(Request $request)
    {
        $vacancy_id = $request->input('vid');
        $uuid = $request->input('uuid');
        $vacancy = OrganizeVacancy::find($vacancy_id);
        $vacancy->filled += 1;
        $vacancy->save();
        DB::table('organize_reserved')->Insert([
            'syear' => current_year(),
            'vacancy_id' => $vacancy_id,
            'uuid' => $uuid,
        ]);
        return response()->json('success');
    }

    public function listVacancy($year = null)
    {
        $user = Auth::user();
        if ($user->user_type == 'Student') {
            return redirect()->route('home')->with('error', '您沒有權限使用此功能！');
        }
        $current = current_year();
        if (!$year) $year = $current;
        $years = OrganizeSettings::years();
        if (!in_array($current, $years)) $years[] = $current;
        rsort($years);
        $flow = OrganizeSettings::current();
        if ($flow && $flow->onPeriod()) {
            $vacancys = OrganizeVacancy::all();
            return view('app.organize_listvacancy', ['current' => $current, 'year' => $year, 'years' => $years, 'vacancys' => $vacancys]);    
        } else {
            return redirect()->route('organize')->with('error', '職務編排意願調查尚未開始或已經結束，因此無法瀏覽職缺一覽表！');
        }
    }

}
