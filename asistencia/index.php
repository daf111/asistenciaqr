<html>
  <head>
    <title>Instascan &ndash; Demo</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="adapter.min.js"></script>
    <script type="text/javascript" src="vue.min.js"></script>
    <!-- <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script> -->
	<script type="text/javascript" src="instascan-1.0.0/instascan.min.js"></script>
	
	<script
	src="https://code.jquery.com/jquery-3.3.1.min.js"
	integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	crossorigin="anonymous"></script>
	
  </head>
  <body>
    <a href="https://github.com/schmich/instascan" style="display:none"><img style="position: absolute; top: 0; right: 0; border: 0; z-index: 1" src="https://camo.githubusercontent.com/365986a132ccd6a44c23a9169022c0b5c890c387/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f72696768745f7265645f6161303030302e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_right_red_aa0000.png"></a>
    <div id="app">
      <div class="sidebar">
        <section class="cameras" style="display:none">
          <h2>Cameras</h2>
          <ul>
            <li v-if="cameras.length === 0" class="empty">No cameras found</li>
            <li v-for="camera in cameras">
              <span v-if="camera.id == activeCameraId" :title="formatName(camera.name)" class="active">{{ formatName(camera.name) }}</span>
              <span v-if="camera.id != activeCameraId" :title="formatName(camera.name)">
                <a @click.stop="selectCamera(camera)">{{ formatName(camera.name) }}</a>
              </span>
            </li>
          </ul>
        </section>
        <section class="scans">
          <h2>Lecturas</h2>
          <ul v-if="scans.length === 0">
            <li class="empty">A&uacute;n no hay lecturas</li>
          </ul>
          <transition-group name="scans" tag="ul">
            <li v-for="scan in scans" :key="scan.date" :title="scan.content">{{ scan.content }}</li>
          </transition-group>
        </section>
      </div>
      <div class="preview-container">
        <video id="preview"></video>
      </div>
    </div>
    <script>
	var app = new Vue({
	  el: '#app',
	  data: {
		scanner: null,
		activeCameraId: null,
		cameras: [],
		scans: []
	  },
	  mounted: function () {
		var self = this;
		self.scanner = new Instascan.Scanner({ video: document.getElementById('preview'), scanPeriod: 5 });
		self.scanner.addListener('scan', function (content, image) {
		  //alert(content);
		  
		  /*
		  $.ajax({
			url: "getAlumno.php?l=" + content
		  }).then(function(data) {
			self.scans.unshift({ date: +(Date.now()), content: '# ' + content + " - " + data.content });
		  });
		  */
		  
		  self.scans.unshift({ date: +(Date.now()), content: content });
		});
		Instascan.Camera.getCameras().then(function (cameras) {
		  self.cameras = cameras;
		  if (cameras.length > 0) {
			self.activeCameraId = cameras[0].id;
			self.scanner.start(cameras[0]);
		  } else {
			alert('No se han encontrado cámaras');
			console.error('No cameras found.');
		  }
		}).catch(function (e) {
		  alert('Error no controlado');
		  console.error(e);
		});
	  },
	  methods: {
		formatName: function (name) {
		  return name || '(unknown)';
		},
		selectCamera: function (camera) {
		  this.activeCameraId = camera.id;
		  this.scanner.start(camera);
		}
	  }
	});
	</script>
  </body>
</html>