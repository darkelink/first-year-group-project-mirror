<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <title>PLOP</title>

  <!-- DEFAULT -->
  <link href="global_style.css" type="text/css" rel="styelsheet" />

  <!-- DROPZONE -->
  <link href="css/dropzone.css" type="text/css" rel="stylesheet" />
  <script src="dropzone.min.js"></script>

  <!-- JQUERY -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

  <!-- FANCYBOX -->
  <link rel="stylesheet" href="/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
  <script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
    
  <script>
  // create Dropzone
  Dropzone.options.myDropzone = {
    init: function() {
      thisDropzone = this;
      thisDropzone.clickable = true;
      $.get('upload.php', function(data) {
        $.each(data, function(key,value){         
          var mockFile = { name: value.name, size: value.size };
          thisDropzone.options.addedfile.call(thisDropzone, mockFile);
          thisDropzone.options.thumbnail.call(thisDropzone, mockFile, "uploads/"+value.name);
        });
      });
    }
  };

  
  $(document).ready(function() {
    $(".dz-preview").fancybox();

    $(".dz-preview").fancybox({
      'transitionIn'  : 'elastic',
      'transitionOut' : 'elastic',
      'speedIn'   : 600, 
      'speedOut'    : 200,
      <!--report button links to report.php and gives it file name -->
      'content' : '<a class="btn-red" onClick="report()">Report</a>',
    });

    $(".dz-preview").fancybox({
      afterClose : function() {
        location.reload();
        return;
      }
    });

  });
  </script>
  <script type="text/javascript">
  function report()
  {
     $.ajax({
       type: "POST", // POST method
       url: "/report.php", // calls report
       data: "this.id", // sends id of the file
       success: function(msg){
         console.log( "Report done!"); // for testing
       }
     });   
  }
  </script>
</head>

<body>	
  <form action="upload.php" class="dropzone" id="my-dropzone"></form>

  <div class = "navigation_bar">
    <!-- must change images and links when possible -->
    <!-- search  -->
    <img src="images/my_profile_unpressed.svg"
      draggable="true" ondragstart="drag(event)"
      width="60" height="60" style="margin-bottom:5px;"
      onmouseover="src='images/my_profile_pressed.svg'"
      onmousedown="src='images/my_profile_pressed.svg'"
      onmouseout="src='images/my_profile_unpressed.svg'"
      onCLick="search()"                                />
    </br>
    <!-- upload  -->
    <img src="images/my_profile_unpressed.svg"
      width="60" height="60" style="margin-bottom:5px;"
      onmouseover="src='images/my_profile_pressed.svg'"
      onmousedown="src='images/my_profile_pressed.svg'"
      onmouseout="src='images/my_profile_unpressed.svg'"/>
    </br>
    <!-- help  -->
    <img src="images/my_profile_unpressed.svg"
      width="60" height="60" style="margin-bottom:5px;"
      onmouseover="src='images/my_profile_pressed.svg'"
      onmousedown="src='images/my_profile_pressed.svg'"
      onmouseout="src='images/my_profile_unpressed.svg'"/>
    </b>
  </div>

</body>
</html>
