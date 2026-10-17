<!DOCTYPE html>
<html lang="{{ $c['locale'] ?? 'cs' }}">
<head>
<meta charset="utf-8">
<style>
    @font-face { font-family: 'TexGyreHeros'; font-style: normal; font-weight: normal; src: url('{{ resource_path('fonts/pdf/texgyreheros-regular.ttf') }}') format('truetype'); }
    @font-face { font-family: 'TexGyreHeros'; font-style: normal; font-weight: bold; src: url('{{ resource_path('fonts/pdf/texgyreheros-bold.ttf') }}') format('truetype'); }
    @font-face { font-family: 'Roboto'; font-style: normal; font-weight: normal; src: url('{{ resource_path('fonts/pdf/roboto-regular.ttf') }}') format('truetype'); }
    {{-- dompdf rozlišuje jen normal/bold, Roboto Light má proto vlastní rodinu --}}
    @font-face { font-family: 'RobotoLight'; font-style: normal; font-weight: normal; src: url('{{ resource_path('fonts/pdf/roboto-light.ttf') }}') format('truetype'); }
    @page { margin: 0; }
    html, body { margin: 0; padding: 0; }
    .page { position: relative; width: 794px; height: 559px; overflow: hidden; page-break-after: always; }
    .page-last { page-break-after: auto; }
</style>
</head>
<body>
    <div class="page">
        @include('gifts.certificate._front', ['c' => $c, 'mode' => 'pdf'])
    </div>
    <div class="page page-last">
        @include('gifts.certificate._back', ['c' => $c, 'qr' => $qr])
    </div>
</body>
</html>
