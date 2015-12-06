
$(document).ready(function(){
        //Show login form in login form page (not the fancybox one)
        showForms();
        function showForms(){
            console.log(window.location.pathname);
            if(window.location.pathname === '/admin/login/'){
            $('.loginLight').removeAttr('style');
            
            }
        }
        //Login form fancybox. El elemento #login crea la fancybox para logearse
        $('#login').click(function(){
            $.fancybox({
                href: "#loginForm",
                afterClose: function(){
                    $('#messageLog').hide();
                }
            });
        });
        //El formulario de la fancybox toma los datos y los procesa por ajax con el documento login.php. 
        //Este archivo php es el que procesa el formulario de login principal
        //Para todos los formularios con el id="loginForm" se procesan los datos con el metodo AJAX

       $('#loginForm').submit(function(){
            var dataLogin = $(this).serialize();
            $.post('./admin/login.php',dataLogin, processLogin);//Esta es otra forma de manejar datos con el metodo ajax, en lugar de $.ajax()

            function processLogin(data){
                switch (data){
                    case '200':
                    console.log(data);
                        location.href='../';
                    break;
                    case '404': 
                        $('.warnData').css('display','none');
                        $('.warnPass').removeAttr('style');
                        //$("#loginForm")[0].reset();
                        //$(this).prepend('<p>Su email y contraseña no coinciden, por favor intentelo de nuevo.</p>');
                    break;
                    case '400':
                        $('.warnPass').css('display','none');
                        $('.warnData').removeAttr('style');
                        $("#loginForm")[0].reset();
                } 
            }
            return false; //no olvidar al hacer nulo el envio del formulario, ya que toda la inforamacion se envia y se obtiene por AJAX

        });//end submit

		//Index ADMIN
        $('#charts').html('<table id="dataUsers" ></table>');
		showUsersAjax();
		

    

   });


function showUsersAjax(){
    var query = 'query=1';
    //Al hacer pruebas de funcionamiento, si se trabaja con JSON, nunca hacer pruebas con cadenas mixtas (letras y numeros) o solo alfabeticas. La funcion getJSON() no espera mas que datos JSON o datos numericos
	 $('#dataUsers').append("<tr><th>Name</th><th>Last Rate</th></tr>");


    $.getJSON('./Classes/UserAjax.php',query,showUsers); 
    function showUsers(dataJson){

        console.log('datos del servidor: '+dataJson);
        //$('#charts').html("<p>"+dataJson+"</p>");

        $.each(dataJson.users, function(i,users){
            var name = users.user.name;
            var email = users.user.email;
            $('#dataUsers').append("<tr><td>"+name+"</td><td>"+email+"</td></tr>");
            
        });

    }
	console.log('funcion show users');



}

   