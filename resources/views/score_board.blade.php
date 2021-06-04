<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Laravel</title>
{{--    <link href="public/fontawesome-free-5.15.3-web/css/all.css" rel="stylesheet">--}}
    <link href="{{ asset('fontawesome-free-5.15.3-web/css/all.css') }}" rel="stylesheet">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
       $(document).ready(function (){
           var high_scores = {
               diff_choose : $('#diff_choose'),
               difficulty  : $('#difficulty'),
               view_btn    : $('#view_btn'),
               score_table : $('#score_table'),
               spinner     : $('#spinner'),
               back        : $('.back_btn'),
               view_scores : $('#view_scores')
           };

           //Hide elements on load
           high_scores.view_scores.hide();
           high_scores.spinner.hide();

           //view scores button
           high_scores.view_btn.click(function (){
               high_scores.diff_choose.hide();
               high_scores.spinner.show();

               //get scores
               $.ajax({
                   url: '/score_board_' + high_scores.difficulty.val(), //example url -> /score_board_easy
                   complete: function (){
                       high_scores.spinner.hide();
                       high_scores.view_scores.show();
                   },
                   success: function (response){
                       high_scores.score_table.empty();
                       high_scores.score_table.append(generateTable(response));
                   },
                   error: function (response){
                       console.log(response);
                   }
               });
           });

           //back
           high_scores.back.click(function (){
               if(high_scores.diff_choose.is(':hidden')){
                   high_scores.diff_choose.show();
                   high_scores.view_scores.hide();
               } else{
                   window.location.href = location.origin + '/';
               }
           });
       });

       function generateTable(scores, ){
           let table = '<table class="table table-striped">';
           table  += '<thead>';
            table  += '<tr>';
                table  += '<th scope="col">#</th>';
                table  += '<th scope="col">Name</th>';
                table  += '<th scope="col">Score</th>';
            table  += '</tr>';
           table  += '</thead>';
           table  += '<tbody>';
           $.each(scores, function (index, record){
               table  += '<tr>';
                table  += '<td scope="col">' + (index + 1).toString() + '</td>';
                table  += '<td scope="col">' + record.name + '</td>';
                table  += '<td scope="col">' + record.score + '</td>';
               table  += '</tr>';
           });
           table  += '</tbody>';
           table  += '</table>';

           return table;
       }
    </script>
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
        body{
            background: url("/img/math_background.jpg") no-repeat;
            background-size: cover;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height" style="overflow: hidden;">
    <div class="content rounded" style="padding: 9%;
background-color: #fff;">
        <div class="option" id="diff_choose">
            <div class="row form-group">
                <label for="difficulty">Difficulty: </label>
                <select class="custom-select" id="difficulty">
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                    <option value="extreme">Extreme</option>
                </select><br>
            </div>
            <div class="row form-group">
                <button id="view_btn"  class="btn btn-primary" style="margin-right: 2px;">View Scores</button><br>
                <button class="btn btn-primary back_btn" >Back</button>
            </div>
        </div>
        <div id="spinner">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <span> Loading... </span>
        </div>
        <div id="view_scores">
            <div id="score_table">

            </div>
    {{--        <button id="view_btn"  class="btn btn-primary" >View High Scores</button><br>--}}
            <button class="btn btn-primary back_btn" >Back</button>
        </div>
    </div>
</div>

</body>
</html>
