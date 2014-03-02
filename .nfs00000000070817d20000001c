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
</body>
</html>