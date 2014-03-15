<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>PLOP</title>

  <!-- DEFAULT -->
  <!-- FOR SOME REASON, THIS DOESN'T WORK -->
  <link href="css/global_style.css" type="text/css" rel="styelsheet" />

  <!-- DROPZONE -->
  <link href="css/dropzone.css" type="text/css" rel="stylesheet" />
  <script type="text/javascript" src="js/dropzone.js"></script>

  <!-- JQUERY -->
  <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>

  <!-- FANCYBOX -->
  <link rel="stylesheet" href="css/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
  <script type="text/javascript" src="js/jquery.fancybox.js?v=2.1.5"></script>
    
  <!-- INIT PAGE -->
  <script>
  
  // display all files in the dropzone
  function getFiles(dz) {
    $.getJSON('upload.php', function(data) {
      $.each(data, function(key,value){
        var mockFile = { name: value.name, size: value.size };
        dz.options.addedfile.call(dz, mockFile);
        dz.options.thumbnail.call(dz, mockFile, "uploads/"+value.name);
      });
    }).fail (function(d) {
      console.warn("JSON parsing failed.");
      // on error, retry
      getFiles(dz);
    });
  }

  // setup Dropzone
  Dropzone.options.myDropzone = {
    maxFileSize: 20,
    uploadMultiple: false,
    parallelUploads: 1,
    clickable: true,
    acceptedFiles: "image/*",
    previewTemplate: "<div class='dz-preview dz-file-preview'><div class='dz-details'><img data-dz-thumbnail /></div><div class='dz-progress'><span class='dz-upload' data-dz-uploadprogress></span></div><div class='dz-success-mark'><span></span></div><div class='dz-error-mark'><span></span></div><div class='dz-error-message'><span data-dz-errormessage></span></div></div>",
      
    // add all files from uploads/ to dropzone when the page loads
    init: function() {
      getFiles(this);
    }
  };

      
  // Setup fancybox
  $(document).ready(function() {
    $(".dz-preview").fancybox();

    $(".dz-preview").fancybox({
      transitionIn : "elastic",
      transitionOut : "elastic",
      speedIn : 600, 
      speedOut : 200,
      // Add report button to the end
      afterLoad : function() {
        this.content = this.content.html() + "<a class='btn-red' onClick='report()'>Report</a>";
      },
      afterClose : function() {
        location.reload();
        return;
      }
    });
  });


  function report()
  {
     $.ajax({
       type: "POST", // POST method
       url: "report.php", // calls report
       data: "this.id", // sends id of the file
       success: function(msg){
         console.log( "Report done!"); // for testing
       }
     });   
  }
  </script>
</head>

<body>	
    
  <form action="upload.php" class="dropzone" id="my-dropzone">
    <div class="fallback">
      <span>Please update your browser to properly use this site.</span>
    </div>
  </form>
    
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