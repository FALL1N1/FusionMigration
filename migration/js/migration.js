var Migration = {

	version: '',
    currentStep: 1,
    toRealm: 0,
    oUsername: '',
    oPassword: '',

    initialize:function()
    {       
        $(".efecto").mouseover(function(){
            $(this).addClass("resaltar");
        });

        $(".efecto").mouseout(function(){
            $(this).removeClass("resaltar");
        });

        $("#to_realm_name").change(function(){
            Migration.toRealm = $("#to_realm_name").val();
        });

        $("a#wow335a").click(function(a)
        {
            a.preventDefault();
            if ($(this).hasClass('disabled'))
                return;

            Migration.version = 'wow335a';
            Migration.Switch(2);
        });

        $("a#wow406a").click(function(a)
        {
            a.preventDefault();
            if ($(this).hasClass('disabled'))
                return;

            Migration.version = 'wow406a';
            Migration.Switch(2);
        });

        $("a#wow434").click(function(a)
        {   a.preventDefault();
            if ($(this).hasClass('disabled'))
                return;

            Migration.version = 'wow434';
            Migration.Switch(2);
        });

        $("body").on('click', '.backb', function(b){ b.preventDefault(); Migration.GoBack(); });
        $("body").on('click', '.nextb', function(n){ n.preventDefault(); Migration.GoNext(); });      
    },
    
    cancelar: function(id, field)
    {
        $.post("migration/cancelar/" + id, {csrf_token_name: Config.CSRF}, function(data){
            UI.alert(data);
            if(data == txt1 || data == txt2 || data == txt3){
                $("#reescribir"+id).text("OK");   
            }
        });
    },

    Switch:function(paso)
    {

        $('div.drop-valid').fadeOut('fast');
        $('div.drop-invalid').fadeOut('fast');
        $('div.drop-checking').fadeOut('fast');
        $('table.nice_table').fadeOut('fast');

    	if (paso == 1) {
            $('html, body').stop().animate({scrollTop: $('#steps_anlca').offset().top}, 1000);
    		$("#selector").show();
            $("div[id^='paso-']").hide();
            $("div#paso-" + paso).fadeIn(1000);
            $('table.nice_table').fadeIn('fast');

            Migration.currentStep = paso;
            Migration.clearPaso(paso);
    	};

        if (paso == 2)
        {
            $('html, body').stop().animate({scrollTop: $('#steps_anlca').offset().top}, 1000);
            $("#selector").hide();
            $("a#descarga-wow335a").hide();
            $("a#descarga-wow406a").hide();
            $("a#descarga-wow434").hide();
            $("a#descarga-" + Migration.version).show('fast');

            $("div[id^='paso-']").hide();
            $("div#paso-" + paso).fadeIn(1000);

                Migration.currentStep = paso;
                Migration.clearPaso(paso);
        }
        
        if (paso == 3)
        {

            if ($("#lua")[0].files[0]){
            var file = $("#lua")[0].files[0];
            var fileName = file.name;
            var fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
            var fileSize = file.size;
            var fileType = file.type;                
            }

            if ($("input[name='oUsername']").val().length == 0 || $("input[name='oPassword']").val().length == 0 || $("input[name='oUsername']").val().length >= 17 || $("input[name='oPassword']").val().length >= 33)
            {
                UI.alert(''+txt37+'');
                return;
            }else if (Migration.toRealm == 0)
            {
                UI.alert(''+txt38+'');
                return;
            }else if (fileName != fname434 && fileName != fname406a && fileName != fname335a) 
            {
             	UI.alert(''+txt39+'');
            	return;
            };

            Migration.oUsername = $("input[name='oUsername']").val();
            Migration.oPassword = $("input[name='oPassword']").val();
            Migration.toRealm   = $("input[name='realm']").val();
            Migration.upl       = $( "input:file" );
        } 

        // Upload Manager
        if(paso == 3){
            var formData = new FormData($(".formulario")[0]);
            $.ajax({
                url: 'migration/procesar',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,

                beforeSend: function(){
                    $('div.drop-valid').fadeOut('fast', function(){
                        $('div.drop-invalid').fadeOut('fast', function(){
                            $('div.drop-checking').fadeOut('fast', function(){
                                $('div.drop-checking').fadeIn('fast');
                            });        
                        });
                    });
                },

                success: function(data){
                    if(data.length > 2000){
                        $("div[id^='paso-']").hide();
                        $("div#paso-" + paso).fadeIn(1000);
                        $(".reading-data").html(data);
                        Migration.currentStep = paso;
                        Migration.clearPaso(paso);
                        $('html, body').stop().animate({scrollTop: $('#steps_anlca').offset().top}, 1000);

                    }else if(data.length < 500 ){
                        $("div.drop-valid").html(data);
                        $('div.drop-checking').fadeOut('fast', function(){
                        $('div.drop-valid').fadeIn('fast');
                        });
                        
                    } 
                },

                error: function(data){
                    console.log();
                    $('div.drop-checking').fadeOut('fast', function(){
                        $('div.drop-invalid').fadeIn('fast');
                    });
                    
                },


            });
        }

        if (paso == 4)
        {
            $.ajax({
                url: 'migration/confirm',
                type: 'POST',
                data: { csrf_token_name: Config.CSRF },

            success: function(data){
                    if(data == 'success')
                    {
                        $("div[id^='paso-']").hide();
                        $("div#paso-" + paso).fadeIn(1000);
                        Migration.currentStep = paso;
                        Migration.clearPaso(paso);
                        $('html, body').stop().animate({scrollTop: $('#steps_anlca').offset().top}, 1000);    
                    }else{
                        $("div.drop-valid").text(data);
                    }
                },
            });
        }
    },

    clearPaso:function(paso)
    {
        $("li[id^='paso-li']").removeClass("ok");
        $("li[id^='paso-li']").removeClass("now");

        if (paso > 1)
            $("li#paso-li1").addClass("ok");

        if (paso > 2)
            $("li#paso-li2").addClass("ok");

        if (paso > 3)
            $("li#paso-li3").addClass("ok");

        if (paso == 4)
            $("li#paso-li4").addClass("ok");
        else
            $("li#paso-li" + paso).addClass("now");
    },

    GoBack:function()
    {
        Migration.Switch(Migration.currentStep - 1);
    },

    GoNext:function()
    {
        Migration.Switch(Migration.currentStep + 1);
    },
}

$(document).ready(function(){
    Migration.initialize();
});