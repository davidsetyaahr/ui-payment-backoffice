    <h3>Student Name : {{ $student->name }}</h3>
    @php
        $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
    @endphp

    {{-- <img
        src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($student->id, $generatorPNG::TYPE_CODE_128)) }}"> --}}
    <img src="{{ url('storage/barcode') . $student->id . '.jpg' }}" alt="">
