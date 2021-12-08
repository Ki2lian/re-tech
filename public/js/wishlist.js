function toggleWishlist(url, that, pageWishlist = false){
    $.ajax({
        type: 'post',
        url: url,
        data: {
                'idAnnonce': that.parent().data('id')
        },
        success: function(data, statut){
            if(data.code == 200){
                if(!pageWishlist){
                    scales = [2, 1]
                    if(that.hasClass('fas')){
                        scales = [0.5, 1]
                    }
                    var tl = anime.timeline({
                        easing: 'easeOutCubic',
                        duration: 300,
                        opacity: .5
                    });
                    tl.add({
                        targets: that.get(0),
                        scale: scales[0],
                        opacity: 1
                    })
                    .add({
                        targets: that.get(0),
                        scale: scales[1]
                        
                    });
                    that.toggleClass('fas')
                    that.toggleClass('far')
                }else{
                    var animation = anime({
                        targets: that.parents('.annonce').parent().get(0),
                        easing: 'easeOutCubic',
                        duration: 300,
                        opacity: 0,
                        scale: 0
                    });
                    animation.finished.then(() => {
                        that.parents('.annonce').parent().remove()
                        if ($('.annonce').length == 0 ) {
                            $('.no-wish').removeClass('d-none')
                            $('.wish-title').hide()
                        }
                    })
                }
            }else{
                alert(data.message)
            }
        }
    });
}