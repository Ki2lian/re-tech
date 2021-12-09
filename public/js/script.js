$(document).ready(function() {
 $('#annonce_form_images').filer();     

});


$('.jq-change-mdp').on('click',(e)=>{
    $('.jq-show-mdp').toggle("slow")
    $('.logo-mdp-up').toggle()
    $('.logo-mdp-down').toggle()
    
})




$('.jq-hover').mouseenter(function(e){
    var button = e.currentTarget.lastElementChild
   button.classList.remove("d-none")
}).mouseleave(function(e){
    var button = e.currentTarget.lastElementChild
    button.classList.add("d-none")
})


// Gère le toggle et le display des tags
for (let i = 1; i < 4; i++) {
    $(`#tag${i}`).change(function () {
     
        if($(`#tag${i}`).val() != 'tag'){

            $(`#tag${i}`).css('border-color','#009c9f','solid', '2px')
            if(i<4)
                if( $(`#tag${i+1}`).css('display') == 'none'){
                    $(`#tag${i+1}`).toggle()
                }
        }else{
            $(`#tag${i}`).css('border-color','#dee2e6')
            if(i<4) $(`#tag${i+1}`).hide(), $(`#tag${i+2}`).hide()
        } 
    }) 
}

