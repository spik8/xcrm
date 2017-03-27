@extends('main')
@section('style')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.1/css/select.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>
        #ilosc
        {
            margin-bottom: 0px;
        }
        #wybor
        {
            float: right;
            margin-bottom: 20px;
            margin-top: 10px;
        }
        #loader {
            font-size: 10px;
            margin: 50px auto;
            text-indent: -9999em;
            width: 11em;
            height: 11em;
            border-radius: 50%;
            background: #ffffff;
            background: -moz-linear-gradient(left, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
            background: -webkit-linear-gradient(left, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
            background: -o-linear-gradient(left, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
            background: -ms-linear-gradient(left, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
            background: linear-gradient(to right, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
            position: relative;
            -webkit-animation: load3 1.4s infinite linear;
            animation: load3 1.4s infinite linear;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
        }
        #loader:before {
            width: 50%;
            height: 50%;
            background: #ffffff;
            border-radius: 100% 0 0 0;
            position: absolute;
            top: 0;
            left: 0;
            content: '';
        }
        #loader:after {
            background: #0dc5c1;
            width: 75%;
            height: 75%;
            border-radius: 50%;
            content: '';
            margin: auto;
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }
        @-webkit-keyframes load3 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @keyframes load3 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>



@endsection

@section('content')


    <div id="loader"></div>



    <table id="ilosc" class="table table-striped table-bordered" cellspacing="0" width="50%">
        <thead>
        <th></th>
        <th>BisNode</th>
        <th>Zgody</th>
        <th>Event</th>
        <th>Reszta</th>
        <th>Suma</th>
        </thead>
        <tr id="znalezione">
            <td>Zlanezionych:</td>
            <td id="bznalezionych">0/0</td>
            <td id="zznalezionych">0/0</td>
            <td id="eznalezionych">0/0</td>
            <td id="rznalezionych">0/0</td>
            <td id="sumaznalezionych">0/0</td>
        </tr>
        <tr id="liczba">
            <td>Liczba:</td>
            <td><input type="number" id="bliczba" value="0" class="form-control-dane"/></td>
            <td><input type="number" id="zliczba" value="0" class="form-control-dane"/></td>
            <td><input type="number" id="eliczba" value="0" class="form-control-dane"/></td>
            <td><input type="number" id="rliczba" value="0" class="form-control-dane"/></td>
            <td id="sumaliczba">0</td>
        </tr>
    </table>

    <div id="wybor">
        <form role="form" class="form-inline">
            <div class="form-group">
                <label for="selectSystem">Wybierz system:</label>
                <select id="selectSystem" class="form-control selectWidth">
                    <option value="0">Systell</option>
                    <option value="1">PBX</option>
                </select>
            </div>
            <div class="btn-group">
                <button id='pobierz' type="button" class="btn btn-primary">Pobierz</button>
            </div>
        </form>
    </div>


    <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Województwo</th>
            <th>Miasto</th>
            <th>Adres</th>
            <th>Kod</th>
            <th>BisNode</th>
            <th>Zgody</th>
            <th>Event</th>
            <th>Reszta</th>
            <th><input type="checkbox" name="select_all" value="0" id="example-select-all"></th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@endsection













@section('script')
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <script>
        var arr = new Array();
        var source = [];
        var miasta = [];
        var tablicakodowpocztowych = [];
        var badania;
        var szukana = ""; // wartosc z pola szukaj
        miasta = <?php echo json_encode($miasta) ?>;
        var availableTags = [];
        availableTags = [];
        for(var i=0;i<miasta.length;i++)
        {
            availableTags.push(miasta[i]['miasto']);
        }
        var klik = 0;
        //DANE Z BAZY Całość
        var sumabis = 0;
        var sumazg = 0;
        var sumaev = 0;
        var sumaresz = 0;
        var sumacalosci = 0;
        //DANE Z BAZY Badania
        var bisbadania = 0;
        var zgodybadania = 0;
        var eventbadania = 0;
        var resztabadania = 0;
        var sumabadania = 0;
        // dane do pobrania
        var liczbabisnode = 0;
        var liczbazgody = 0;
        var liczbaevent = 0;
        var liczbareszy = 0;
        var liczbacalosci = 0;
        $(document).ready(function() {
//            $("#bliczba").html("0");
//            $("#rliczba").html("0");
//            $("#eliczba").html("0");
//            $("#zliczba").html("0");
            $("#sumaliczba").html("0");
            $("#bznalezionych").html("0/0");
            $("#zznalezionych").html("0/0");
            $("#eznalezionych").html("0/0");
            $("#rznalezionych").html("0/0");
            $("#sumaznalezionych").html("0/0");
        });
        function wyszukaj() { // wyszukaj klawisz
            odznaczenie();
            szukana = $('.dataTables_filter input').val(); // zapis wyszukiwania z pola;
            var res = szukana.replace("/", "|"); // zmana / na I aby nie było przekierowania
            $.get('/getCity/'+res, function(data) { // wywołanie metody z kontrolera
                source = data; // zapisanie zwroconych danych
                var table = $('#example').DataTable(); // wskaznik na tabele
                table.clear().draw();
                var table_rows = ""; // zerowanie całego kodu html
                var napis = ""; // zerwoanie wierwsza
                badania = new Array(source.length);
                for(var i=0;i<source.length;i++)
                {
                    napis = '<tr><td>'+source[i]['idwoj']+'</td><td>'+source[i]['miasto']+'</td><td>'+source[i]['adres']+'</td><td>'+source[i]['kodpocztowy']+'</td><td>'+source[i]['bisnode']+'</td><td>'+source[i]['zgody']+'</td><td>'+source[i]['event']+'</td><td>'+source[i]['reszta']+'</td><td><input type="checkbox"  value='+i+' class="checkboxselect"/></td></tr>';
                    table_rows +=napis; // połączenie wszystkiego iteracyjnie
                    badania[i] = new Array(4);
                    badania[i][0] = source[i]['bisnode_badania'];
                    badania[i][1] = source[i]['zgody_badania'];
                    badania[i][2] = source[i]['event_badania'];
                    badania[i][3] = source[i]['reszta_badania'];
                }
                table.rows.add($(table_rows)).draw(); // rysowanie tebeli na jeden raz, optymalnie niz pojedynczo
                $('.dataTables_filter input').val(szukana); // aby nie znikl wynik wyszukiwania w polu wyszukaj
            });
        }
        function ZerujDane() {
            //całość
            sumabis = 0;
            sumazg = 0;
            sumaev = 0;
            sumaresz = 0;
            sumacalosci = 0;
            //badania
            bisbadania = 0;
            zgodybadania = 0;
            eventbadania = 0;
            resztabadania = 0;
            sumabadania = 0;
            //dopobrania
            liczbabisnode = 0;
            liczbazgody = 0;
            liczbaevent = 0;
            liczbareszy = 0;
            liczbacalosci = 0;

            $("#bliczba").val(0);
            $("#rliczba").val(0);
            $("#eliczba").val(0);
            $("#zliczba").val(0);
            $("#sumaliczba").html(0);


            while (tablicakodowpocztowych.length > 0) {
                tablicakodowpocztowych.pop();
            }

        }
        function CzytajPola() {
            liczbabisnode = $("#bliczba").val();
            liczbazgody = $("#zliczba").val();
            liczbaevent = $("#eliczba").val();
            liczbareszy = $("#rliczba").val();
            liczbacalosci = parseInt(liczbabisnode) + parseInt(liczbazgody) + parseInt(liczbaevent) + parseInt(liczbareszy);
            $("#sumaliczba").html(liczbacalosci);
        }
        $(document).ready(function() {
            $('#example').DataTable( {
                    "language": {
                        "processing":     "Przetwarzanie...",
                        "search":         "",
                        "lengthMenu":     "Pokaż _MENU_ pozycji",
                        "info":           "Pozycje od _START_ do _END_ z _TOTAL_ łącznie",
                        "infoEmpty":      "Pozycji 0 z 0 dostępnych",
                        "infoFiltered":   "(filtrowanie spośród _MAX_ dostępnych pozycji)",
                        "infoPostFix":    "",
                        "loadingRecords": "Wczytywanie...",
                        "zeroRecords":    "Nie znaleziono pasujących pozycji",
                        "emptyTable":     "Brak danych",
                        "paginate": {
                            "first":      "Pierwsza",
                            "previous":   "Poprzednia",
                            "next":       "Następna",
                            "last":       "Ostatnia"
                        },
                        "aria": {
                            "sortAscending": ": aktywuj, by posortować kolumnę rosnąco",
                            "sortDescending": ": aktywuj, by posortować kolumnę malejąco"
                        }
                    },
                    "columnDefs": [
                        {
                            "searchable": false, "targets":[0,2,3,4,5,6,7,8],
                            "orderable": false, "targets": [0,8]}
                    ],
                    deferRender:    true,
                    scrollY:        250,
                    "bPaginate": false,
                    scrollCollapse: true,
                    scroller:       true,
                    //"sDom": '<"topleft"f>rt<"bottom"lp><"clear">'
                    dom: 'l<"toolbar">frtip',
                    initComplete: function(){
                        $("div.toolbar").html('<button type="button" id="any_button" style="float: right;" onclick="wyszukaj()">Szukaj</button>');
                    }
                }
            );
            $( function() { // podpowiadanie fraz w wyszukiwarce
                $( "#example_filter input" ).autocomplete({
                    source: function(req, response) {// zrodło danych
                        var results = $.ui.autocomplete.filter(availableTags, req.term); // ustawinie zrodla danych
                        response(results.slice(0, 10));//wyswietlanie tylko 10 wyszukan danej frazy
                    }
                });
            });
        });



        $('#example tbody').on('click',':checkbox', function () { // po kliknięciu w jakiś checkbox
            var table = $('#example').DataTable();
            var kolumna = $(this).closest('tr');
            var wartosccheckoxa = $(this).val();
            var nazwa = kolumna.hasClass('selected');
            ZerujDane();
            if(nazwa)
            {
                kolumna.removeClass('selected');

            }
            else
            {
                kolumna.addClass('selected');
            }
            var dane = table.rows('.selected').data(); // znalezienie wszystkich zanaczonych elementow

            var selected = [];
            $('#example tbody input:checked').each(function() {
                selected.push($(this).attr('value'));
            });

            var indeks = 0;
            for(var i=0 ;i<selected.length;i++)
            {
                indeks = parseInt(selected[i]);
                bisbadania += badania[indeks][0];
                zgodybadania += badania[indeks][1];
                eventbadania += badania[indeks][2];
                resztabadania += badania[indeks][3];
                sumabadania = bisbadania + zgodybadania+ eventbadania+resztabadania;
            }


            for (var i = 0; i < dane.length; i++) // sumowanie
            {   //Całość
                sumabis += parseInt(dane[i][4]);
                sumazg += parseInt(dane[i][5]);
                sumaev += parseInt(dane[i][6]);
                sumaresz += parseInt(dane[i][7]);
                tablicakodowpocztowych.push(dane[i][3]);
                sumacalosci = sumabis + sumazg + sumaev + sumaresz;
            }
            // wyswietlenie łączne
            $("#bznalezionych").html(bisbadania +"/" + sumabis);
            $("#zznalezionych").html(zgodybadania + "/"+ sumazg);
            $("#eznalezionych").html(eventbadania + "/" + sumaev);
            $("#rznalezionych").html(resztabadania + "/"+ sumaresz);
            $("#sumaznalezionych").html(sumabadania + "/" + sumacalosci);

        });
        $(document).ready(function() {
            var table = $('#example').DataTable();
            $('.dataTables_filter input').unbind().keyup(function (e) { // usunięcie danych po wpisanu frazy w wszysukaj
                odznaczenie();
                var value = $(this).val();
                szukana = value;
                table.clear().draw();
                $('.dataTables_filter input').val(szukana);
            });
        });
        function odznaczenie() {
            var table = $('#example').DataTable();
            klik = 0;// zerowanie kliknięcia
            $('.selected').removeClass('selected'); // usuniecie zaznaczenia
            $('#example input[type=checkbox]').attr('checked',false);
            $('#example-select-all').attr('checked',false);
            var dane = table.rows('.selected').data(); // zerowanie
            ZerujDane();
            for (var i = 0; i < dane.length; i++) // sumowanie
            {
                sumabis += parseInt(dane[i][4]);
                sumazg += parseInt(dane[i][5]);
                sumaev += parseInt(dane[i][6]);
                sumaresz += parseInt(dane[i][7]);
                sumacalosci = sumabis + sumazg + sumaev + sumaresz;
            }
            // wyswietlenie
            $("#bznalezionych").html("0/" + sumabis);
            $("#zznalezionych").html("0/" + sumazg);
            $("#eznalezionych").html("0/" + sumaev);
            $("#rznalezionych").html("0/" + sumaresz);
            $("#sumaznalezionych").html("0/" + sumacalosci);
        }



        $(document).ready(function() {
            $('#example-select-all').on('click', function () {
                var table = $('#example').DataTable();
                // Get all rows with search applied
                var rows = table.rows({'search': 'applied'}).nodes();
                // Check/uncheck checkboxes for all rows in the table
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
                if(klik == 0) {
                    table.rows( { page: 'current' } ).nodes().to$().addClass( 'selected' );
                    klik = 1;
                }else {
                    table.rows( { page: 'current' } ).nodes().to$().removeClass( 'selected' );
                    klik = 0;
                }
                $('.dataTables_filter input').val(szukana);
                /////////FUNKCJA//////////////
                var dane = table.rows('.selected').data(); // znalezienie wszystkich zanaczonych elementow
                ZerujDane();
                for (var i = 0; i < dane.length; i++) // sumowanie
                {   //Całość
                    sumabis += parseInt(dane[i][4]);
                    sumazg += parseInt(dane[i][5]);
                    sumaev += parseInt(dane[i][6]);
                    sumaresz += parseInt(dane[i][7]);
                    sumacalosci = sumabis + sumazg + sumaev + sumaresz;
                    tablicakodowpocztowych.push(dane[i][3]);
                    //Badania
                    bisbadania += parseInt(badania[i][0]);
                    zgodybadania  += parseInt(badania[i][1]);
                    eventbadania+= parseInt(badania[i][2]);
                    resztabadania += parseInt(badania[i][3]);
                    sumabadania = bisbadania + zgodybadania + eventbadania + resztabadania;

                }
                // wyswietlenie
                $("#bznalezionych").html(bisbadania +"/" + sumabis);
                $("#zznalezionych").html(zgodybadania + "/"+ sumazg);
                $("#eznalezionych").html(eventbadania + "/" + sumaev);
                $("#rznalezionych").html(resztabadania + "/"+ sumaresz);
                $("#sumaznalezionych").html(sumabadania + "/" + sumacalosci);
                ///////FUNKCJA Koniec //////////////
            });
            $('#example tbody').on('click', 'tr', function () { // reakcja na klikniece wierszu w tabeli z danymi
                if (event.target.type !== 'checkbox') { // zmiana koloru podswietlnia
                    $(':checkbox', this).trigger('click');
                }
            });
        });






        $(document).ready(function() {
            //  Wpisywanie Danych  //
            $("#bliczba").bind("change paste keyup", function () {
                var liczba = $(this).val();
                if (!parseInt(liczba)) {
                    $(this).val("0");
                }
                else {
                    liczba = parseInt(liczba);
                    if(liczba < 0)
                    {
                        liczba = 0;
                    }
                    if(liczba > bisbadania)
                    {
                        liczba = bisbadania;
                    }
                    $(this).val(liczba);
                }
                CzytajPola();
            });
            $("#eliczba").bind("change paste keyup", function () {
                var liczba = $(this).val();
                if (!parseInt(liczba)) {
                    $(this).val("0");
                }
                else {
                    liczba = parseInt(liczba);
                    if(liczba < 0)
                    {
                        liczba = 0;
                    }
                    if(liczba > eventbadania)
                    {
                        liczba = eventbadania;
                    }
                    $(this).val(liczba);
                }
                CzytajPola();
            });
            $("#zliczba").bind("change paste keyup", function () {
                var liczba = $(this).val();
                if (!parseInt(liczba)) {
                    $(this).val("0");
                }
                else {
                    liczba = parseInt(liczba);
                    if(liczba < 0)
                    {
                        liczba = 0;
                    }
                    if(liczba > zgodybadania)
                    {
                        liczba = zgodybadania;
                    }
                    $(this).val(liczba);
                }
                CzytajPola();
            });
            $("#rliczba").bind("change paste keyup", function () {
                var liczba = $(this).val();
                if (!parseInt(liczba)) {
                    $(this).val("0");
                }
                else {
                    liczba = parseInt(liczba);
                    if(liczba < 0)
                    {
                        liczba = 0;
                    }
                    if(liczba > resztabadania)
                    {
                        liczba = resztabadania;
                    }
                    $(this).val(liczba);
                }
                CzytajPola();
            });
        });


        $("#pobierz").on("click",function(e){
            if(liczbacalosci > 3000 || liczbacalosci  < 1)
            {
                alert("Za Duzo");
            }else {
                if(liczbabisnode > bisbadania)
                {
                    alert("Za Duzo");
                }else if(liczbaevent > eventbadania)
                {
                    alert("Za Duzo");
                }else if(liczbareszy > resztabadania)
                {
                    alert("Za Duzo");
                }else if(liczbazgody > zgodybadania)
                {
                    alert("Za Duzo");
                }else
                {
                    var system = $('#selectSystem').val();
                    document.getElementById("loader").style.display = "block";  // show the loading message.
                    var tablica;
                    $.ajax({
                        type: "GET",
                        url: '/storageResearch',
                        data: {
                            "System": system,
                            "kody": tablicakodowpocztowych,
                            "bisnode": liczbabisnode,
                            "zgody": liczbazgody,
                            "reszta": liczbareszy,
                            "event": liczbaevent
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                           tablica = response;
                           console.log(tablica);
                            window.location="{{URL::to('gererateCSV')}}";
                           document.getElementById("loader").style.display = "none";
                        }
                    });
                }
            }
        });
    </script>
@endsection