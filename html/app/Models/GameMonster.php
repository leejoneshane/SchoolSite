<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameMonster extends Model
{

    protected $table = 'game_monsters';

    //以下屬性可以批次寫入
    protected $fillable = [
        'name',        //怪物名稱
        'description', //怪物簡介
        'max_hp',      //最大健康值
        'hp',          //目前健康值
        'hit_rate',    //攻擊命中率
        'crit_rate',   //爆擊率，爆擊時攻擊力為基本攻擊力的 2 倍
        'ap',          //基本攻擊力
        'dp',          //基本防禦力
        'sp',          //基本敏捷力
    ];

    //以下屬性隱藏不顯示（toJson 時忽略）
    protected $hidden = [
        'images',
    ];

    //取得此怪物的圖片
    public function images()
    {
        return $this->belongsToMany('App\Models\GameImage', 'game_monsters_images', 'monster_id', 'image_id');
    }

}
