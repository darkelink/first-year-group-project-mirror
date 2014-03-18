<!DOCTYPE html>
<html>
<head>
  <title>PLOP</title>
  <meta charset="UTF-8" name="vieport" 
    content="width=device-width, user-scalable=no, minimum=scale=1.0, maximum=scale=1.0">

  <!-- FONT -->
  <link href='http://fonts.googleapis.com/css?family=Quicksand' rel='stylesheet' type='text/css'>

  <!-- DEFAULT -->
  <link href="css/global_style.css" type="text/css" rel="stylesheet" />

  <!-- DROPZONE -->
  <link href="css/dropzone.css" type="text/css" rel="stylesheet" />
  <script type="text/javascript" src="js/dropzone.min.js"></script>

  <!-- JQUERY -->
  <script type="text/javascript" src="js/jquery-2.1.0.min.js"></script>

  <!-- FANCYBOX -->
  <link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" media="screen" />
  <script type="text/javascript" src="js/jquery.fancybox.pack.js"></script>
    
  <!-- THREE.JS -->
  <script src="js/three.min.js"></script>

  <!-- SHADERS -->
  <script src="js/Mirror.js"></script>
  <script src="js/WaterShader.js"></script>

</head>

<body>
<script>
  // main div
  var container, myDropzone;

  // three.js elements
  var camera, scene, renderer;
  var waterNormals;

  // constants
  var WIDTH = 2000;
  var HEIGHT = 2000;

  var fileName;


  init();
  animate();

  // Create three.js scene
  function init() {
    // create all elements
    container = document.createElement('div');
    container.id = 'background';
    document.body.appendChild(container);

    renderer = new THREE.WebGLRenderer();
    renderer.setSize(window.innerWidth, window.innerHeight);
    container.appendChild(renderer.domElement);

    scene = new THREE.Scene();
    camera = new THREE.PerspectiveCamera(55, window.innerWidth / window.innerHeight, 0.5, 3000000);

    // give a nice position to the camera
    camera.position.set(0, Math.max(WIDTH * 1.5, HEIGHT) / 8, HEIGHT);
    camera.lookAt(new THREE.Vector3(0, 0, 0));

    // add a light
    directionalLight = new THREE.DirectionalLight(0xffffff, 1);
    directionalLight.position.set(- 1, 0.5, - 1);
    scene.add(directionalLight);

    // load shaders
    waterNormals = new THREE.ImageUtils.loadTexture('images/normals.png');
    waterNormals.wrapS = waterNormals.wrapT = THREE.RepeatWrapping;

    water = new THREE.Water(renderer, camera, scene, {
      textureWidth: 512,
      textureHeight: 512,
      waterNormals: waterNormals,
      alpha:  1.0,
      sunDirection: directionalLight.position.normalize(),
      sunColor: 0xffffff,
      waterColor: 0x0022ff,
      distortionScale: 50.0,
    });

    mirrorMesh = new THREE.Mesh(new THREE.PlaneGeometry(WIDTH * 500, HEIGHT * 500, 50, 50), water.material);

    mirrorMesh.add(water);
    mirrorMesh.rotation.x = -Math.PI * 0.5;
    scene.add(mirrorMesh);

    // load sky
    var cubeMap = new THREE.Texture([]);
    cubeMap.format = THREE.RGBFormat;
    cubeMap.flipY = false;

    var loader = new THREE.ImageLoader();
    loader.load('images/skybox.png', function (image) {
      // the skybox is in cubemap format, so we need each side of the cube
      var getSide = function (x, y) {
        var size = 1024;
        var canvas = document.createElement('canvas');
        canvas.width = size;
        canvas.height = size;
        var context = canvas.getContext('2d');
        context.drawImage(image, - x * size, - y * size);
        return canvas;
      };

      cubeMap.image[0] = getSide(2, 1);
      cubeMap.image[1] = getSide(0, 1);
      cubeMap.image[2] = getSide(1, 0);
      cubeMap.image[3] = getSide(1, 2);
      cubeMap.image[4] = getSide(1, 1);
      cubeMap.image[5] = getSide(3, 1);
      cubeMap.needsUpdate = true;
    });

    // add sky
    var cubeShader = THREE.ShaderLib['cube'];
    cubeShader.uniforms['tCube'].value = cubeMap;

    var skyBoxMaterial = new THREE.ShaderMaterial({
      fragmentShader: cubeShader.fragmentShader,
      vertexShader: cubeShader.vertexShader,
      uniforms: cubeShader.uniforms,
      depthWrite: false,
      side: THREE.BackSide
    });

    var skyBox = new THREE.Mesh(new THREE.CubeGeometry(1000000, 1000000, 1000000), skyBoxMaterial);
    scene.add(skyBox);
  }

  // animate three.js scene
  function animate() {
    requestAnimationFrame(animate);
    render();
  }
  // render three.js scene
  function render() {
    water.material.uniforms.time.value += 1.0 / 60.0;
    water.render();
    renderer.render(scene, camera);
  }

  // resize when the window resizes
  window.onresize = function(event) {
    renderer.setSize(window.innerWidth, window.innerHeight);
  }

  //////////////////////////////////////////////////////////////////////////////
  // Here marks the end of the webgl code
  //////////////////////////////////////////////////////////////////////////////

  // display all files in the dropzone
  function getFiles(dz) {
    $.getJSON('upload.php?getFiles=true', function(data) {
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

  function report()
  {
     $.ajax({
       type: "POST", // POST method
       url: "report.php?name=" + fileName, // calls report
       success: function(msg){
         console.log("Report done"); // for testing
       }
     });   
  }
  
  // add the dropzone

  $(document).ready(function() {

    // Dropzone
    var dzMain = document.createElement('div');
    dzMain.className = "dropzone";
    document.body.appendChild(dzMain);
    var dropzone = new Dropzone(dzMain, {
      url: "upload.php", 
      maxFilesize: 20,
      uploadMultiple: false,
      parallelUploads: 1,
      clickable: true,
      acceptedFiles: "image/*",
      previewTemplate: "<div class='dz-preview dz-file-preview'><div class='dz-details'><img data-dz-thumbnail /></div><div class='dz-progress'><span class='dz-upload' data-dz-uploadprogress></span></div><div class='dz-success-mark'><span></span></div><div class='dz-error-mark'><span></span></div><div class='dz-error-message'><span data-dz-errormessage></span></div></div>",

      // add all files from uploads/ to dropzone when the page loads
      init: function() {
        getFiles(this);
      },
      // reload the page after a successful upload
      success: function() {
        location.reload();
        return;
      }
    });


    // Fancybox
    $(".dz-preview").fancybox();

    $(".dz-preview").fancybox({
      helpers: {
        title: null
      },
      transitionIn : "elastic",
      transitionOut : "elastic",
      padding: 0,
      speedIn : 600, 
      speedOut : 200,

      afterLoad : function(current) {
        var image = this.content[0].children[0].children[0];
        fileName = image.src;

        // make sure there is no horizontal scrolling
        image.style.width = Math.min(this.width, image.width) + 'px';

        // add report button
        var report = document.createElement('a');
        report.setAttribute('class', 'btn-red2');
        report.setAttribute('onClick', 'report()');
        report.appendChild(document.createTextNode('Report'));
        $(".fancybox-skin").append(report);
      },
      // reload the page when closing a file
      afterClose : function() {
        location.reload();
        return;
      }
    });

    $("#helpBtn").fancybox({
      padding: 0
    });
  }); 
  </script>
  
  <h1 id="title">PLOP</h1>
  <a class="fancybox fancybox.iframe" id="helpBtn" href="helppage.html">Help</a>

</body>
</html>
