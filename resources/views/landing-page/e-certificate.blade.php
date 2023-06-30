<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ md5($id) }}</title>
</head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<style>
    html {
        font-family: 'Poppins', sans-serif;
    }

    .a4 {
        height: 2480px;
        width: 3508px;
        page-break-after: auto;
    }

    .image {
        position: relative;
    }

    .nama {
        position: absolute;
        top: 310px;
        left: 490px;
        font-size: 30px;
    }

    .level {
        position: absolute;
        top: 364px;
        left: 491px;
        font-size: 30px;
    }

    .priceid {
        position: absolute;
        top: 76px;
        left: 165px;
        font-size: 30px;
    }

    .writing {
        position: absolute;
        top: 170px;
        left: 292px;
        font-size: 30px;
    }

    .speaking {
        position: absolute;
        top: 201px;
        left: 292px;
        font-size: 30px;
    }

    .reading {
        position: absolute;
        top: 289px;
        left: 292px;
        font-size: 30px;
    }

    .listening {
        position: absolute;
        top: 319px;
        left: 292px;
        font-size: 30px;
    }

    .grammar {
        position: absolute;
        top: 408px;
        left: 292px;
        font-size: 30px;
    }

    .vocabulary {
        position: absolute;
        top: 438px;
        left: 292px;
        font-size: 30px;
    }

    .average {
        position: absolute;
        top: 514px;
        left: 337px;
        font-size: 80px;
    }

    .a4:last-child {}

    @media print {

        html,
        body {
            page-break-after: always !important;
        }
    }
</style>

<body>
    {{-- <div class="a4"> --}}
    <div class="image">
        <img src="{{ url('/') }}/assets/img/sertif-depan.jpg" alt="" width="1001px">
        <div class="nama">
            {{ ucwords($student->name) }}
        </div>
        <div class="level">
            {{ ucwords($class->level) }}
        </div>
    </div>
    <div class="image">
        <img src="{{ url('/') }}/assets/img/sertif-belakang.jpg" alt="" width="1001px">
        <div class="priceid">
            {{ ucwords($class->level) }}
        </div>
        @php
            $writing = (($score1 ? $writing1->score : $writing1) + ($score2 ? $writing2->score : $writing2) + ($score3 ? $writing3->score : $writing3)) / 3;
            $speaking = (($score1 ? $speaking1->score : $speaking1) + ($score2 ? $speaking2->score : $speaking2) + ($score3 ? $speaking3->score : $speaking3)) / 3;
            $reading = (($score1 ? $reading1->score : $reading1) + ($score2 ? $reading2->score : $reading2) + ($score3 ? $reading3->score : $reading3)) / 3;
            $listening = (($score1 ? $listening1->score : $listening1) + ($score2 ? $listening2->score : $listening2) + ($score3 ? $listening3->score : $listening3)) / 3;
            $grammar = (($score1 ? $grammar1->score : $grammar1) + ($score2 ? $grammar2->score : $grammar2) + ($score3 ? $grammar3->score : $grammar3)) / 3;
            $vocabulary = (($score1 ? $vocabulary1->score : $vocabulary1) + ($score2 ? $vocabulary2->score : $vocabulary2) + ($score3 ? $vocabulary3->score : $vocabulary3)) / 3;
            $average = ($average_score1 + $average_score2 + $average_score3) / 3;
        @endphp
        <div class="writing">
            {{ round($writing) }}
        </div>
        <div class="speaking">
            {{ round($speaking) }}
        </div>
        <div class="reading">
            {{ round($reading) }}
        </div>
        <div class="listening">
            {{ round($listening) }}
        </div>
        <div class="grammar">
            {{ round($grammar) }}
        </div>
        <div class="vocabulary">
            {{ round($vocabulary) }}
        </div>
        <div class="average">
            {{ round($average) }}
        </div>
    </div>
    {{-- </div> --}}
    <script>
        window.print();
        window.onafterprint = (event) => {

            window.close();
        };
    </script>
</body>

</html>
