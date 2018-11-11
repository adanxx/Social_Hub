// console.log('Register js is online:')
// console.log();



$(document).ready(function(){
    console.log('JQuery is online:');

    //Onclick signup, hide and show the register form or login form
    
    $('#signup').click(function(){
       $('#first').slideUp("slow", function(){
           $("#second").slideDown('slow');
       })
    })

    $('#signin').click(function(){
        $('#second').slideUp("slow", function(){
            $("#first").slideDown('slow');
        })
     })

    //  $(document).on('click',"#reg_btn", function(){
    //      $('#first').hide();
    //      console.log('clicked')
    //  })
     
    

})