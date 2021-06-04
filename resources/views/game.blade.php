<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
        //Utility
        function debounce(func, wait, immediate) {
            var timeout;
            return function() {
                var context = this, args = arguments;
                var later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        }
        //GAME
        const config = {
            time_limit : 30,//seconds
            initial_score : 0,
            difficulty_levels : {
                "easy"    : 10,
                "medium"  : 10,
                "hard"    : 100,
                "extreme" : 1000,
            }
        };

        $(document).ready(function (){
            var game = {
                start            : $('#start'),
                difficulty       : $('#difficulty'),
                game             : $('#game'),
                diff_choose      : $('#diff_choose'),
                timer            : $('#timer'),
                score            : $('#score'),
                number1          : $('#number1'),
                operator         : $('#operator'),
                number2          : $('#number2'),
                equals           : $('#equals'),
                result           : $('#result'),
                submit           : $('#submit'),
                submit_score     : $('#submit_score'),
                user_name        : $('#user_name'),
                save_score       : $('#save_score'),
                play_again       : $('.play_again'),
                after_submit     : $('#after_submit'),
                view_high_scores : $('#view_high_scores'),
                score_achieved   : $('#score_achieved'),
                back             : $('.back_btn'),
                difficulty_level : 0
            };
            //set defaults
            game.score.text(config.initial_score);
            game.timer.text(config.time_limit);

            game.game.hide();
            game.submit_score.hide();
            game.after_submit.hide();

            //start button
            game.start.click(function (){
                game.difficulty_level = game.difficulty.val().trim();
                game.diff_choose.hide();
                game.game.show();
                starCountDown(game);
                setQuestionValues(game);
            });

            //switched from click to submit event in order to enable ENTER key as submit
            $('form').submit(function(e){
                e.preventDefault();
            });

            //submit answer
            $('form').submit(debounce(function (){
                // event.preventDefault();
                let result = 0;
                console.log(game.operator.text());
                //check if correct answer..
                switch (game.operator.text()){
                    case "+":
                        result = parseInt(game.number1.text()) + parseInt(game.number2.text());
                        break;
                    case "-":
                        result = parseInt(game.number1.text()) - parseInt(game.number2.text());
                        break;
                    case "*":
                        result = parseInt(game.number1.text()) * parseInt(game.number2.text());
                        break;
                    case "/":
                        result = parseInt(game.number1.text()) / parseInt(game.number2.text());
                        break;
                }


                if(result === parseInt(game.result.val())){
                    config.initial_score++;
                    game.score.text(config.initial_score);
                }

                game.result.val('');
                setQuestionValues(game);
            }, 250));

            game.play_again.click(function (){
                window.location.href = window.location.href;
            });

            game.save_score.click(function (){
                saveScore(game);
            });

            //back
            game.back.click(function (){
                window.location.href = location.origin + '/';
                // if(high_scores.diff_choose.is(':hidden')){
                //     high_scores.diff_choose.show();
                //     high_scores.view_scores.hide();
                // } else{
                // }
            });

            //Goes to View High Scores page
            game.view_high_scores.click(function (){
                window.location.href = location.origin + '/score_board';
            })

        });

        function starCountDown(game){
            let timer = setInterval(function (){
                if(config.time_limit){
                    config.time_limit--;//countdown
                    game.timer.text(config.time_limit);
                } else{
                    clearInterval(timer);
                    gameOver(game);
                }
            }, 1000);
        }

        function setQuestionValues(game){
            let operator_rand = Math.random();
            let operator = '';
            let number1  = Math.round(Math.random() * config.difficulty_levels[game.difficulty_level]);
            let number2  = Math.round(Math.random() * config.difficulty_levels[game.difficulty_level]);
            console.log(game.difficulty_level)
            console.log(config.difficulty_levels)
            console.log(config.difficulty_levels[game.difficulty_level])
            if(game.difficulty_level === 'easy'){//easy
                if (operator_rand < 0.5){
                    operator = '+';
                } else{
                    operator = '-';
                    number1 = number1 + number2;
                }
            } else{// medium and hard
                if(operator_rand < 0.25){
                    operator = '+';
                } else if(operator_rand < 0.5){
                    operator = '-';
                    number1 = number1 + number2;
                } else if(operator_rand < 0.75){
                    operator = '*';
                } else{
                    operator = '/';
                    number1 = number1 * number2;
                }
            }

            //set operator and numbers
            game.operator.text(operator);
            game.number1.text(number1);
            game.number2.text(number2);
        }

        function gameOver(game){
            console.log('game over');
            game.game.empty();
            game.diff_choose.empty();
            game.submit_score.show();
            game.score_achieved.text(config.initial_score)
        }

        function saveScore(game){
            $.ajax({
                method: "POST",
                url: "/save_score",
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                data: {
                    user_name : game.user_name.val(),
                    score     : config.initial_score,
                    difficulty_level : game.difficulty_level
                },
                complete: function (){
                    game.submit_score.empty();
                    game.after_submit.show();
                },
                success: function (response){
                    console.log(response);
                },
                error: function (response){
                    console.log(response);
                }
            });
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

        .game-el{
            margin: 1%;
        }

        .game-spans{
            padding: 1%;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height" style="overflow: hidden;">
    <div class="content rounded" style="padding: 9%;
background-color: #fff;">
        <div id="game">
            <span>Timer: </span><h1 id="timer"></h1><br>
            <span>Score: </span><span id="score"></span><br>
            <div id="question">
                <form>
                    <div class="row form-group">
                        <span id="number1" class="game-el game-spans"></span>
                        <span id="operator" class="game-el game-spans"></span>
                        <span id="number2" class="game-el game-spans"></span>
                        <span id="equals" class="game-el game-spans">=</span>
                        <input id="result" class="game-el form-control" type="text" style="width: 55%;">
                    </div>
                    <div class="row form-group" style="margin-left: 27%;">
                        <button id="submit" type="submit" class="game-el btn btn-primary">Submit</button><br>
                        <button class="game-el btn btn-primary back_btn" >Back</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="option" id="diff_choose">
            <label for="difficulty">Difficulty: </label>
            <select id="difficulty" style="margin-bottom: 24%;" class="custom-select">
                <option value="easy">Easy</option>
                <option value="medium">Medium</option>
                <option value="hard">Hard</option>
                <option value="extreme">Extreme</option>
            </select><br>

            <button id="start" class="btn btn-primary" >Start</button>
            <button class="btn btn-primary back_btn" >Back</button>
        </div>
        <div id="submit_score">
            <div class="row">
                <span>Your have achieved a high score of <strong id="score_achieved"></strong> points! </span>
            </div>
            <div class="row">
                <label style="margin-right: 2%;">Username:</label>
                <input type="text" class="form-control" id="user_name" style="width: 73%;" required>
            </div>
            <div class="row" style="margin: 8% 0;">
                <button type="button" class="btn btn-primary play_again" style="margin-right: 2px;">Play again</button>
                <button type="button" class="btn btn-primary"  id="save_score">Save Score</button>
            </div>
        </div>
        <div id="after_submit">
            <div class="row" style="padding: 0 0 15% 12%;">
                <span>Score Successfully Saved!</span>
            </div>
            <div class="row">
                <button type="button" class="btn btn-primary play_again" style="margin-right: 2px;">Play again</button>
                <button type="button" class="btn btn-primary" id="view_high_scores">View Scores</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
