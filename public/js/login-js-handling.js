$(document).ready(function(){

    // start hide error message in 4 second
    $('#email').on('input', function() {
        if($('#error-mes-login')){
            // Menghilangkan error message setelah 5 detik
            setTimeout(function() {
                $('#error-mes-login').hide();
            }, 4000);
        } 
    });

    $('#password').on('input', function() {
        if($('#error-mes-login')){
            // Menghilangkan error message setelah 5 detik
            setTimeout(function() {
                $('#error-mes-login').hide();
            }, 4000);
        } 
    });

    $('.fa-eye-slash').click(function() {
        var passwordField = $('#password');
        var passwordFieldType = passwordField.attr('type');
        if (passwordFieldType == 'password') {
          passwordField.attr('type', 'text');
          $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
          passwordField.attr('type', 'password');
          $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        }
      });

});