/* Pulang Lupa Crafting Tracker */
(function () {
  'use strict';

  var app = document.getElementById('plct-app');
  if (!app) return;

  var STORAGE_KEY = 'plct_state_v1';
  var recipes = [];
  var state = { tracked: {}, tab: 'browse', search: '', cat: 'all' };

  /* ---------- category labels ---------- */
  var GROUPS = {
    food: 'Pagkain', dessert: 'Panghimagas', drinks: 'Inumin', rekado: 'Rekado',
    items: 'Mga Gamit', uling: 'Uling', panday: 'Panday', doctor: 'Doktor',
    matadero: 'Matadero', armero: 'Armero', katutubo: 'Katutubo',
    ensayador: 'Ensayador', printing: 'Imprenta', lason: 'Lason'
  };
  function catGroup(cat) {
    var m = cat.match(/^([a-z]+?)_?(\d+)?$/i);
    return (m && GROUPS[m[1]]) ? m[1] : cat;
  }
  function catLabel(cat) {
    var m = cat.match(/^([a-z]+?)_?(\d+)?$/i);
    if (m && GROUPS[m[1]]) return GROUPS[m[1]] + (m[2] ? ' ' + m[2] : '');
    return cat.charAt(0).toUpperCase() + cat.slice(1);
  }
  function groupLabel(g) { return GROUPS[g] || (g.charAt(0).toUpperCase() + g.slice(1)); }

  /* ---------- persistence ---------- */
  function loadState() {
    try {
      var raw = localStorage.getItem(STORAGE_KEY);
      if (raw) {
        var s = JSON.parse(raw);
        if (s && typeof s.tracked === 'object') state.tracked = s.tracked;
      }
    } catch (e) { /* corrupted state: start fresh */ }
  }
  function saveState() {
    try { localStorage.setItem(STORAGE_KEY, JSON.stringify({ tracked: state.tracked })); } catch (e) {}
  }

  /* ---------- helpers ---------- */
  function recipeId(r) { return r.category + '::' + r.name; }
  function findRecipe(id) {
    for (var i = 0; i < recipes.length; i++) if (recipeId(recipes[i]) === id) return recipes[i];
    return null;
  }
  function esc(s) {
    return String(s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }
  function trackedCount() { return Object.keys(state.tracked).length; }

  function recipeProgress(id) {
    var t = state.tracked[id], r = findRecipe(id);
    if (!t || !r) return { need: 0, have: 0 };
    var need = 0, have = 0;
    r.ingredients.forEach(function (ing) {
      var n = ing.count * t.qty;
      need += n;
      have += Math.min(t.have[ing.name] || 0, n);
    });
    return { need: need, have: have };
  }

  /* ---------- actions ---------- */
  function toggleTrack(id) {
    if (state.tracked[id]) delete state.tracked[id];
    else state.tracked[id] = { qty: 1, have: {} };
    saveState(); render();
  }
  function setQty(id, qty) {
    var t = state.tracked[id];
    if (!t) return;
    t.qty = Math.max(1, Math.min(99, qty));
    saveState(); render();
  }
  function setHave(id, ing, val, max) {
    var t = state.tracked[id];
    if (!t) return;
    t.have[ing] = Math.max(0, Math.min(max, val));
    saveState(); render();
  }
  function clearAll() {
    if (!confirm('Alisin lahat ng tine-track na recipe?')) return;
    state.tracked = {};
    saveState(); render();
  }

  /* ---------- job/login status banner ---------- */
  function renderStatusBanner() {
    var cfg = window.PLCT || {};
    if (cfg.isAdmin) {
      return '<div class="plct-status plct-status-admin">Nakikita mo ang lahat ng recipe bilang staff.</div>';
    }
    if (cfg.loggedIn && cfg.jobLabel) {
      return '<div class="plct-status plct-status-job">Ipinapakita ang mga recipe ng: <strong>' + esc(cfg.jobLabel) + '</strong></div>';
    }
    if (cfg.loggedIn) {
      return '<div class="plct-status plct-status-guest">Naka-login ka, ngunit walang trabahong naka-link sa account mo &mdash; ang mga pangkalahatang recipe lang ang makikita.</div>';
    }
    return '<div class="plct-status plct-status-guest">May karagdagang recipe ang mga miyembro ng trabaho. <a href="/job-portal/">Mag-login</a> upang makita ang sa iyong trabaho.</div>';
  }

  /* ---------- rendering ---------- */
  function render() {
    var html = '';
    html += '<div class="plct-header"><h2 class="plct-title">Talaan ng Paggawa</h2>' +
            '<span class="plct-subtitle">Pulang Lupa RP Crafting Tracker</span></div>';
    html += renderStatusBanner();
    html += '<div class="plct-tabs">' +
      '<button class="plct-tab' + (state.tab === 'browse' ? ' active' : '') + '" data-tab="browse">Mga Recipe</button>' +
      '<button class="plct-tab' + (state.tab === 'track' ? ' active' : '') + '" data-tab="track">Tine-track ko' +
      (trackedCount() ? '<span class="plct-badge">' + trackedCount() + '</span>' : '') + '</button>' +
      '</div>';
    html += state.tab === 'browse' ? renderBrowse() : renderTracker();
    app.innerHTML = html;
    bind();
  }

  function renderBrowse() {
    var groups = [];
    recipes.forEach(function (r) {
      var g = catGroup(r.category);
      if (groups.indexOf(g) === -1) groups.push(g);
    });

    var html = '<div class="plct-toolbar">' +
      '<input class="plct-search" type="text" placeholder="Maghanap ng recipe o sangkap&hellip;" value="' + esc(state.search) + '">' +
      '</div>';
    html += '<div class="plct-cats"><button class="plct-cat' + (state.cat === 'all' ? ' active' : '') + '" data-cat="all">Lahat</button>';
    groups.forEach(function (g) {
      html += '<button class="plct-cat' + (state.cat === g ? ' active' : '') + '" data-cat="' + esc(g) + '">' + esc(groupLabel(g)) + '</button>';
    });
    html += '</div>';

    var q = state.search.toLowerCase();
    var list = recipes.filter(function (r) {
      if (state.cat !== 'all' && catGroup(r.category) !== state.cat) return false;
      if (!q) return true;
      if (r.name.toLowerCase().indexOf(q) !== -1) return true;
      return r.ingredients.some(function (ing) {
        return (ing.label || ing.name).toLowerCase().indexOf(q) !== -1;
      });
    });

    html += '<div class="plct-grid">';
    if (!list.length) html += '<div class="plct-empty">Walang nahanap na recipe.</div>';
    list.forEach(function (r) {
      var id = recipeId(r), tracked = !!state.tracked[id];
      html += '<div class="plct-card">' +
        '<div class="plct-card-head"><span class="plct-card-name">' + esc(r.name) + '</span>' +
        '<span class="plct-card-cat">' + esc(catLabel(r.category)) + '</span></div>' +
        '<ul class="plct-ing-list">';
      r.ingredients.forEach(function (ing) {
        html += '<li><span>' + esc(ing.label || ing.name) + '</span><span class="plct-ing-count">&times;' + ing.count + '</span></li>';
      });
      html += '</ul>' +
        '<button class="plct-track-btn' + (tracked ? ' tracked' : '') + '" data-track="' + esc(id) + '">' +
        (tracked ? '&#10003; Tine-track na &mdash; alisin' : '+ I-track ito') + '</button></div>';
    });
    html += '</div>';
    return html;
  }

  function renderTracker() {
    var ids = Object.keys(state.tracked);
    if (!ids.length) {
      return '<div class="plct-empty" style="display:block">Wala ka pang tine-track. Pumunta sa <strong>Mga Recipe</strong> at pindutin ang &ldquo;I-track ito&rdquo;.</div>';
    }

    var totNeed = 0, totHave = 0;
    ids.forEach(function (id) {
      var p = recipeProgress(id);
      totNeed += p.need; totHave += p.have;
    });
    var pct = totNeed ? Math.round(totHave / totNeed * 100) : 0;

    var html = '<div class="plct-overall"><div class="plct-overall-row">' +
      '<strong>Kabuuang progreso: ' + pct + '%</strong>' +
      '<span class="plct-subtitle">' + totHave + ' / ' + totNeed + ' na sangkap</span>' +
      '<button class="plct-clear-btn" data-clear="1">Alisin lahat</button></div>' +
      '<div class="plct-bar"><div class="plct-bar-fill' + (pct >= 100 ? ' done' : '') + '" style="width:' + pct + '%"></div></div></div>';

    /* shopping list */
    var agg = {};
    ids.forEach(function (id) {
      var r = findRecipe(id), t = state.tracked[id];
      if (!r) return;
      r.ingredients.forEach(function (ing) {
        var key = ing.name;
        if (!agg[key]) agg[key] = { label: ing.label || ing.name, need: 0, have: 0 };
        var n = ing.count * t.qty;
        agg[key].need += n;
        agg[key].have += Math.min(t.have[ing.name] || 0, n);
      });
    });
    var keys = Object.keys(agg).sort(function (a, b) { return agg[a].label.localeCompare(agg[b].label); });
    html += '<div class="plct-shop" style="margin-bottom:14px"><h3>Listahan ng Kailangan</h3><ul class="plct-shop-list">';
    keys.forEach(function (k) {
      var it = agg[k], done = it.have >= it.need;
      html += '<li class="' + (done ? 'have' : '') + '"><span>' + esc(it.label) + '</span>' +
        '<span class="plct-shop-need">' + (done ? '&#10003; kumpleto' : 'kulang pa: ' + (it.need - it.have)) + ' <em style="opacity:.6">(' + it.have + '/' + it.need + ')</em></span></li>';
    });
    html += '</ul></div>';

    /* tracked recipes */
    html += '<div class="plct-tracked">';
    ids.forEach(function (id) {
      var r = findRecipe(id), t = state.tracked[id];
      if (!r) return;
      var p = recipeProgress(id);
      var rpct = p.need ? Math.round(p.have / p.need * 100) : 0;
      var done = rpct >= 100;
      html += '<div class="plct-titem' + (done ? ' done' : '') + '">' +
        '<div class="plct-titem-head"><span class="plct-titem-name">' + esc(r.name) +
        (done ? '<span class="plct-done-tag">&#10003; handa nang lutuin!</span>' : '') + '</span>' +
        '<div class="plct-titem-actions">' +
        '<span class="plct-qty">Dami: <button data-qty="' + esc(id) + '" data-d="-1">&minus;</button><strong>' + t.qty + '</strong><button data-qty="' + esc(id) + '" data-d="1">+</button></span>' +
        '<button class="plct-remove" data-track="' + esc(id) + '" title="Alisin">&#10005;</button>' +
        '</div></div>' +
        '<div class="plct-bar"><div class="plct-bar-fill' + (done ? ' done' : '') + '" style="width:' + rpct + '%"></div></div>' +
        '<ul class="plct-check-list">';
      r.ingredients.forEach(function (ing) {
        var need = ing.count * t.qty;
        var have = Math.min(t.have[ing.name] || 0, need);
        var full = have >= need;
        html += '<li class="' + (full ? 'have' : '') + '">' +
          '<input type="checkbox" class="plct-check-toggle" data-chk="' + esc(id) + '" data-ing="' + esc(ing.name) + '" data-max="' + need + '"' + (full ? ' checked' : '') + '>' +
          '<span class="plct-ing-name">' + esc(ing.label || ing.name) + '</span>' +
          '<span class="plct-step">' +
          '<button data-step="' + esc(id) + '" data-ing="' + esc(ing.name) + '" data-d="-1" data-max="' + need + '"' + (have <= 0 ? ' disabled' : '') + '>&minus;</button>' +
          '<span class="plct-step-count">' + have + ' / ' + need + '</span>' +
          '<button data-step="' + esc(id) + '" data-ing="' + esc(ing.name) + '" data-d="1" data-max="' + need + '"' + (full ? ' disabled' : '') + '>+</button>' +
          '</span></li>';
      });
      html += '</ul></div>';
    });
    html += '</div>';
    return html;
  }

  /* ---------- events ---------- */
  function bind() {
    app.querySelectorAll('[data-tab]').forEach(function (el) {
      el.addEventListener('click', function () { state.tab = el.getAttribute('data-tab'); render(); });
    });
    app.querySelectorAll('[data-cat]').forEach(function (el) {
      el.addEventListener('click', function () { state.cat = el.getAttribute('data-cat'); render(); });
    });
    app.querySelectorAll('[data-track]').forEach(function (el) {
      el.addEventListener('click', function () { toggleTrack(el.getAttribute('data-track')); });
    });
    app.querySelectorAll('[data-clear]').forEach(function (el) {
      el.addEventListener('click', clearAll);
    });
    app.querySelectorAll('[data-qty]').forEach(function (el) {
      el.addEventListener('click', function () {
        var id = el.getAttribute('data-qty');
        var t = state.tracked[id];
        if (t) setQty(id, t.qty + parseInt(el.getAttribute('data-d'), 10));
      });
    });
    app.querySelectorAll('[data-step]').forEach(function (el) {
      el.addEventListener('click', function () {
        var id = el.getAttribute('data-step'), ing = el.getAttribute('data-ing');
        var max = parseInt(el.getAttribute('data-max'), 10);
        var t = state.tracked[id];
        if (!t) return;
        var cur = Math.min(t.have[ing] || 0, max);
        setHave(id, ing, cur + parseInt(el.getAttribute('data-d'), 10), max);
      });
    });
    app.querySelectorAll('[data-chk]').forEach(function (el) {
      el.addEventListener('change', function () {
        var id = el.getAttribute('data-chk'), ing = el.getAttribute('data-ing');
        var max = parseInt(el.getAttribute('data-max'), 10);
        setHave(id, ing, el.checked ? max : 0, max);
      });
    });
    var search = app.querySelector('.plct-search');
    if (search) {
      search.addEventListener('input', function () {
        state.search = search.value;
        var grid = app.querySelector('.plct-grid');
        if (grid) {
          var tmp = document.createElement('div');
          tmp.innerHTML = renderBrowse();
          var newGrid = tmp.querySelector('.plct-grid');
          grid.replaceWith(newGrid);
          bindGrid();
        }
      });
    }
  }
  function bindGrid() {
    app.querySelectorAll('.plct-grid [data-track]').forEach(function (el) {
      el.addEventListener('click', function () { toggleTrack(el.getAttribute('data-track')); });
    });
  }

  /* ---------- init ---------- */
  loadState();
  var url = (window.PLCT && window.PLCT.recipesUrl) || 'data/recipes.json';
  var fetchHeaders = {};
  if (window.PLCT && window.PLCT.nonce) fetchHeaders['X-WP-Nonce'] = window.PLCT.nonce;
  fetch(url, { headers: fetchHeaders, credentials: 'same-origin' })
    .then(function (r) { if (!r.ok) throw new Error(r.status); return r.json(); })
    .then(function (data) { recipes = data; render(); })
    .catch(function () {
      app.innerHTML = '<div class="plct-empty" style="display:block">Hindi ma-load ang mga recipe. Paki-refresh ang page.</div>';
    });
})();
