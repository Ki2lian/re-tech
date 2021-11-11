    document.querySelector('.switch-historique-reservation').addEventListener('click', function(e){
        ongletClick = e.originalTarget.parentElement
        switchColor(ongletClick)
    });
    document.querySelector('.switch-historique-ventes').addEventListener('click', function(e){
        ongletClick = e.originalTarget.parentElement
        switchColor(ongletClick)
    });


function switchColor(ongletClick){

    var check = ongletClick.firstElementChild;
    var text = ongletClick.lastElementChild

    if (ongletClick.classList.contains("active")){

        text.style.color= "#495057"
        check.style.color= "#009C9F";

    }else{
        text.style.color= "#dbdbdb";
        check.style.color= "#dbdbdb";
    }
}
$( document ).ready(function() {
    $('.jq-switch-actif').css("color", "#495057")
    $('.jq-switch-actif2').css("color", "#495057")
});