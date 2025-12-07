// jQuery AJAX login with loader and auto-hide alert
$(document).ready(function () {
  $('#loginBtn').click(function (e) {
    e.preventDefault();

    const data = {
      email: $('#email').val().trim(),
      password: $('#password').val()
    };

    // Clear previous alerts
    $('#alert').html('');

    // Validation
    if (!data.email || !data.password) {
      $('#alert').html('<div class="alert alert-danger">Enter email & password</div>');
      setTimeout(() => $('#alert').html(''), 3000); // hide after 3s
      return;
    }

    // Show loader
    const loader = '<div class="text-center my-2"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    $('#alert').html(loader);

    $.ajax({
      url: 'php/login.php',
      method: 'POST',
      data: data,
      dataType: 'json',
      success: function (res) {
        if (res.success) {
          // store token or user id in localStorage
          localStorage.setItem('isLoggedIn', '1');
          localStorage.setItem('user_id', res.user_id);

          $('#alert').html('<div class="alert alert-success">' + res.message + '</div>');
          setTimeout(() => {
            $('#alert').html('');
            window.location.href = 'profile.html';
          }, 3000); // wait 3s before redirect
        } else {
          $('#alert').html('<div class="alert alert-danger">' + res.message + '</div>');
          setTimeout(() => $('#alert').html(''), 3000); // hide after 3s
        }
      },
      error: function () {
        $('#alert').html('<div class="alert alert-danger">Server error.</div>');
        setTimeout(() => $('#alert').html(''), 3000); // hide after 3s
      }
    });
  });

  // Password visibility toggle - Login Page
  $('#togglePassword').click(function () {
    const pwd = $('#password');
    const newType = pwd.attr('type') === 'password' ? 'text' : 'password';
    pwd.attr('type', newType);
    $(this).toggleClass('bi-eye bi-eye-slash');
  });


});
