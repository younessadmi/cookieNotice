$(document).ready(function(){
    var backgroundColor = $('#cookienotice_data .cookienotice_background_color').html();
    var textColor = $('#cookienotice_data .cookienotice_text_color').html();
    var timeToHideTheNotification = $('#cookienotice_data .cookienotice_cookie_expiration').html();
    var animation = $('#cookienotice_data .cookienotice_animation').html();
    var position = $('#cookienotice_data .cookienotice_position').html();
    var opacity = 90;
    var cookielaw = $("#cookienotice_data .cookienotice_law").html();

    var cookiename= 'cookienotice_accepted';
    var areCookiesAccepted = ($('#cookienotice_data .cookienotice_accepted').html() == 'on') ? true : false;

    if(areCookiesAccepted == false){
        $('.cookienotice_block_home .cookienotice_button_text button').click(function(){
            if(animation == 'Fondu'){
                $('.cookienotice_block_home').fadeOut('slow');
            }else if(animation == 'Glissement'){
                $('.cookienotice_block_home').slideUp('slow');
            }else $('.cookienotice_block_home').hide();

            setCookie(cookiename, 'accepted', timeToHideTheNotification*1000);
        });
    }
    if(cookielaw != true){
        $('.cookienotice_block_home .cookienotice_button_text').append("<a class='btn btn-default' href='http://www.webrankinfo.com/dossiers/webmastering/loi-cookies' target='_blank'>En savoir plus</a>");
    }
    $('.cookienotice_block_home').css('background-color', convertHex(backgroundColor, opacity));
    $('.cookienotice_block_home p.cookienotice_message').css('color', textColor);
    if(position == 'Haut'){
        $('.cookienotice_block_home').css('top', '0');
    }else if(position == 'Bas'){
        $('.cookienotice_block_home').css('bottom', '0');
    }
});

function setCookie(cname, cvalue, seconds) {
    var d = new Date();
    d.setTime(d.getTime() + (seconds));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function convertHex(hex,opacity){
    hex = hex.replace('#','');
    r = parseInt(hex.substring(0,2), 16);
    g = parseInt(hex.substring(2,4), 16);
    b = parseInt(hex.substring(4,6), 16);

    result = 'rgba('+r+','+g+','+b+','+opacity/100+')';
    return result;
}
