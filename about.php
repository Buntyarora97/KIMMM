<?php
require_once 'includes/config.php';
$pageTitle       = 'Trusted Ayurvedic Brand | Pure Herbal Products India';
$metaDescription = 'Discover the story behind India\'s premium ayurvedic brand. We bring you pure, safe herbal supplements and natural health products for holistic wellness.';
$metaKeywords    = 'ayurvedic brand india, herbal company india, natural wellness brand, ayurvedic manufacturer, trusted products india';
$canonicalUrl    = 'https://livvra.in/best-ayurvedic-brand-india';
require_once 'includes/header.php';
?>


<!-- STEP WISE IMAGE SECTION -->
<section class="livvra-steps">

  <div class="livvra-step">
    <img src="assets/images/about/1.jpeg" alt="Step 01">
  </div>

  <div class="livvra-step" style="width:100%; height:100%; overflow:hidden;">
    <video 
      src="assets/images/about/2.mp4"
      autoplay
      muted
      loop
      playsinline
      style="
        width:100%;
        height:auto;
        object-fit:contain;
        display:block;
      "
    ></video>
  </div>

  <div class="livvra-step">
    <img src="assets/images/about/3.jpeg" alt="Step 03">
  </div>

  <div class="livvra-step">
    <img src="assets/images/about/4.jpeg" alt="Step 04">
  </div>

  <div class="livvra-step" style="width:100%; height:100%; overflow:hidden;">
    <video 
      src="assets/images/about/5.mp4"
      autoplay
      muted
      loop
      playsinline
      style="
        width:100%;
        height:auto;
        object-fit:contain;
        display:block;
      "
    ></video>
  </div>

  <div class="livvra-step">
    <img src="assets/images/about/6.jpeg" alt="Step 06">
  </div>

</section>

<style>
/* ===== LIVVRA VERTICAL STEPS ===== */
.livvra-steps {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 0;
  background: #fff;

  /* 🔥 LINE REMOVE FIX */
  margin-top: 0 !important;
  padding-top: 0 !important;
  border-top: none !important;
}

/* Each step */
.livvra-step {
  width: 100%;
  overflow: hidden;
  border: none;
}

/* Images */
.livvra-step img {
  width: 100%;
  height: auto;
  display: block;
  object-fit: contain;
}

/* Videos (same as image) */
.livvra-step video {
  width: 100%;
  height: auto;
  display: block;
  object-fit: contain;
}

/* 🔥 Global HR / border safety (line wahi se aa rahi thi) */
hr {
  display: none !important;
}

/* Mobile safe */
@media (max-width: 768px) {
  .livvra-step img,
  .livvra-step video {
    width: 100%;
    height: auto;
  }
}
</style>


<?php require_once 'includes/footer.php'; ?>
