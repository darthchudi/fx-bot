<!DOCTYPE html>
<html>
<head>
	<title>Gifs</title>
</head>
<body>
	{{-- @for($i=1; $i<=46; $i++)
		<h1> screaming_{{$i}} </h1>
		<img src="gifs/screaming_{{$i}}.gif">
	@endfor --}}

	@foreach($gifs as $gif)
		<img src="gifs/{{$gif}}">
		<h1> {{$gif}} heh</h1>
	@endforeach


</body>
</html>