 {{-- {!! QrCode::size(100)->generate(Request::url()); !!} --}}
 <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate('Make me into an QrCode!')) !!} ">
 {{ $qr = QrCode::format('png')->size(200)->generate('http://google.com')}}
 {{-- {{ $qr = QrCode::format('png')->size(200)->generate('http://google.com');}} --}}
