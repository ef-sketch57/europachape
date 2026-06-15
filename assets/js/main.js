/* Europachape — interactions front (vanilla JS, sans dépendance) */
(function () {
  'use strict';

  /* --- Menu mobile -------------------------------------------------- */
  var toggle = document.querySelector('.nav__toggle');
  var links = document.getElementById('nav-links');
  if (toggle && links) {
    toggle.addEventListener('click', function () {
      var open = links.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    links.addEventListener('click', function (e) {
      if (e.target.tagName === 'A') {
        links.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  /* --- Lightbox galerie (page détail référence) --------------------- */
  var galleryLinks = document.querySelectorAll('[data-lightbox]');
  if (galleryLinks.length) {
    var box = document.createElement('div');
    box.className = 'lightbox';
    box.setAttribute('role', 'dialog');
    box.setAttribute('aria-modal', 'true');
    box.innerHTML =
      '<button class="lightbox__close" aria-label="Fermer">&times;</button>' +
      '<img class="lightbox__img" alt="">';
    box.style.cssText =
      'position:fixed;inset:0;z-index:999;display:none;align-items:center;' +
      'justify-content:center;background:rgba(7,42,70,.92);padding:5vw;';
    document.body.appendChild(box);

    var img = box.querySelector('.lightbox__img');
    img.style.cssText = 'max-width:100%;max-height:90vh;border-radius:10px;box-shadow:0 20px 60px rgba(0,0,0,.5);';
    var closeBtn = box.querySelector('.lightbox__close');
    closeBtn.style.cssText =
      'position:absolute;top:1.2rem;right:1.6rem;font-size:2.5rem;line-height:1;' +
      'background:none;border:0;color:#fff;cursor:pointer;';

    function open(src, alt) {
      img.src = src;
      img.alt = alt || '';
      box.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
    function close() {
      box.style.display = 'none';
      img.src = '';
      document.body.style.overflow = '';
    }

    galleryLinks.forEach(function (a) {
      a.addEventListener('click', function (e) {
        e.preventDefault();
        open(a.getAttribute('href'), a.querySelector('img') ? a.querySelector('img').alt : '');
      });
    });
    closeBtn.addEventListener('click', close);
    box.addEventListener('click', function (e) { if (e.target === box) close(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
  }
})();
