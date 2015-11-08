$(function(){

    $('.pl-item-details').click(function(){
        alert('справа будет выезжать сайдбар со свойствами айтема');
        return false;
    });

    $('.pl-is-selected').change(function(){
        $('#pl-item-details').toggle();
        return false;
    });


});
