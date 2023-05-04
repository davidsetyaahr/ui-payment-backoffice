    {{-- <h3>Student Name : {{ $student->name }}</h3>
    @php
        $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
    @endphp

    <img
        src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($student->id, $generatorPNG::TYPE_CODE_128)) }}">
    <img src="{{ url('storage/barcode') . $student->id . '.jpg' }}" alt=""> --}}
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Print {{ $id }}</title>
    </head>

    <body>
        <img src="{{ url('storage/barcode/' . $id . '.jpg') }}" width="350px">
    </body>

    </html>
    <script>
        window.print();
    </script>
