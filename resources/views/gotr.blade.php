<!DOCTYPE html>
<head>
    <style>
        img {
            width: calc(100% / 7 - 5px - 5px);
        }
        .first {
            padding-left: 25px;
        }
        .button1 {
            width: 20%;
            height: 30px;
        }
        .button2 {
            width: 20%;
            height: 30px;
        }
        .button3 {
            width: 55%;
            height: 30px;
        }
        .nametag {
            width: 20px;
            height: 100%;
            display: inline-block;
            position: absolute;
            left: 0;
            writing-mode: sideways-lr;
            text-align: center;
        }
        .deck {
            /* margin-left: 20px; */
            position: relative;
        }
        .red {
            background-color: darkred;
            color: white;
        }
        .green {
            background-color: green;
            color: white;
        }
        .orange {
            background-color: orange;
            color: black;
        }
        .yellow {
            background-color: yellow;
            color: black;
        }
        .white {
            background-color: lightgray;
            color: black;
        }
        .black {
            background-color: black;
            color: white;
        }
        .blue {
            background-color: cyan;
            color: black;
        }
        .purple {
            background-color: purple;
            color: white;
        }
    </style>
</head>
<body>
    <!-- <button onclick="if (confirm('Уверен?')) main()"> -->
    <button onclick="toggleArren()" class="button1">
        Аррены
    </button>
    <button onclick="toggleTargarien()" class="button2">
        Таргариены
    </button>
    <button onclick="main()" class="button3">
        Размешать
    </button>
    <br><br>
    <!-- <div class="deck">
        <input class="nametag red" value="Юра">
        <img src="public/images/TargarienB/0-Missandeya.png" alt="image not found" class="first" power="1">
        <img src="public/images/TargarienB/1-Kapitan_Groleo.png" alt="image not found" power="2">
        <img src="public/images/TargarienB/1-Rakharo.png" alt="image not found" power="3">
        <img src="public/images/TargarienB/2-Seriy_Cherv.png" alt="image not found" power="4">
        <img src="public/images/TargarienB/2-Silach_Belvas.png" alt="image not found" power="5">
        <img src="public/images/TargarienB/3-Daario_Naharis.png" alt="image not found" power="6">
        <img src="public/images/TargarienB/4-Dayeneris_Targarien.png" alt="image not found" power="7">
    </div>
    <hr> -->
    <div id="targarienDeck" class="deck">
        <input id="targarienNametag" class="nametag purple" value="Костя">
        <img src="public/gotr/TargarienA/0-Illirio_Mopatis.png" alt="image not found" class="first" power="1">
        <img src="public/gotr/TargarienA/1-Daeneris_Targarien.png" alt="image not found" power="2">
        <img src="public/gotr/TargarienA/1-Vizeris_Targarien.png" alt="image not found" power="3">
        <img src="public/gotr/TargarienA/2-Ksaro_Ksoan_Daksos.png" alt="image not found" power="4">
        <img src="public/gotr/TargarienA/2-Sir_Jorah_Marmont.png" alt="image not found" power="5">
        <img src="public/gotr/TargarienA/3-Arstan_Beloborodiy.png" alt="image not found" power="6">
        <img src="public/gotr/TargarienA/4-Khal_Grogo.png" alt="image not found" power="7">
    </div>
    <hr>
    <div id="arrenDeck" class="deck">
        <input id="arrenNametag" class="nametag blue" value="Андрей">
        <img src="public/gotr/Arren/0-Robert_Arren.png" alt="image not found" class="first" power="1">
        <img src="public/gotr/Arren/1-Anya_Yeinvud.png" alt="image not found" power="2">
        <img src="public/gotr/Arren/1-Godrik_Borrel.png" alt="image not found" power="3">
        <img src="public/gotr/Arren/2-Garri_Harding.png" alt="image not found" power="4">
        <img src="public/gotr/Arren/2-Sir_Vardis_Igen.png" alt="image not found" power="5">
        <img src="public/gotr/Arren/3-Bronzoviy_Jhon_Rois.png" alt="image not found" power="6">
        <img src="public/gotr/Arren/4-Liza_Arren.png" alt="image not found" power="7">
    </div>
    <hr>
    <div class="deck">
        <input class="nametag yellow" value="Декер">
        <img src="public/gotr/Barateon/0-Pestrak.png" alt="image not found" class="first" power="1">
        <img src="public/gotr/Barateon/1-Melisandra.png" alt="image not found" power="2">
        <img src="public/gotr/Barateon/1-Sallador_Saan.png" alt="image not found" power="3">
        <img src="public/gotr/Barateon/2-Brienna_iz_Tarta.png" alt="image not found" power="4">
        <img src="public/gotr/Barateon/2-Sir_Davos_Sivort.png" alt="image not found" power="5">
        <img src="public/gotr/Barateon/3-Renly_Barateon.png" alt="image not found" power="6">
        <img src="public/gotr/Barateon/4-Stannis_Barateon.png" alt="image not found" power="7">
    </div>
    <hr>
    <div class="deck">
        <input class="nametag black" value="Андрей">
        <img src="public/gotr/Greidjoi/0-Eiron_Sirovlasiy.png" alt="image not found" class="first" power="1">
        <img src="public/gotr/Greidjoi/1-Asha_Greidjoi.png" alt="image not found" power="2">
        <img src="public/gotr/Greidjoi/1-Dagmer_Bitiy_Rot.png" alt="image not found" power="3">
        <img src="public/gotr/Greidjoi/2-Beilon_Greidjoi.png" alt="image not found" power="4">
        <img src="public/gotr/Greidjoi/2-Teon_Greidjoi.png" alt="image not found" power="5">
        <img src="public/gotr/Greidjoi/3-Viktarion_Greidjoi.png" alt="image not found" power="6">
        <img src="public/gotr/Greidjoi/4-Eyron_Voroniy_Glaz.png" alt="image not found" power="7">
    </div>
    <hr>
    <div class="deck">
        <input class="nametag red" value="Геныч">
        <img src="public/gotr/Lannister/0_Serseya_Lannister.png" alt="image not found" class="first" power="1">
        <img src="public/gotr/Lannister/1-Sir_Kivan_Lannister.png" alt="image not found" power="2">
        <img src="public/gotr/Lannister/1-Tirion_Lannister.png" alt="image not found" power="3">
        <img src="public/gotr/Lannister/2-Jeime_Lannister.png" alt="image not found" power="4">
        <img src="public/gotr/Lannister/2-Pes.png" alt="image not found" power="5">
        <img src="public/gotr/Lannister/3-Sir_Gregor_Kligan.png" alt="image not found" power="6">
        <img src="public/gotr/Lannister/4-Taivin_Lannister.png" alt="image not found" power="7">
    </div>
    <hr>
    <div class="deck">
        <input class="nametag orange" value="bruh">
        <img src="public/gotr/Martell/0-Doran_Martell.png" alt="image not found" class="first" power="1">
        <img src="public/gotr/Martell/1-Arianna_Martell.png" alt="image not found" power="2">
        <img src="public/gotr/Martell/1-Nimeria_Send.png" alt="image not found" power="3">
        <img src="public/gotr/Martell/2-Gerold_Temnaya_Zvezda.png" alt="image not found" power="4">
        <img src="public/gotr/Martell/2-Obara_Send.png" alt="image not found" power="5">
        <img src="public/gotr/Martell/3-Areo_Hota.png" alt="image not found" power="6">
        <img src="public/gotr/Martell/4-Oberin_Krasnaya_Gadyuka.png" alt="image not found" power="7">
    </div>
    <hr>
    <div class="deck">
        <input class="nametag white" value="bruh">
        <img src="public/gotr/Stark/0-Keitelin_Stark.png" alt="image not found" class="first" power="1">
        <img src="public/gotr/Stark/1-Brinden_Cornaja_Riba.png" alt="image not found" power="2">
        <img src="public/gotr/Stark/1-Sir_Rodrik_Kessel.png" alt="image not found" power="3">
        <img src="public/gotr/Stark/2_Bolshoi_Jon_Amber.png" alt="image not found" power="4">
        <img src="public/gotr/Stark/2-Ruse_Bolton.png" alt="image not found" power="5">
        <img src="public/gotr/Stark/3-Robb_Stark.png" alt="image not found" power="6">
        <img src="public/gotr/Stark/4-Eddard_Stark.png" alt="image not found" power="7">
    </div>
    <hr>
    <div class="deck">
        <input class="nametag green" value="bruh">
        <img src="public/gotr/Tirell/0_Koroleva_Shipov.png" alt="image not found" class="first" power="1">
        <img src="public/gotr/Tirell/1-Alister_Florent.png" alt="image not found" power="2">
        <img src="public/gotr/Tirell/1-Margeri_Tirell.png" alt="image not found" power="3">
        <img src="public/gotr/Tirell/2-Rendill_Tarli.png" alt="image not found" power="4">
        <img src="public/gotr/Tirell/2-Sir_Garlan_Tirell.png" alt="image not found" power="5">
        <img src="public/gotr/Tirell/3-Sir_Loras_Tirell.png" alt="image not found" power="6">
        <img src="public/gotr/Tirell/4-Meis_Tirell.png" alt="image not found" power="7">
    </div>
