$(function(){

    $('.pl-item-details').click(function(){
        alert('справа будет выезжать сайдбар со свойствами айтема');
        return false;
    });

    $('#pl-item-add-link').click(function(){
        $('#pl-item-add').slideToggle(200);
        $('#pl-item-add input[type="text"]').focus();
        return false;
    });

    $('.pl-is-selected').change(function(){
        $('#pl-item-details').toggle();
        return false;
    });

    $('.pl-done').click(function(){
        $(this).closest('li').slideToggle(200);
    });

    $('#pl-complete-log-link').click(function(){
        $('#pl-complete-log').slideToggle(200);
        $(this).hide(200);
        return false;
    });

});
