document.addEventListener('DOMContentLoaded', function() {
    const open = document.getElementById('open');
    const popup = document.getElementById('main');
    const form = document.getElementById('form')

    function restoreFromLocalStorage() {
        if (localStorage.getItem('name')) {
            document.getElementById('name').value = localStorage.getItem('fullName');
        }
        if (localStorage.getItem('email')) {
            document.getElementById('email').value = localStorage.getItem('email');
        }
        if (localStorage.getItem('number')) {
            document.getElementById('number').value = localStorage.getItem('phone');
        }
        if (localStorage.getItem('organization')) {
            document.getElementById('organization').value = localStorage.getItem('organization');
        }
        if (localStorage.getItem('message')) {
            document.getElementById('message').value = localStorage.getItem('message');
        }
    }

    open.onclick = function() {
        history.pushState({ state: 'popup' }, '', '?popup');
        popup.style.display = 'flex';
        
        restoreFromLocalStorage();
    };

    window.onpopstate = function(event) {
        if (!event.state || event.state.state !== 'popup') {
            popup.style.display = 'none'; 
        }
    };


    

    
        $(function(){
            $("#form").submit(function(e){
                e.preventDefault();

                let name = document.getElementById('name').value;
                let email = document.getElementById('email').value;
                let number = document.getElementById('number').value;
                let organization = document.getElementById('organization').value;
                let message = document.getElementById('message').value;
                let check = document.getElementById('check').checked;
        
                if (!name || !email || number=='+7' || !number || !organization || !message || !check) {
                    alert('Заполните все обязательные поля!');
                    return;
                }
        
                localStorage.setItem('name', name);
                localStorage.setItem('email', email);
                localStorage.setItem('number', number);
                localStorage.setItem('organization', organization);
                localStorage.setItem('message', message);
        
            
              var href = $(this).attr("action");
              
              $.ajax({
                  type: "POST",
                  url: href,
                  data: new FormData(this),
                  dataType: "json",
                  processData: false,
                  contentType: false,
                  success: function(response){
                    if(response.status == "success"){
                        alert("We received your submission, thank you!");
                        localStorage.clear();
                        form.reset();
                    }
                    else if(response.code === 422){
                      alert("Field validation failed");
                      $.each(response.errors, function(key) {
                        $('[name="' + key + '"]').addClass('formcarry-field-error');
                      });
                    }
                    else{
                      alert("An error occured: " + response.message);
                    }
                  },
                  error: function(jqXHR, textStatus){
                    const errorObject = jqXHR.responseJSON
          
                    alert("Request failed, " + errorObject.title + ": " + errorObject.message);
                  }
              });
            });
          });
    
});

