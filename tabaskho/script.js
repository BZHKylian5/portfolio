document.addEventListener("DOMContentLoaded", function () {
  var scrollLinks = document.querySelectorAll('.scroll');

  // Gestionnaire de clic pour chaque lien
  scrollLinks.forEach(function (link) {
    link.addEventListener('click', function (e) {
      e.preventDefault();

      var targetId = this.getAttribute('href').substring(1);
      var targetElement = document.getElementById(targetId);

      // Défilement en douceur vers la section cible
      window.scrollTo({
        top: targetElement.offsetTop,
        behavior: 'smooth'
      });
    });
  });
});

function showVideoPopup(videoUrl) {
  var videoPopup = document.getElementById('videoPopup');
  var iframe = videoPopup.querySelector('iframe');
  iframe.src = generateYouTubeEmbedUrl(videoUrl);
  videoPopup.style.display = 'block';
}

function closeVideoPopup() {
  var videoPopup = document.getElementById('videoPopup');
  var iframe = videoPopup.querySelector('iframe');
  iframe.src = '';
  videoPopup.style.display = 'none';
}

function generateYouTubeEmbedUrl(videoUrl) {
  // Extract video ID from the YouTube URL
  var videoId = videoUrl.match(/(?:youtu\.be\/|youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/)[1];
  return 'https://www.youtube.com/embed/' + videoId;
}