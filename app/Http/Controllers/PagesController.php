<?php

namespace App\Http\Controllers;
use App\Postcode;
use App\Record;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\Types\Integer;
use Response;





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
    public function gererateCSV()
    {
        $dane =session()->get('dane');
        session()->flush();
        return Excel::create('itsolutionstuff_example', function($excel) use ($dane) {
            $excel->sheet('mySheet', function($sheet) use ($dane)
            {
                $sheet->fromArray($dane);
            });
        })->download('csv');

//            $filename = "tweets6.csv";
//            $handle = fopen($filename, 'w+');
//            fputcsv($handle, array('Imie', 'Nazwisko', 'telefon','lock','idbaza', 'idkod'));
//
//            foreach($dane as $row) {
//                fputcsv($handle, array($row['imie'], $row['nazwisko'], $row['telefon'], $row['lock']));
//            }
//            fclose($handle);
//            $headers = array(
//                'Content-Type' => 'text/csv',
//            );

//            return \Response::make($filename, 200, $headers);

    }
    public function storageResearch(Request $request)
    {
        $system = $request['System'];
        $kody = $request['kody'];
        $bisnode = $request['bisnode'];
        $zgody = $request['zgody'];
        $reszta = $request['reszta'];
        $event = $request['event'];
        $test = str_replace('-','',$kody[0]);
        $test = intval($test);
        $tablica = array();
        foreach ($kody as $item)
        {
            $test = str_replace('-','',$item);
            $test = intval($test);

            $rekodyzgody =
                Record::select('imie','nazwisko','telefon','lock','idbaza','idkod')
                    ->where('idkod', '=', $test)
                    ->where('lock', '=',0)
                    ->Where(function($query)
                    {
                        $query->where('idbaza', '=', 8);
                    })
                    ->take($bisnode)->get();
            if(count($rekodyzgody) < $bisnode)
            {
                foreach ($rekodyzgody as $item)
                {
                    array_push($tablica,$item);
                }
                $bisnode = $bisnode - count($rekodyzgody);
            }else break;
        }
        foreach ($rekodyzgody as $item)
        {
            array_push($tablica,$item);
        }
        session()->put('dane',$tablica);
       return session()->get('dane');



//
////        $filename = "tweets6.csv";
////        $handle = fopen($filename, 'w+');
////        fputcsv($handle, array('Imie', 'Nazwisko', 'telefon','lock','idbaza', 'idkod'));
////
////        foreach($tablica as $row) {
////            fputcsv($handle, array($row['imie'], $row['nazwisko'], $row['telefon'], $row['lock']));
////        }
////        fclose($handle);
////        $headers = array(
////            'Content-Type' => 'text/csv',
////        );
////
////        return \Response::make($filename, 200, $headers);
//
//
////
//        return $tablica;
    }
    public function bisnode($ilosc,$kod)
    {


        $rekodybisnode =
            Record::select('imie','nazwisko','telefon','lock','idbaza','idkod')
                ->where('idkod', '=', $kod)
                ->where('lock', '=',0)
                ->Where(function($query)
                {
                    $query->where('idbaza', '=', 8);
                })
                ->take($ilosc)->get();
    }

    public function getShipment()
    {
        $keyword=  Input::get('q');
    }

}