</body>

<script>
    var cardArray = [];
    cardArray.push(document.querySelectorAll('[power="1"]'));
    cardArray.push(document.querySelectorAll('[power="2"]'));
    cardArray.push(document.querySelectorAll('[power="3"]'));
    cardArray.push(document.querySelectorAll('[power="4"]'));
    cardArray.push(document.querySelectorAll('[power="5"]'));
    cardArray.push(document.querySelectorAll('[power="6"]'));
    cardArray.push(document.querySelectorAll('[power="7"]'));
    var nameArray = document.getElementsByClassName('nametag');
    var temp = [];
    
    function main() {
        for (let j = 0; j < 7; j++) {
            temp = [];
            for (var i = 0; i < cardArray[j].length; i++) {
                temp.push(cardArray[j][i].src);
            }
            for (var i = 0; i < cardArray[j].length; i++) {
                var randomNumber = Math.floor(Math.random() * temp.length);
                cardArray[j][i].src = temp[randomNumber];
                removeElementByIndex(randomNumber);
            }
        }

        temp = [];
        for (var i = 0; i < nameArray.length; i++) {
            temp.push(nameArray[i].value);
        }
        for (var i = 0; i < nameArray.length; i++) {
            var randomNumber = Math.floor(Math.random() * temp.length);
            nameArray[i].value = temp[randomNumber];
            removeElementByIndex(randomNumber);
        }
    }
    function removeElementByIndex(index) {
        for (i = index; i < (temp.length); i++) {
            if (i == temp.length - 1) temp.pop();
            else temp[i] = temp[i + 1];
        }
    }

    function toggleTargarien() {
        if (targarienDeck.style.display == '')
        {
            targarienDeck.style.display = 'none';
            targarienNametag.classList.remove("nametag");
        }
        else
        {
            targarienDeck.style.display = '';
            targarienNametag.classList.add("nametag");
        }
        nameArray = document.getElementsByClassName('nametag');
    }
    function toggleArren() {
        if (arrenDeck.style.display == '')
        {
            arrenDeck.style.display = 'none';
            arrenNametag.classList.remove("nametag");
        }
        else
        {
            arrenDeck.style.display = '';
            arrenNametag.classList.add("nametag");
        }
        nameArray = document.getElementsByClassName('nametag');
    }
</script>