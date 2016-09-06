  <?php $pr=0; 
function pintarSelect($t){
  $t=='contraste' ? $contraste="selected" : $contraste="";
  $t=='lectura' ? $lectura="selected" : $lectura="";
  $t=='normal' ? $normal="selected" : $normal="";
  echo '<option value="contraste" '.$contraste.'>Alto Contraste</option>
        <option value="lectura" '.$lectura.'>Fácil Lectura</option>
        <option value="normal" '.$normal.'>Colores estándar</option>';
}
  
  ?>
  <div class="container">

        <div class="row metro">
            <a href="<?php echo site_url('Inicio'); ?>" class="col-xs-12 cabecera" style="background: #bbbbbb;">
                <h4 class="titulo">AiSoy Social Robotics  <img src="<?php echo base_url('incl/img/aisoyx32.png'); ?>" width="25"></h4>
            </a>
        </div>

        <div id="cuadros" class="row metro grid" style="margin-bottom: 80px;">
          <?php foreach($habitaciones as $d){echo $d;}?>
        </div>

        <div id="selector">
            <select id="elegir">
               <?php pintarSelect($queColor); ?>
            
            </select>
        </div>  
  </div>

  <div id="cuadroVoz">
  <div id="info">
      <p id="info_start" style="display:none"></p>
      <p id="info_speak_now" style="display:none">
        Habla ahora
      </p>
      <p id="info_no_speech" style="display:none">
        No se ha detectado habla. Tal vez debas ajustar las  <a href="//support.google.com/chrome/bin/answer.py?hl=en&amp;answer=1407892">preferencias del micrófono</a>.
      </p>
      <p id="info_no_microphone" style="display:none">
        No se ha detectado el micrófono. Asegurate de que el micrófono está instalado y <a href="//support.google.com/chrome/bin/answer.py?hl=en&amp;answer=1407892">configurado correctamente</a>.
      </p>
      <p id="info_allow" style="display:none">
        Pulsa en el botón "Permitir" para activar el micrófono
      </p>
      <p id="info_denied" style="display:none">
        Se ha denegado el acceso al micrófono.
      </p>
      <p id="info_blocked" style="display:none">
        El acceso al microfono está bloqueado. Cambia esto en chrome://settings/contentExceptions#media-stream
      </p>
      <p id="info_upgrade" style="display:none; font-size: 20px;">
        Necesitas <a href="//www.google.com/chrome">Chrome</a> version >=25, para ASR
      </p>
  </div>     
    <div id="cuadroVozCont">

        <div id="results">
              <span class="final" id="final_span">Pulsa el boton para hablar</span> 
              <span class="interim" id="interim_span"></span>
        </div>

        <div id="div_start">
            <button id="start_button" onclick="startButton(event)">
            <img alt="Inicio" id="start_img" src="https://www.google.com/intl/en/chrome/assets/common/images/content/mic.gif">
          </button>
        </div>

    </div>
  </div>
  <script>

      $(function() {
          $("#elegir").change(function(e) {
              e.preventDefault();
              var str;
              $( "select option:selected" ).each(function() {
                  str = $( this ).attr('value');
              });
              $.get("<?php echo site_url("Inicio/cambiaColores"); ?>/"+str, function(data, status){
                    location.reload(true);
              });
              
              return false;
          });
      });

      $(function() {
          $('.cabecera').click(function(e) {
              e.preventDefault();
              $('body').load('<?php echo site_url("Inicio/inicioBody"); ?>');
              return false;
          });
      });

      showInfo('info_start');

      var final_transcript = '';
      var recognizing = false;
      var ignore_onend;
      var start_timestamp;
      if (!('webkitSpeechRecognition' in window)) {
        upgrade();
      } else {
        start_button.style.display = 'inline-block';
        var recognition = new webkitSpeechRecognition();
        recognition.continuous = true;
        recognition.interimResults = true;

        recognition.onstart = function() {
          recognizing = true;
          showInfo('info_speak_now');
          start_img.src = 'https://www.google.com/intl/en/chrome/assets/common/images/content/mic-animate.gif';
        };

        recognition.onerror = function(event) {
          if (event.error == 'no-speech') {
            start_img.src = 'https://www.google.com/intl/en/chrome/assets/common/images/content/mic.gif';
            showInfo('info_no_speech');
            ignore_onend = true;
          }
          if (event.error == 'audio-capture') {
            start_img.src = 'https://www.google.com/intl/en/chrome/assets/common/images/content/mic.gif';
            showInfo('info_no_microphone');
            ignore_onend = true;
          }
          if (event.error == 'not-allowed') {
            if (event.timeStamp - start_timestamp < 100) {
              showInfo('info_blocked');
            } else {
              showInfo('info_denied');
            }
            ignore_onend = true;
          }
        };

        recognition.onend = function() {
          recognizing = false;
          if (ignore_onend) {
            return;
          }
          start_img.src = 'https://www.google.com/intl/en/chrome/assets/common/images/content/mic.gif';
          if (!final_transcript) {
            showInfo('info_start');
            final_span.innerHTML = 'Pulsa el boton para hablar';
            interim_span.innerHTML = '';            
            return;
          }
          showInfo('');
          $.ajax({
                   type: "POST",
                   url: "<?php echo site_url('Inicio/asr'); ?>", 
                   data: {asr:final_transcript},
                   dataType: "text",  
                   cache:false,
                   success: 
                    function(data){
                      //alert(data);  //as a debugging message.
                    }
           });
          //Selecciona el texto
          if (window.getSelection) {
            window.getSelection().removeAllRanges();
            var range = document.createRange();
            range.selectNode(document.getElementById('final_span'));
            window.getSelection().addRange(range);
          }

        };

        recognition.onresult = function(event) {
          var interim_transcript = '';
          if (typeof(event.results) == 'undefined') {
            recognition.onend = null;
            recognition.stop();
            upgrade();
            return;
          }
          for (var i = event.resultIndex; i < event.results.length; ++i) {
            if (event.results[i].isFinal) {
              final_transcript += event.results[i][0].transcript;
            } else {
              interim_transcript += event.results[i][0].transcript;
            }
          }
          final_transcript = capitalize(final_transcript);
          final_span.innerHTML = linebreak(final_transcript);
          interim_span.innerHTML = linebreak(interim_transcript);
          refresco=setInterval(function(){ refrescarDispositivos() }, 10000);

        };
      }

      function upgrade() {
        start_button.style.visibility = 'hidden';
        cuadroVozCont.style.display = 'none';

        showInfo('info_upgrade');
      }

      var two_line = /\n\n/g;
      var one_line = /\n/g;
      function linebreak(s) {
        return s.replace(two_line, '<p></p>').replace(one_line, '<br>');
      }

      var first_char = /\S/;
      function capitalize(s) {
        return s.replace(first_char, function(m) { return m.toUpperCase(); });
      }


      function startButton(event) {
        clearInterval(refresco);
        if (recognizing) {
          recognition.stop();
          return; 
        }
        final_transcript = '';
        recognition.lang = "es-ES";
        recognition.start();
        ignore_onend = false;
        final_span.innerHTML = '';
        interim_span.innerHTML = '';
        start_img.src = 'https://www.google.com/intl/en/chrome/assets/common/images/content/mic-slash.gif';
        showInfo('info_allow');
        start_timestamp = event.timeStamp;
      }

      function showInfo(s) {
        if (s) {
          for (var child = info.firstChild; child; child = child.nextSibling) {
            if (child.style) {
              child.style.display = child.id == s ? 'inline' : 'none';
            }
          }
          info.style.visibility = 'visible';
          info.style.display = 'block';
        } else {
          info.style.visibility = 'hidden';
          info.style.display = 'none';
        }
      }

      //Bloquear click derecho
      /*window.oncontextmenu = function(event) {
           event.preventDefault();
           event.stopPropagation();
           return false;
      };*/    

      function refrescarDispositivos(){
            if($('#queHabitacion').length){
              var dir="<?php echo site_url("Inicio/h"); ?>/"+($('#queHabitacion').text());
              $('#cuadros').load(dir);
            }
      }

      var refresco=setInterval(function(){refrescarDispositivos()}, 10000);

      $(document).ready(function(){
        var inicio;
        var tiempoPress = 1500;
        $('#cuadros').on('click', '.tile', function(e){
              e.preventDefault();
              clearInterval(refresco);
          });
        $('#cuadros').on('contextmenu', '.tile', function(e) {
              e.preventDefault();
              leerContenido($(this)[0].dataset.tts);
              return 0;
        });

        $('#cuadros').on('mousedown', '.tile', function(e){          
            inicio = new Date().getTime(); 
        });

        $('#cuadros').on('mouseleave', '.tile', function(e){
            inicio = 0;         
        });

        $('#cuadros').on('mouseup', '.tile', function(e){
            e.preventDefault();
            if ( new Date().getTime() >= ( inicio + tiempoPress )  ) {
                leerContenido($(this)[0].dataset.tts);
                return 0;             
            } else {
              if (e.which !== 3) {
                  clearInterval(refresco);
                  var dir=$(this).attr('href');
                  //console.log("Enviado:"+dir);
                  if(dir!='#'){
                     $.ajax({
                             type: "GET",
                             url: dir, 
                     });
                  }

                  if($(this).hasClass('activo')){
                      $(this).removeClass('activo');
                      $(this).addClass('inactivo');
                  }else if($(this).hasClass('inactivo')){
                      $(this).removeClass('inactivo');
                      $(this).addClass('activo');
                  }
                  refresco=setInterval(function(){ refrescarDispositivos() }, 10000);
              }
            }          
        } );        

      })       

      $(document).ready(function(){
          var tiempoPress = 1500;
          var inicio;
          $('#cuadros').on('contextmenu', '.tileHabitacion', function(e){
              e.preventDefault();
              var tts=$(this).find('h4')[0].innerText;
              leerContenido(tts);
          });
          $('#cuadros').on('click', '.tileHabitacion', function(e){
            e.preventDefault();
          });
          $('#cuadros').on('mousedown', '.tileHabitacion', function(e){          
              inicio = new Date().getTime(); 
          });
          $('#cuadros').on('mouseleave', '.tileHabitacion', function(e){
              inicio = 0;          
          });

          $('#cuadros').on('mouseup', '.tileHabitacion', function(e){            
              if ( new Date().getTime() >= ( inicio + tiempoPress )  ) {
                  var tts=$(this).find('h4')[0].innerText;
                  leerContenido(tts);
              } else {
                  var dir=$(this).attr('href');
                  $('#cuadros').load(dir);   
              }            
          });          

      });

      function leerContenido(contenido){
            var msg = new SpeechSynthesisUtterance();
            var voices = window.speechSynthesis.getVoices();
            msg.voice = voices[10]; 
            msg.voiceURI = 'native';
            msg.volume = 1;
            msg.rate = 0.9;
            msg.pitch = 1;
            msg.text = contenido;
            msg.lang = 'es-ES';
            speechSynthesis.speak(msg);        
      }

      function cambiarColorInput(e){
          $.ajax({
             type: "POST",
             url: "<?php echo site_url('/Inicio/cambiarDimmer'); ?>"+"/"+e.attributes.name.value, 
             data: {color: e.value},
             dataType: "text",  
             cache:false
          });
      }


  </script>