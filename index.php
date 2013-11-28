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

  <!-- Add fancyBox -->
  <link rel="stylesheet" href="/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
  <script type="text/javascript" src="/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
    
  <script>
  Dropzone.options.myDropzone = {
  init: function() {
    thisDropzone = this;
    thisDropzone.clickable = true;
    $.get('upload.php', function(data) {
      $.each(data, function(key,value){         
        var mockFile = { name: value.name, size: value.size };
        thisDropzone.options.addedfile.call(thisDropzone, mockFile);
        thisDropzone.options.thumbnail.call(thisDropzone, mockFile, "uploads/"+value.name);
        mockFile.addEventListener('click', function() { $(".dz-preview").fancybox();}, false);
      }); 
    });
  }
};

<<<<<<< HEAD
  function search() 
  {
    //this should implement the search functionality
  }

=======
>>>>>>> 116b757cbc4c246e7babe8302c9c14e56311b87e
</script>
</head>

<body>	


  <form action="upload.php" class="dropzone" id="my-dropzone"></form>
  <?php
    echo file_get_contents("uploads/spritemap.png");
  ?>

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
