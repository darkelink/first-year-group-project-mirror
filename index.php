<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <title>PLOP</title>
  
  <!-- DROPZONE -->
  <link href="css/dropzone.css" type="text/css" rel="stylesheet" />
  <script src="dropzone.min.js"></script>
    
  <!-- SHOWING ALL FILES USING JQUERY -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script>
  Dropzone.options.myDropzone = {
  init: function() {
    thisDropzone = this;
    $.get('upload.php', function(data) {
      $.each(data, function(key,value){         
        var mockFile = { name: value.name, size: value.size };
        thisDropzone.options.addedfile.call(thisDropzone, mockFile);
        thisDropzone.options.thumbnail.call(thisDropzone, mockFile, "uploads/"+value.name);
      }); 
    });
  }
};
</script>
</head>

<body>	


  <form action="upload.php" class="dropzone" id="my-dropzone"></form>
  <?php
    echo file_get_contents("uploads/spritemap.png");
  ?>

<div class = "navigation_bar">
  <img src="images/my_profile_unpressed.svg"
  draggable="true" ondragstart="drag(event)"
  width="60" height="60" style="margin-bottom:5px;"
  onmouseover="src='images/my_profile_pressed.svg'"
  onmousedown="src='images/my_profile_pressed.svg'"
  onmouseout="src='images/my_profile_unpressed.svg'">
  </br>
  <img src="images/my_profile_unpressed.svg"
  width="60" height="60" style="margin-bottom:5px;"
  onmouseover="src='images/my_profile_pressed.svg'"
  onmousedown="src='images/my_profile_pressed.svg'"
  onmouseout="src='images/my_profile_unpressed.svg'">
  </br>
  <img src="images/my_profile_unpressed.svg"
  width="60" height="60" style="margin-bottom:5px;"
  onmouseover="src='images/my_profile_pressed.svg'"
  onmousedown="src='images/my_profile_pressed.svg'"
  onmouseout="src='images/my_profile_unpressed.svg'">
</br>
</div>



</body>
</html>
