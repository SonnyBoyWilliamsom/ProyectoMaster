(function($){
    $.fn.extend({
        validate:function(remarcar){
            var seleccionadoRemarcar=(typeof remarcar == "undefined")?false:remarcar;
            $(this).on('submit',function(){
                return $(this).validateFields(seleccionadoRemarcar);
            })
        },
        validateFields:function(remarcar){
            var form=$(this);
            var inputsToEvaluate=form.find("[data-validation]");
            var retorno=true;
            for(var i=0;i<inputsToEvaluate.length;i++){
                var input=$(inputsToEvaluate[i]);
                var data=input.data("validation");
                var incorrecto=false;

                switch(data){
                    case 'select':
                        if(input.val()==-1){
                            retorno=false;
                            incorrecto=true;
                        }
                    break;
                    case 'number':
                        if(isNaN(parseInt(input.val()))){
                            retorno=false;
                            incorrecto=true;
                        }
                    break;
                    case 'telephone':
                        if(!/^((\+?34([ \t|\-])?)?[9|6|7]((\d{1}([ \t|\-])?[0-9]{3})|(\d{2}([ \t|\-])?[0-9]{2}))([ \t|\-])?[0-9]{2}([ \t|\-])?[0-9]{2})$/.test(input.val())){
                            retorno=false;
                            incorrecto=true;
                        }
                    break;
                    case 'postalcode':
                        if(isNaN(parseInt(input.val())) || input.val().length!=5){
                            retorno=false;
                            incorrecto=true;
                        }
                    break;
                    case 'mail':
                        if(!/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(input.val())){
                            retorno=false;
                            incorrecto=true;
                        }
                    break;
                    case 'text':
                        if(input.val().length==0){
                            retorno=false;
                            incorrecto=true;
                        }
                    break;
                    case 'alphanumeric':
                        if(!/^[A-Za-z0-9 ]$/.test(input.val())){
                            retorno=false;
                            incorrecto=true;
                        }
                    break;
                    case 'dni':

                    break;
                }
                if(incorrecto && remarcar){
                    input.css("border","1px solid red");
                }
                if(!incorrecto && remarcar) input.css("border","");
            }
            if(!retorno) alert("Existen datos obligatorios aún por rellenar, por favor, rellénelos y vuelva a intentarlo");
            return ($(this).validateOptionalFields(form,remarcar) && retorno);
        },
        validateOptionalFields:function(form,remarcar){
            var inputsToEvaluate=form.find("[data-optional]");
            var retorno=true;
            for(var i=0;i<inputsToEvaluate.length;i++){
                var input=$(inputsToEvaluate[i]);

                if(input.val().length==0) continue;
                var data=input.data("optional");
                var incorrecto=false;
                switch(data){
                    case 'select':
                        if(input.val()==-1){
                            retorno=false;
                            incorrecto=true;
                        }
                    break;
                    case 'number':
                        if(isNaN(input.val())){
                            retorno=false;
                            incorrecto=true;
                        }
                    break;
                    case 'telephone':
                        if(!/^((\+?34([ \t|\-])?)?[9|6|7]((\d{1}([ \t|\-])?[0-9]{3})|(\d{2}([ \t|\-])?[0-9]{2}))([ \t|\-])?[0-9]{2}([ \t|\-])?[0-9]{2})$/.test(input.val())){
                            retorno=false;
                            incorrecto=true;
                        }
                    break;
                    case 'postalcode':
                        if(isNaN(parseInt(input.val())) || input.val().length!=5){
                            retorno=false;
                            incorrecto=true;
                        }
                    break;
                    case 'mail':
                        if(!/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(input.val())){
                            retorno=false;
                            incorrecto=true;
                        }
                    break;
                    case 'text':
                        if(input.val().length==0){
                            retorno=false;
                            incorrecto=true;
                        }
                    break;
                    case 'alphanumeric':
                        if(!/^[A-Za-z0-9 ]$/.test(input.val())){
                            retorno=false;
                            incorrecto=true;
                        }
                    break;
                    case 'dni':

                    break;
                }
                if(incorrecto && remarcar) input.css("border","1px solid orange");
                if(!incorrecto && remarcar) input.css("border","");
            }
            if(!retorno) alert("Ha rellenado datos no obligatorios de forma incorrecta");
            return retorno;
        },
        postprocess:null
    })
})(jQuery);
