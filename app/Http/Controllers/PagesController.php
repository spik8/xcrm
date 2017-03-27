<?php

namespace App\Http\Controllers;
use App\Postcode;
use App\Record;

class PagesController extends Controller
{

    public function getIndex()
    {
        return view('pages.index');
    }

    public function getChoice()
    {
        return view('pages.choice');
    }

    public function getResearch()
    {
        $miasto = Postcode::select('miasto')->distinct()->get();
        return view('pages.research')->with('miasta',$miasto);
    }

    public static function getCity($city = null)
    {
        $res = str_replace("|","/",$city);
        $rekordy = Postcode::select('idwoj','miasto','adres','kodpocztowy','bisnode','zgody','event','reszta','bisnode_badania','zgody_badania','event_badania','reszta_badania')->where('miasto', '=', $res)->get();
        return $rekordy;
    }

    public function getShipment()
    {
        $keyword=  Input::get('q');
        return view('pages.shipment');
    }

}