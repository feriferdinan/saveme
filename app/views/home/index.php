<!DOCTYPE html>
<html lang="en">

<head>
  <title>SAVE-ME - Instagram, Facebook, Twitter Photo,Video, IGTV Downloader</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,700,800" rel="stylesheet">

  <link rel="stylesheet" href="<?= BASEURL ?>css/animate.css">
  <link rel="stylesheet" href="<?= BASEURL ?>css/styleheadline.css">
  <link rel="stylesheet" href="<?= BASEURL ?>css/style.css">

</head>

<body>
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
      <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
      <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00" /></svg></div>

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
          <h1 style="color:white" class="" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
            <span class="cd-headline clip">
              <span class="cd-words-wrapper">
                <b class="is-visible"><strong>Instagram</strong></b>
                <b><strong>Facebook</strong></b>
                <b><strong>Twitter</strong></b>
              </span>
            </span>
          </h1>
          <h3 data-scrollax="properties: { translateY: '30%', opacity: 1.6 }" style="color:white" class="mb-4">Photo, Video, and IGTV Downloader</h3>
          <div class="col-md-12">
            <div class="form-group">
              <select class="form-control" id="app">
                <option value="instagram">Instagram (Foto, Video, or IGTV)</option>
                <option value="facebook">Facebook (Video)</option>
                <option value="twitter">Twitter (Video) </option>
              </select>
            </div>
            <div class="form-group mb-3">
              <input id="url" placeholder="Link" class="form-control">
            </div>
            <p data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><button class="btn btn-primary btn-outline-white px-5 py-3 js-download">Generate Download Link</button></p>

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
  <script src="<?= BASEURL ?>js/bootstrap.min.js"></script>
  <script src="<?= BASEURL ?>js/jquery.waypoints.min.js"></script>
  <script src="<?= BASEURL ?>js/particles.min.js"></script>
  <script src="<?= BASEURL ?>js/particle.js"></script>
  <script src="<?= BASEURL ?>js/jquery.animatedheadline.js"></script>
  <script src="<?= BASEURL ?>js/main.js"></script>

  <?php if (isset($data["js"])) foreach ($data["js"] as $key => $value) {
    $this->view($value);
  } ?>


</body>

</html>