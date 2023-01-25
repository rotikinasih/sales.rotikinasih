<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        //menampilkan tanggal hari ini
        $day = date('d F Y');

        return Inertia::render('Apps/Dashboard/Index',[
            'hari_ini'      => $day
        ]);
    }
}
