<?php

namespace App\Traits;

trait TracksUserShop
{
    protected static function bootTracksUserShop()
    {
        static::creating(function ($model) {

            $req = request();

            $model->user_id = $req->auth_user_id ?? null;
            $model->shop_id = $req->auth_shop_id ?? null;
        });

        static::updating(function ($model) {

            $req = request();

            // reuse existing column instead of non-existing one
            $model->user_id = $req->auth_user_id ?? null;
        });
    }
}
