// jQuery AJAX login
$(document).ready(function(){
  $('#loginBtn').click(function(e){
    e.preventDefault();
    const data = {
      email: $('#email').val().trim(),
      password: $('#password').val()
    };
    $('#alert').html('');
    if(!data.email || !data.password){
      $('#alert').html('<div class="alert alert-danger">Enter email & password</div>');
      return;
    }
    $.ajax({
      url: 'php/login.php',
      method: 'POST',
      data: data,
      dataType: 'json',
      success: function(res){
        if(res.success){
          // store token or user id in localStorage
          localStorage.setItem('isLoggedIn', '1');
          localStorage.setItem('user_id', res.user_id);
          $('#alert').html('<div class="alert alert-success">'+res.message+'</div>');
          setTimeout(()=> window.location.href = 'profile.html', 800);
        } else {
          $('#alert').html('<div class="alert alert-danger">'+res.message+'</div>');
        }
      },
      error: function(){
        $('#alert').html('<div class="alert alert-danger">Server error.</div>');
      }
    });
  });
});
