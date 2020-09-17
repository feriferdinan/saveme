<!DOCTYPE html>
<html lang="en">

<head>
  <title>SAVE-ME - Instagram, Facebook, Twitter Photo,Video, IGTV Downloader</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,700,800" rel="stylesheet">

  <link rel="stylesheet" href="<?= BASEURL ?>css/open-iconic-bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASEURL ?>css/animate.css">

  <link rel="stylesheet" href="<?= BASEURL ?>css/owl.carousel.min.css">
  <link rel="stylesheet" href="<?= BASEURL ?>css/owl.theme.default.min.css">
  <link rel="stylesheet" href="<?= BASEURL ?>css/magnific-popup.css">

  <link rel="stylesheet" href="<?= BASEURL ?>css/aos.css">

  <link rel="stylesheet" href="<?= BASEURL ?>css/ionicons.min.css">

  <link rel="stylesheet" href="<?= BASEURL ?>css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="<?= BASEURL ?>css/jquery.timepicker.css">


  <link rel="stylesheet" href="<?= BASEURL ?>css/flaticon.css">
  <link rel="stylesheet" href="<?= BASEURL ?>css/icomoon.css">
  <link rel="stylesheet" href="<?= BASEURL ?>css/style.css">
  <?php if (isset($data["js"])) foreach ($data["js"] as $key => $value) {
    $this->view($value);
  } ?>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="index.html">SAVE-ME</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="oi oi-menu"></span> Menu
      </button>

      <div class="collapse navbar-collapse" id="ftco-nav">
        <!-- <ul class="navbar-nav ml-auto">
          <li class="nav-item active"><a href="index.html" class="nav-link">Home</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="portfolio.html" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Contact Me</a>
            <div class="dropdown-menu" aria-labelledby="dropdown04">
              <a class="dropdown-item" href="portfolio.html">Portfolio</a>
            </div>
          </li>
          <li class="nav-item "><a href="index.html" class="nav-link">Tutorial</a></li>
        </ul> -->
      </div>
    </div>
  </nav>
  <!-- END nav -->

  <!-- <div class="js-fullheight"> -->
  <div class="hero-wrap js-fullheight">
    <div class="overlay"></div>
    <div id="particles-js"></div>
    <div class="container">

      <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
        <div class="col-md-6 ftco-animate text-center" data-scrollax=" properties: { translateY: '70%' }">
          <h1 class="mb-4" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><strong>Social Media</strong> Photo, Video, and IGTV Downloader</h1>
          <div class="col-md-12">
            <div class="form-group">
              <select class="form-control" id="app">
                <option value="instagram">Instagram (Foto, Video, or IGTV)</option>
                <option value="facebook">Facebook (Video only)</option>
                <option disabled value="twitter">Twitter (Video only) comingsoon</option>
              </select>
            </div>
            <div class="form-group mb-3">
              <input id="url" placeholder="Link" class="form-control">
            </div>
            <p data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><button class="btn btn-primary btn-outline-white px-5 py-3 js-download">Download</button></p>

            <div class="error-content"></div>
            <div class="result-content">

            </div>

          </div>
        </div>
      </div>

    </div>

  </div>


  <script src="<?= BASEURL ?>js/jquery.min.js"></script>
  <script src="<?= BASEURL ?>js/jquery-migrate-3.0.1.min.js"></script>
  <script src="<?= BASEURL ?>js/popper.min.js"></script>
  <script src="<?= BASEURL ?>js/bootstrap.min.js"></script>
  <script src="<?= BASEURL ?>js/jquery.easing.1.3.js"></script>
  <script src="<?= BASEURL ?>js/jquery.waypoints.min.js"></script>
  <script src="<?= BASEURL ?>js/jquery.stellar.min.js"></script>
  <script src="<?= BASEURL ?>js/owl.carousel.min.js"></script>
  <script src="<?= BASEURL ?>js/jquery.magnific-popup.min.js"></script>
  <script src="<?= BASEURL ?>js/aos.js"></script>
  <script src="<?= BASEURL ?>js/jquery.animateNumber.min.js"></script>
  <script src="<?= BASEURL ?>js/bootstrap-datepicker.js"></script>
  <script src="<?= BASEURL ?>js/particles.min.js"></script>
  <script src="<?= BASEURL ?>js/particle.js"></script>
  <script src="<?= BASEURL ?>js/scrollax.min.js"></script>
  <script src="<?= BASEURL ?>js/main.js"></script>


  <script>
    $(document).ready(function() {

      function showContent(app, data) {
        html = ``
        if (app == "facebook") {
          html = ` <div class="row">
                <div class="col-md-6 ">
                  <h2 class="mb-1" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Video SD</h2>
                  <video controls="" loop=""  src="${data.data.videoSD}" height="160"> Maaf, tampaknya ada yang tidak beres </video>
                </div>
                <div class="col-md-6  ">
                  <h2 class="mb-1" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Video HD</h2>
                  <video controls="" loop=""  src="${data.data.videoHD}" height="160"> Maaf, tampaknya ada yang tidak beres </video>
                </div>
              </div>`
        } else {
          if (data.type == "video") {
            html = `<div class="col-md-12 ">
                  <video controls="" loop=""  src="${data.data}" height="160"> Maaf, tampaknya ada yang tidak beres </video>
                </div>`
          } else {

            html = `<p data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><a href="${data.data}" class="btn btn-primary btn-outline-white px-5 py-3 ">Download Image</a></p>`

          }
        }
        $(".result-content").html(html)
      }

      function errorContent(message) {
        $(".error-content").html(`<h2 class="mb-1" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">${message}</h2>`)
      }
      $(".js-download").click(function(e) {
        e.preventDefault()

        $(".result-content").html("")
        let app = $("#app").val()
        let url = $("#url").val()
        if (url.length == 0) {
          errorContent("Link is required")
          return
        }
        $(this).prop("disabled", true)
        $(this).html("Loading...")
        errorContent("")
        $.post(`<?= BASEURL ?>home/download`, {
          app,
          url
        }).done(resp => {
          $(this).prop("disabled", false)
          $(this).html("Download")
          let res = JSON.parse(resp)
          console.log(res);
          if (res.status) {
            showContent(app, res)
          } else {
            errorContent(res.message)
          }
        }).fail(err => {
          $(this).prop("disabled", false)
          $(this).html("Download")
          errorContent(err.message)
          console.log(err);
        })
      })

    });
  </script>

</body>

</html>