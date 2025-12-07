// jQuery AJAX registration with loader and auto-hide alert
$(document).ready(function () {
  $('#registerBtn').click(function (e) {
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

    // Clear previous alerts
    $('#alert').html('');

    // Validation
    if (!data.name || !data.email || !data.password || data.password.length < 6) {
      $('#alert').html('<div class="alert alert-danger">Fill required fields. Password min 6 chars.</div>');
      setTimeout(() => $('#alert').html(''), 3000); // hide after 3s
      return;
    }

    // Show loader
    const loader = '<div class="text-center my-2"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    $('#alert').html(loader);

    $.ajax({
      url: 'php/register.php',
      method: 'POST',
      data: data,
      dataType: 'json',
      success: function (res) {
        if (res.success) {
          $('#alert').html('<div class="alert alert-success">' + res.message + '</div>');
          setTimeout(() => {
            $('#alert').html('');
            window.location.href = 'login.html';
          }, 3000); // wait 3s before redirect
        } else {
          $('#alert').html('<div class="alert alert-danger">' + res.message + '</div>');
          setTimeout(() => $('#alert').html(''), 3000); // hide after 3s
        }
      },
      error: function (xhr) {
        $('#alert').html('<div class="alert alert-danger">Server error.</div>');
        setTimeout(() => $('#alert').html(''), 3000); // hide after 3s
      }
    });
  });

  // Password visibility toggle
  $('#togglePassword').click(function () {
    const passwordField = $('#password');
    const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
    passwordField.attr('type', type);
    $(this).toggleClass('bi-eye bi-eye-slash'); // switch icon
  });

});
