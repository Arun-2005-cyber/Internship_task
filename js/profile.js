// Profile JS - fetch / update profile with loader and auto-hide alert
$(document).ready(function(){
  const userId = localStorage.getItem('user_id');
  if(!userId){
    window.location.href = 'login.html';
    return;
  }

  function showAlert(msg, type='info'){
    $('#alert').html('<div class="alert alert-'+type+'">'+msg+'</div>');
    setTimeout(()=> $('#alert').html(''), 3000); // auto-hide after 3s
  }

  function showLoader(){
    return '<div class="text-center my-2"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
  }

  function fetchProfile(){
    $('#alert').html(showLoader());
    $.ajax({
      url: 'php/verify.php',
      method: 'POST',
      data: { user_id: userId },
      dataType: 'json',
      success: function(res){
        if(res.success){
          $('#name').val(res.data.name || '');
          $('#email').val(res.data.email || '');
          $('#age').val(res.data.profile.age || '');
          $('#dob').val(res.data.profile.dob || '');
          $('#contact').val(res.data.profile.contact || '');
          $('#address').val(res.data.profile.address || '');
          $('#alert').html(''); // clear loader
        } else {
          showAlert('Session invalid. Redirecting to login...', 'danger');
          setTimeout(()=> window.location.href='login.html', 1200);
        }
      },
      error: function(){ showAlert('Server error', 'danger'); }
    });
  }

  $('#saveBtn').click(function(e){
    e.preventDefault();
    const payload = {
      user_id: userId,
      name: $('#name').val().trim(),
      age: $('#age').val().trim(),
      dob: $('#dob').val(),
      contact: $('#contact').val().trim(),
      address: $('#address').val().trim()
    };
    $('#alert').html(showLoader());
    $.ajax({
      url: 'php/updateProfile.php',
      method: 'POST',
      data: payload,
      dataType: 'json',
      success: function(res){
        if(res.success){
          showAlert(res.message, 'success');
        } else {
          showAlert(res.message, 'danger');
        }
      },
      error: function(){ showAlert('Server error', 'danger'); }
    });
  });

  $('#refreshBtn').click(function(){ fetchProfile(); });

  $('#logoutBtn').click(function(){
    $('#alert').html(showLoader());
    $.ajax({
      url: 'php/logout.php',
      method: 'POST',
      data: { user_id: userId },
      dataType: 'json',
      success: function(){
        localStorage.removeItem('isLoggedIn');
        localStorage.removeItem('user_id');
        window.location.href = 'login.html';
      },
      error: function(){ 
        localStorage.clear(); 
        window.location.href='login.html'; 
      }
    });
  });

  // initial fetch
  fetchProfile();
});
