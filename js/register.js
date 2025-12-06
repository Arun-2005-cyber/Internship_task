// jQuery AJAX registration
$(document).ready(function(){
  $('#registerBtn').click(function(e){
    e.preventDefault();
    const data = {
      name: $('#name').val().trim(),
      email: $('#email').val().trim(),
      password: $('#password').val(),
      age: $('#age').val().trim(),
      dob: $('#dob').val(),
      contact: $('#contact').val().trim(),
      address: $('#address').val().trim()
    };
    $('#alert').html('');
    if(!data.name || !data.email || !data.password || data.password.length < 6){
      $('#alert').html('<div class="alert alert-danger">Fill required fields. Password min 6 chars.</div>');
      return;
    }
    $.ajax({
      url: 'php/register.php',
      method: 'POST',
      data: data,
      dataType: 'json',
      success: function(res){
        if(res.success){
          $('#alert').html('<div class="alert alert-success">'+res.message+'</div>');
          setTimeout(()=> window.location.href = 'login.html', 1200);
        } else {
          $('#alert').html('<div class="alert alert-danger">'+res.message+'</div>');
        }
      },
      error: function(xhr){
        $('#alert').html('<div class="alert alert-danger">Server error.</div>');
      }
    });
  });
});
