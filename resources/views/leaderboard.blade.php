@extends ('layouts.app')
@section ('content')
<div class="px-4 py-5 my-5 text-center">
    <div class="col-lg-3 mx-auto">
        <div class="border rounded-3 mx-5">
            <button onclick="switchRecordListMode()" class="btn btn-outline-dark"><b id="recordListLabel">
                <span lang="en">Global best records:</span>
                <span lang="lv">Globalie labakie rezultati:</span>
            </b></button>
            <ol id="recordList"></ol>
        </div>
    </div>
</div>

<script>
    const records = [
    @foreach ($data["results"] as $record)
        [{{ $record["place_id"] }}, "{{ $record["name"] }}", "{{ $record["result"] }}"], 
    @endforeach
    ];
    var resultsShownGlobal = true;
    updateRecordList();
    function switchRecordListMode() {
        if (resultsShownGlobal)
            
            document.getElementById("recordListLabel").innerHTML = 
            '<span lang="en">Your best records:</span>'+
            '<span lang="lv">Jusu labakie rezultati:</span>';
        else
            document.getElementById("recordListLabel").innerHTML = 
            '<span lang="en">Global best records:</span>'+
            '<span lang="lv">Globalie labakie rezultati:</span>';
        resultsShownGlobal = !resultsShownGlobal;
        updateRecordList();
    }
    function updateRecordList() {
        recordListHtml = "";
        let recordArray = [];
        if (resultsShownGlobal) {
            j = 0;
            for (let i = 0; i < records.length; i++) {
                let exists = false;
                for (let k = 0; k < recordArray.length; k++) {
                    if (recordArray[k][0] == records[i][1]) {
                        exists = true;
                        break;
                    }
                }
                if (!exists) {
                    recordArray[j] = [];
                    recordArray[j][0] = records[i][1];
                    recordArray[j][1] = records[i][2];
                    j++;
                    if (j >= 10) break;
                }
            }
            for (let i = 0; i < recordArray.length; i++) {
                recordListHtml += "<li>" + recordArray[i][0] + " - " + Math.round(recordArray[i][1]) + " points </li>";
            }
            for (let i = 0; i < (10 - recordArray.length); i++) {
                recordListHtml += "<li> - </li>";
            }
        }
        else {
            j = 0;
            for (let i = 0; i < records.length; i++) {
                if (records[i][1] == "{{ Auth::user()->name }}") {
                    recordArray[j] = [];
                    recordArray[j][0] = records[i][1];
                    recordArray[j][1] = records[i][2];
                    j++;
                    if (j >= 10) break;
                }
            }
            for (let i = 0; i < recordArray.length; i++) {
                recordListHtml += "<li>" + recordArray[i][0] + " - " + Math.round(recordArray[i][1]) + " points </li>";
            }
            for (let i = 0; i < (10 - recordArray.length); i++) {
                recordListHtml += "<li> - </li>";
            }
        }
        document.getElementById('recordList').innerHTML = recordListHtml;
    }
</script>
@endsection