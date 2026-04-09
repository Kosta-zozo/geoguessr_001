@extends ('layouts.app')
@section ('content')
<style>
    .btn-primary {
        background-color: #46769B;
        border-width: 0;
    }
    .selected-bottom {
        background-color: #46769B !important;
        border-width: 0 0 4px 0 !important;
        border-color: #003366 !important;
    }
    .selected-end {
        background-color: #46769B !important;
        border-width: 0 4px 0 0 !important;
        border-color: #003366 !important;
    }
</style>

<div class="px-4 py-5 my-5 text-center">
    <div class="col-lg-3 mx-auto">
        <h2>
            <span lang="en">Leaderboard</span>
            <span lang="lv">Lideru saraksts</span>
        </h2>
        <p>
            <span lang="en">
                Leaderboard where you can preview best serie results that you and other players got. Select category (on the left), difficulty and see your achievements.
            </span>
            <span lang="lv">
                Līderu saraksts, kurā varat apskatīt labākos sērijas rezultātus, ko esat ieguvuši jūs un citi spēlētāji. Izvēlieties temu (kreisajā pusē), grūtības pakāpi un skatiet savus sasniegumus.
            </span>
        </p>
        <div class="border rounded-3 mx-5">
            <div class="row">
                <div class="col-4">
                    <h5>
                        <span lang="en">Categories ↓</span>
                        <span lang="lv">Temas ↓</span>
                    </h5>
                     @foreach ($categories as $category)
                        <button id="filterCategoryButton_{{ $category['id'] }}" onclick="filterRecordsByCategory({{ $category['id'] }})" class="btn btn-secondary rounded-0 w-100 shadow">{{ $category['name'] }}</button>
                    @endforeach
                </div>
                <div class="col-8">
                    <div class="row">
                        <button id="filterDiffButton_easy" onclick="filterRecordsByDifficulty('easy')" class="col-4 btn btn-secondary rounded-0 shadow">
                            <span lang="en">easy</span>
                            <span lang="lv">viegli</span>
                        </button>
                        <button id="filterDiffButton_medium" onclick="filterRecordsByDifficulty('medium')" class="col-4 btn btn-secondary rounded-0 shadow">
                            <span lang="en">medium</span>
                            <span lang="lv">videji</span>
                        </button>
                        <button id="filterDiffButton_hard" onclick="filterRecordsByDifficulty('hard')" class="col-4 btn btn-secondary rounded-0 shadow">
                            <span lang="en">hard</span>
                            <span lang="lv">gruti</span>
                        </button>
                    </div>
                    @if(Auth::check())
                        <div class="row">
                            <button id="filterGlobalButton_Global" onclick="filterRecordsByGlobal(true)" class="col-6 btn btn-secondary rounded-0 shadow">
                                <span lang="en">global</span>
                                <span lang="lv">globalie</span>
                            </button>
                            <button id="filterGlobalButton_Personal" onclick="filterRecordsByGlobal(false)" class="col-6 btn btn-secondary rounded-0 shadow">
                                <span lang="en">personal</span>
                                <span lang="lv">personalie</span>
                            </button>
                        </div>
                    @endif
                    <br>
                    <!-- <br> -->
                    <!-- <h3>Lideru saraksts</h3> -->
                    <ol id="recordList" class="shadow"></ol>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const records = [
    @foreach ($data["results"] as $record)
        [{{ $record["place_id"] }}, "{{ $record["name"] }}", "{{ $record["result"] }}", "{{ $record["category_id"] }}", "{{ $record["difficulty"] }}"], 
    @endforeach
    ];
    var resultsShownGlobal = true;
    var currentFilterCategory = 1;
    var currentFilterDifficulty = "easy";
    filterRecords();
    function filterRecordsByGlobal(global) {
        filterRecords(global, currentFilterCategory, currentFilterDifficulty);
    }
    function filterRecordsByCategory(category) {
        filterRecords(resultsShownGlobal, category, currentFilterDifficulty);
    }
    function filterRecordsByDifficulty(diff) {
        filterRecords(resultsShownGlobal, currentFilterCategory, diff);
    }
    function filterRecords(global = true, category = 1, diff = "easy") {
        recordListHtml = "";
        let recordArray = [];
        if (global) {
            j = 0;
            for (let i = 0; i < records.length; i++) {
                // checks if user from records[i] exists in new array
                let exists = false;
                for (let k = 0; k < recordArray.length; k++) {
                    if (recordArray[k][0] == records[i][1]) {
                        exists = true;
                        break;
                    }
                }
                if (!exists && records[i][3] == category && records[i][4] == diff) {
                    recordArray[j] = [];
                    recordArray[j][0] = records[i][1];
                    recordArray[j][1] = records[i][2];
                    j++;
                    if (j >= 10) break;
                }
            }
        }
        @if(Auth::check())
        else {
            j = 0;
            for (let i = 0; i < records.length; i++) {
                if (records[i][1] == "{{ Auth::user()->name }}" && records[i][3] == category && records[i][4] == diff) {
                    recordArray[j] = [];
                    recordArray[j][0] = records[i][1];
                    recordArray[j][1] = records[i][2];
                    j++;
                    if (j >= 10) break;
                }
            }
        }
        @endif
        for (let i = 0; i < recordArray.length; i++) {
            let style = "";
            if (i == 0) style = " class='shadow' style='background-color:gold;'";
            if (i == 1) style = " class='shadow' style='background-color:silver;'";
            if (i == 2) style = " class='shadow' style='background-color:#FCA956;'";
            recordListHtml += "<li" + style + ">" + recordArray[i][0] + " - " + Math.round(recordArray[i][1]) + 
                " <span lang='en'>points</span><span lang='lv'>punkti</span> </li>";
        }
        for (let i = 0; i < (10 - recordArray.length); i++) {
            recordListHtml += "<li> - </li>";
        }
        document.getElementById('recordList').innerHTML = recordListHtml;

        document.getElementById("filterCategoryButton_" + currentFilterCategory).classList.remove("selected-end"); // cancel previews category selection styles

        resultsShownGlobal = global;
        currentFilterCategory = category;
        currentFilterDifficulty = diff;

        //highlights
        highlightFilterDiffButton(diff);
        highlightFilterCategoryButton(category);
        @if(Auth::check())
            highlightFilterGlobalButton(global);
        @endif
    }
    function highlightFilterDiffButton(diff) {
        document.getElementById("filterDiffButton_easy").classList.remove("selected-bottom");
        document.getElementById("filterDiffButton_medium").classList.remove("selected-bottom");
        document.getElementById("filterDiffButton_hard").classList.remove("selected-bottom");
        document.getElementById("filterDiffButton_" + diff).classList.add("selected-bottom");
    }
    function highlightFilterGlobalButton(global) {
        document.getElementById("filterGlobalButton_Global").classList.remove("selected-bottom");
        document.getElementById("filterGlobalButton_Personal").classList.remove("selected-bottom");
        document.getElementById("filterGlobalButton_" + (global ? "Global" : "Personal")).classList.add("selected-bottom");
    }
    function highlightFilterCategoryButton(category) {
        document.getElementById("filterCategoryButton_" + category).classList.add("selected-end");
    }
</script>
@endsection
