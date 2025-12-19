@foreach ($data as $values)
    {{ $values['imageName'] }}
@endforeach
<button id="butt" onclick="func()">Number #</button>
<script>
    var dt = "{{ $data[0] }}";
    function func() {
        document.getElementById("butt").innerHTML = "Number " + dt;
    }
</script>