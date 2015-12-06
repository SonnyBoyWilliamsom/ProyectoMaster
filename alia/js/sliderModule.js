(function($){
    $.fn.extend({
        slider:function(params){
            var contexto=this;
            params.nav=(typeof(params.nav)!="undefined" && params.nav!=null)?params.nav:"";
            params.exclude=(typeof(params.exclude)!="undefined" && params.exclude!=null)?params.exclude:"";
            params.time=(typeof(params.time)!="undefined" && params.time!=null)?params.time:5000;
            params.transition=(typeof(params.transition)!="undefined" && params.transition!=null)?params.transition:600;
            params.arrows=(typeof(params.arrows)!="undefined" && params.arrows!=null)?params.arrows:null;
            var navegacionArrows=(params.arrows!=null)?params.arrows.selector:"";
            params.exclude=stringAExcluir(new Array(params.exclude,params.nav,navegacionArrows));
            this.inicializar(params);
            params.intervalo=this.activar(params);
            if(params.arrows!=null){
                this.flechasSlider(params);
            }
            if(params.nav!=null){
                this.navegacionSlider(params);
            }
            $(window).resize(function(){contexto.centrar($(contexto),params)});
        },
        inicializar:function(params){
            $(this).css({"overflow":"hidden","position":"relative"});
            var slides=$(this).children().not(params.exclude);
            if($(this).children(".active").length==0) 
                slides.first().not(params.exclude).addClass("active");
            for(var i=0;i<slides.length;i++){
                var slide=$(slides[i]);
                slide.css({"position":"absolute","top":"0"});
                slide.attr('data-position',i);
                if(slide.hasClass("active")) slide.css("left",0);
                else slide.css("left",slide.width());
            }
            this.centrar($(this),params);
       },
       activar:function(params){
            var selector=$(this);
            var intervalo=setInterval(function(){
                selector.siguiente(params.transition);
            },params.time);
            return intervalo;
        },
        desactivar:function(intervalo){
            console.log(intervalo);
            clearInterval(intervalo);
        },
        siguiente:function(transition){
            var activo=$(this).children(".active");
            var siguiente=(activo.next("[data-position]").length>0)?activo.next("[data-position]"):$(this).children("[data-position]").first();
            activo.removeClass("active");
            activo.animate({"left":-activo.width()},transition,function(){
                activo.css("left",activo.width());
            });
            siguiente.addClass("active").animate({"left":0},transition);
        },
        anterior:function(transition){
            var activo=$(this).children(".active");
            var siguiente=(activo.prev("[data-position]").length>0)?activo.prev("[data-position]"):$(this).children("[data-position]").last();
            activo.removeClass("active");
            siguiente.css("left",-activo.width());
            activo.animate({"left":activo.width()},transition);
            siguiente.addClass("active").animate({"left":0},transition);
        },
        ir:function(){},
        centrar:function(jObject,params){
            var slides=jObject.children().not(params.exclude);
            var anchoVentana=$(window).width();
            var margen=(anchoVentana-slides.width())/2;
            console.log(anchoVentana + " " + slides.width());
            for(var i=0;i<slides.length;i++){
                var slide=$(slides[i]);
                slide.css("margin-left",margen);
            }
        },
        navegacionSlider:function(){},
        flechasSlider:function(params){
            var contexto=$(this);
            var selector=$(params.arrows.selector);
            var izquierda=$(document.createElement("a")).addClass("left");
            var derecha=$(document.createElement("a")).addClass("right");
            izquierda.attr({"href":"javascript:void(0);"}).css({"background-image":"url('"+params.arrows.left+"')"});
            derecha.attr({"href":"javascript:void(0);"}).css({"background-image":"url('"+params.arrows.right+"')"});
            izquierda.on("click",function(){
               contexto.desactivar(params.intervalo);
               contexto.anterior(params.transition);
            });
            derecha.on("click",function(){
               contexto.desactivar(params.intervalo);
               contexto.siguiente(params.transition);
            });
            selector.append(izquierda).append(derecha);
        }
    });
    function stringAExcluir(arrayExcludes){
        var aExcluir=new Array();
        for(var i=0;i<arrayExcludes.length;i++){
            if(typeof(arrayExcludes[i])!="undefined" && arrayExcludes[i]!=null && arrayExcludes[i].length>0) aExcluir[aExcluir.length]=arrayExcludes[i]
        }
        return aExcluir.join(",");
    }
})(jQuery);