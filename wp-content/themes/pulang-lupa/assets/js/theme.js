(function () {
  'use strict';

  // ── Navbar: shrink on scroll ──────────────────────────────
  var header = document.getElementById('site-header');
  if (header) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 60) {
        header.style.background = 'rgba(0,0,0,0.97)';
        header.style.boxShadow  = '0 2px 20px rgba(0,0,0,0.6)';
      } else {
        header.style.background = '';
        header.style.boxShadow  = '';
      }
    }, { passive: true });
  }

  // ── Mobile nav toggle ─────────────────────────────────────
  var toggle = document.querySelector('.nav-toggle');
  var menu   = document.querySelector('.nav-menu');
  if (toggle && menu) {
    toggle.addEventListener('click', function () {
      var isOpen = menu.classList.toggle('open');
      toggle.setAttribute('aria-expanded', isOpen);
      toggle.querySelectorAll('span')[0].style.transform = isOpen ? 'rotate(45deg) translate(5px, 5px)' : '';
      toggle.querySelectorAll('span')[1].style.opacity   = isOpen ? '0' : '';
      toggle.querySelectorAll('span')[2].style.transform = isOpen ? 'rotate(-45deg) translate(5px, -5px)' : '';
    });
  }

  // ── Smooth scroll for anchor links ───────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      var target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        var offset = 70;
        var top    = target.getBoundingClientRect().top + window.pageYOffset - offset;
        window.scrollTo({ top: top, behavior: 'smooth' });
        if (menu) menu.classList.remove('open');
      }
    });
  });

  // ── Scroll reveal animation ──────────────────────────────
  var revealElements = document.querySelectorAll(
    '.feature-card, .step-card, .stat-card, .rule-item, .about-text, .about-stats'
  );

  if ('IntersectionObserver' in window && revealElements.length) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.style.opacity   = '1';
          entry.target.style.transform = 'translateY(0)';
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.05 });

    revealElements.forEach(function (el) {
      el.style.opacity    = '0';
      el.style.transform  = 'translateY(20px)';
      el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
      observer.observe(el);
    });

    setTimeout(function () {
      revealElements.forEach(function (el) {
        el.style.opacity   = '1';
        el.style.transform = 'translateY(0)';
      });
    }, 1500);
  }

  // ── Parallax (hero bg, about image, any [data-parallax]) ──
  // Note: intentionally NOT gated behind prefers-reduced-motion — the site
  // owner wants this effect to show regardless of the OS "reduce motion" setting.
  var parallaxEls = document.querySelectorAll('[data-parallax]');

  if (parallaxEls.length) {
    var vh = window.innerHeight;
    var pTicking = false;

    var applyParallax = function () {
      vh = window.innerHeight;
      parallaxEls.forEach(function (el) {
        var speed = parseFloat(el.getAttribute('data-parallax')) || 0.2;
        var scale = parseFloat(el.getAttribute('data-parallax-scale')) || 1;
        var rect  = el.getBoundingClientRect();
        var elCenter = rect.top + rect.height / 2;
        var y = (vh / 2 - elCenter) * speed;
        // Clamp movement to the overflow the scale provides, so edges never show
        var maxY = scale > 1 ? rect.height * (scale - 1) / 2 * 0.9 : 0;
        if (maxY) y = Math.max(-maxY, Math.min(maxY, y));
        el.style.transform = 'translate3d(0,' + y.toFixed(1) + 'px,0)' +
          (scale !== 1 ? ' scale(' + scale + ')' : '');
      });
      pTicking = false;
    };

    var onParallaxScroll = function () {
      if (!pTicking) {
        window.requestAnimationFrame(applyParallax);
        pTicking = true;
      }
    };

    window.addEventListener('scroll', onParallaxScroll, { passive: true });
    window.addEventListener('resize', applyParallax);
    applyParallax();
  }

  // ── RULES PAGE ────────────────────────────────────────────
  var rulesPage = document.getElementById('rules-page');

  // Reading progress bar
  var progressBar = document.getElementById('reading-progress-bar');
  if (progressBar) {
    var updateProgress = function () {
      var doc    = document.documentElement;
      var scroll = doc.scrollTop || document.body.scrollTop;
      var height = doc.scrollHeight - doc.clientHeight;
      progressBar.style.width = (height > 0 ? (scroll / height) * 100 : 0) + '%';
    };
    window.addEventListener('scroll', updateProgress, { passive: true });
    window.addEventListener('resize', updateProgress);
    updateProgress();
  }

  // Copy link buttons
  var toast = document.querySelector('.copy-toast');
  if (!toast) {
    toast = document.createElement('div');
    toast.className = 'copy-toast';
    toast.textContent = 'Link copied!';
    document.body.appendChild(toast);
  }

  document.querySelectorAll('.rule-copy-link').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var href = btn.getAttribute('data-href');
      var url  = window.location.origin + window.location.pathname + href;
      if (navigator.clipboard) {
        navigator.clipboard.writeText(url);
      } else {
        var ta = document.createElement('textarea');
        ta.value = url;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
      }
      toast.classList.add('show');
      setTimeout(function () { toast.classList.remove('show'); }, 2200);
    });
  });

  // Sidebar chapter accordion
  document.querySelectorAll('.rules-nav-chapter-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var chapter = btn.closest('.rules-nav-chapter');
      if (chapter) chapter.classList.toggle('open');
    });
  });

  // Rules search filter
  var rulesSearch = document.getElementById('rules-search');
  var noResults   = document.querySelector('.rules-no-results');
  if (rulesSearch) {
    rulesSearch.addEventListener('input', function () {
      var q         = rulesSearch.value.toLowerCase().trim();
      var navGroups = document.querySelectorAll('.rules-nav-chapter');
      var chapters  = document.querySelectorAll('.rule-chapter');
      var anyMatch  = false;

      // Sidebar: filter links against each link's own text AND its target
      // section's full body content, so a hit inside a rule's paragraphs/
      // lists (not just its title) still surfaces the link. Hide empty
      // chapter groups, auto-expand while searching.
      navGroups.forEach(function (group) {
        var visible = 0;
        group.querySelectorAll('.rules-nav-link').forEach(function (link) {
          var haystack = link.textContent.toLowerCase();
          var targetId = (link.getAttribute('href') || '').replace('#', '');
          var target   = targetId && document.getElementById(targetId);
          if (target) haystack += ' ' + target.textContent.toLowerCase();
          var match = !q || haystack.indexOf(q) !== -1;
          link.classList.toggle('hidden', !match);
          if (match) visible++;
        });
        group.classList.toggle('hidden', q && visible === 0);
        if (q && visible > 0) group.classList.add('open');
        if (visible > 0) anyMatch = true;
      });

      // Main content: filter rule cards, hide empty chapters
      chapters.forEach(function (chapter) {
        var secs    = chapter.querySelectorAll('.rule-section, .rules-afterword');
        var visible = 0;
        secs.forEach(function (sec) {
          var match = !q || sec.textContent.toLowerCase().indexOf(q) !== -1;
          sec.style.display = match ? '' : 'none';
          if (match) visible++;
        });
        chapter.style.display = (!q || visible > 0) ? '' : 'none';
      });

      if (noResults) noResults.style.display = (q && !anyMatch) ? 'block' : 'none';
    });
  }

  // Sidebar active-link scroll-spy + auto-open active chapter
  var ruleSections = document.querySelectorAll('.rule-section[id], .rules-afterword[id]');
  var ruleNavLinks = document.querySelectorAll('.rules-nav-link');
  if (ruleSections.length && ruleNavLinks.length) {
    var onRulesScroll = function () {
      var scrollY = window.pageYOffset + 130;
      var current = '';
      ruleSections.forEach(function (sec) {
        if (sec.offsetTop <= scrollY) current = sec.id;
      });
      ruleNavLinks.forEach(function (link) {
        var href     = (link.getAttribute('href') || '').replace('#', '');
        var isActive = href === current;
        link.classList.toggle('active', isActive);
        if (isActive) {
          var group = link.closest('.rules-nav-chapter');
          if (group && !rulesSearch.value.trim()) {
            document.querySelectorAll('.rules-nav-chapter.current').forEach(function (g) {
              if (g !== group) g.classList.remove('current');
            });
            group.classList.add('current', 'open');
          }
        }
      });
    };
    window.addEventListener('scroll', onRulesScroll, { passive: true });
    onRulesScroll();
  }

  // Back to top
  var backToTop = document.getElementById('rules-back-to-top');
  if (backToTop) {
    window.addEventListener('scroll', function () {
      backToTop.classList.toggle('show', window.pageYOffset > 600);
    }, { passive: true });
    backToTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // Print / Save PDF
  var printBtn = document.getElementById('rules-print');
  if (printBtn) {
    printBtn.addEventListener('click', function () { window.print(); });
  }

  // ── Custom page-flip handbook ─────────────────────────────
  var book = document.getElementById('plrp-book');
  if (book) {
    var leaves  = Array.prototype.slice.call(book.querySelectorAll('.flip-leaf'));
    var N       = leaves.length;
    var flipped = 0;
    var busy    = false;
    var bPrev   = document.getElementById('book-prev');
    var bNext   = document.getElementById('book-next');
    var bProg   = document.getElementById('book-progress');

    var setZ = function () {
      leaves.forEach(function (leaf, i) {
        leaf.style.zIndex = (i < flipped) ? (i + 1) : (N - i);
      });
    };
    var updateUI = function () {
      if (bPrev) bPrev.disabled = (flipped === 0);
      if (bNext) bNext.disabled = (flipped === N);
      if (bProg) {
        bProg.textContent = flipped === 0 ? 'Cover'
          : (flipped >= N ? 'Back Cover'
          : 'Pages ' + (flipped * 2) + '–' + (flipped * 2 + 1));
      }
    };
    var turn = function (dir) {
      if (busy) return;
      var next = flipped + dir;
      if (next < 0 || next > N) return;
      busy = true;
      var leaf = leaves[dir > 0 ? flipped : flipped - 1];
      leaf.style.zIndex = N + 5; // ride on top during the flip
      if (dir > 0) { leaf.classList.add('flipped'); flipped++; }
      else         { leaf.classList.remove('flipped'); flipped--; }
      updateUI();
      window.setTimeout(function () { setZ(); busy = false; }, 970);
    };

    if (bNext) bNext.addEventListener('click', function () { turn(1); });
    if (bPrev) bPrev.addEventListener('click', function () { turn(-1); });

    // Click a page half to turn (ignore clicks on real links/buttons)
    book.addEventListener('click', function (e) {
      if (e.target.closest('a, button')) return;
      var rect = book.getBoundingClientRect();
      turn((e.clientX - rect.left > rect.width / 2) ? 1 : -1);
    });

    // Arrow keys, only while the book is on screen
    document.addEventListener('keydown', function (e) {
      if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft') return;
      var r = book.getBoundingClientRect();
      if (r.bottom < 80 || r.top > window.innerHeight - 80) return;
      e.preventDefault();
      turn(e.key === 'ArrowRight' ? 1 : -1);
    });

    setZ();
    updateUI();
  }

})();
