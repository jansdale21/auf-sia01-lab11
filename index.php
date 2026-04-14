<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Movie Search Engine</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .hit-image {
      background-size: cover;
      background-position: center;
      background-color: #0f172a;
      aspect-ratio: 2 / 3;
      width: 100%;
      min-height: 0;
    }

    .hit-image.placeholder,
    .poster-placeholder {
      display: grid;
      place-items: center;
      background: #0f172a;
      color: #94a3b8;
      font-size: 0.95rem;
      aspect-ratio: 2 / 3;
      width: 100%;
      min-height: 0;
    }

    .ais-SearchBox-form {
      display: flex;
      gap: 0.75rem;
      width: 100%;
    }

    .ais-SearchBox-input {
      width: 100%;
      padding: 0.95rem 1rem;
      border-radius: 9999px;
      border: 1px solid rgba(148, 163, 184, 0.18);
      background: rgba(15, 23, 42, 0.92);
      color: #e2e8f0;
      outline: none;
      min-height: 3rem;
    }

    .ais-SearchBox-input::placeholder {
      color: #64748b;
    }

    .ais-SearchBox-submit,
    .ais-SearchBox-reset,
    .ais-ClearRefinements-button {
      border: none;
      border-radius: 9999px;
      background: #7c3aed;
      color: #ffffff;
      padding: 0 1rem;
      font-weight: 700;
      cursor: pointer;
      min-height: 3rem;
    }

    .ais-ClearRefinements-button {
      width: 100%;
    }

    .ais-MenuSelect-select {
      width: 100%;
      padding: 0.95rem 1rem;
      border-radius: 1rem;
      border: 1px solid rgba(148, 163, 184, 0.18);
      background: rgba(15, 23, 42, 0.92);
      color: #e2e8f0;
      font-size: 0.95rem;
      outline: none;
      appearance: none;
      min-height: 3rem;
    }

    aside {
      width: 100%;
      max-width: 320px;
    }

    .ais-SearchBox-form {
      display: flex;
      gap: 0.5rem;
      width: 100%;
      flex-wrap: wrap;
    }

    .ais-SearchBox-input {
      flex: 1 1 0;
    }

    .ais-RefinementList-list {
      list-style: none;
      margin: 0;
      padding: 0;
      display: grid;
      gap: 0.75rem;
      max-height: 62vh;
      overflow-y: auto;
    }

    .ais-RefinementList-item label {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.95rem 1rem;
      border-radius: 1.25rem;
      background: rgba(148, 163, 184, 0.06);
      color: #e2e8f0;
      cursor: pointer;
      border: 1px solid rgba(148, 163, 184, 0.12);
      min-height: 3rem;
    }

    .ais-RefinementList-item--selected label {
      background: rgba(124, 58, 237, 0.16);
      border-color: rgba(124, 58, 237, 0.32);
    }

    .ais-RefinementList-count {
      color: #94a3b8;
      font-size: 0.95rem;
    }

    .ais-Pagination {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      justify-content: center;
      align-items: center;
      padding: 1rem 0;
      margin: 0;
      list-style: none;
    }

    .ais-Pagination-item {
      display: inline-flex;
      margin: 0;
      padding: 0;
      list-style: none;
    }

    .ais-Pagination-link {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 3rem;
      height: 3rem;
      border-radius: 1rem;
      border: 1px solid rgba(148, 163, 184, 0.2);
      background: rgba(15, 23, 42, 0.85);
      color: #e2e8f0;
      text-decoration: none;
      font-weight: 600;
      transition: transform 0.2s ease, background 0.2s ease;
    }

    .ais-Pagination-link:hover {
      transform: translateY(-1px);
      background: rgba(124, 58, 237, 0.16);
    }

    .ais-Pagination-item--selected .ais-Pagination-link {
      background: #7c3aed;
      color: #ffffff;
      border-color: #7c3aed;
    }

    .overview {
      display: -webkit-box;
      -webkit-line-clamp: 4;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    #hits {
      display: block;
      width: 100%;
    }

    .ais-Hits-list {
      display: grid;
      gap: 1.25rem;
      grid-template-columns: repeat(1, minmax(0, 1fr));
      padding: 0;
      margin: 0;
      list-style: none;
      width: 100%;
    }

    .ais-Hits-item {
      width: 100%;
    }

    .ais-Hits-item > * {
      width: 100%;
    }

    @media (min-width: 640px) {
      .ais-Hits-list {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (min-width: 768px) {
      .ais-Hits-list {
        grid-template-columns: repeat(3, minmax(0, 1fr));
      }
    }

    @media (min-width: 1024px) {
      .ais-Hits-list {
        grid-template-columns: repeat(4, minmax(0, 1fr));
      }
    }

    @media (min-width: 1280px) {
      .ais-Hits-list {
        grid-template-columns: repeat(5, minmax(0, 1fr));
      }
    }

    #movie-detail-modal {
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 50;
    }

    #movie-detail-modal.active {
      display: flex;
    }
  </style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased">
  <div class="min-h-screen px-4 py-6 sm:px-6 lg:px-8">
    <div class="mx-auto grid max-w-[1680px] gap-6 grid-cols-1 xl:grid-cols-[280px_minmax(0,1fr)]">
      <aside class="w-full max-w-[320px] space-y-6 rounded-[36px] border border-slate-800/70 bg-slate-900/75 p-6 shadow-2xl shadow-slate-950/35 backdrop-blur-xl">
        <div class="space-y-2">
          <p class="text-xs font-semibold uppercase tracking-[0.32em] text-violet-400">MovieSearch</p>
          <h1 class="text-3xl font-black tracking-tight text-white">Browse by genre & year</h1>
        </div>

        <div class="space-y-3">
          <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Genres</p>
          <div id="genre-list"></div>
        </div>

        <div class="space-y-3">
          <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Year released</p>
          <div id="year-list"></div>
        </div>

        <div class="space-y-3">
          <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Reset filters</p>
          <div id="clear-refinements"></div>
        </div>
      </aside>

      <main class="space-y-6">
        <section class="rounded-[36px] border border-slate-800/70 bg-slate-900/70 p-8 shadow-2xl shadow-slate-950/35 backdrop-blur-xl">
          <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.32em] text-violet-400">Discover</p>
            <h2 class="mt-4 text-4xl font-black tracking-tight text-white">Discover movies instantly</h2>
            <p class="mt-4 max-w-2xl text-slate-400">Search through thousands of movies, filter by genre and release year, and explore rating plus overview details.</p>
          </div>

          <div class="mt-8" id="searchbox"></div>
        </section>

        <div class="rounded-[36px] border border-slate-800/70 bg-slate-900/70 p-5 shadow-2xl shadow-slate-950/35 backdrop-blur-xl">
          <div id="stats" class="text-sm text-slate-400"></div>
        </div>

        <section class="space-y-5" id="hits"></section>

        <div class="rounded-[36px] border border-slate-800/70 bg-slate-900/70 p-5 shadow-2xl shadow-slate-950/35 backdrop-blur-xl">
          <div id="pagination"></div>
        </div>
      </main>
    </div>
  </div>

  <div id="movie-detail-modal" class="fixed inset-0 z-50 items-center justify-center overflow-y-auto bg-slate-950/85 p-4">
    <div class="relative mx-auto w-full max-w-4xl overflow-hidden rounded-[32px] bg-slate-900 shadow-2xl shadow-slate-950/40">
      <button id="movie-detail-close" class="absolute right-4 top-4 rounded-full border border-slate-700/80 bg-slate-950/90 px-3 py-2 text-xl text-slate-100 transition hover:bg-slate-900">×</button>
      <div class="grid gap-6 p-6 lg:grid-cols-[320px_minmax(0,1fr)]">
        <div id="detail-poster" class="h-[0] rounded-[28px] bg-cover bg-center bg-slate-950/40 pb-[150%]"></div>
        <div class="space-y-4 text-slate-100">
          <div class="space-y-2">
            <p class="text-xs uppercase tracking-[0.32em] text-violet-400">Movie details</p>
            <h2 id="detail-title" class="text-3xl font-black"></h2>
            <p id="detail-meta" class="text-sm text-slate-400"></p>
          </div>
          <p id="detail-genres" class="text-sm text-slate-300"></p>
          <div class="rounded-[24px] border border-slate-800/70 bg-slate-950/90 p-4 text-sm leading-6 text-slate-300" id="detail-overview"></div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/algoliasearch@4/dist/algoliasearch.umd.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/instantsearch.js@4"></script>
  <script>
    const searchClient = algoliasearch('CSWSDEW39Q', 'c2a11ea57eebc711be5b9fda17c6f4d8');

    const search = instantsearch({
      indexName: 'movies',
      searchClient,
      routing: true,
    });

    const escapeHtml = (value) => {
      const stringValue = String(value || '');
      return stringValue.replace(/[&<>\"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '\"': '&quot;',
        "'": '&#39;'
      })[char]);
    };

    const getYear = (hit) => {
      if (hit.release_year) return hit.release_year;
      if (hit.release_date) return String(hit.release_date).slice(0, 4);
      return 'Unknown';
    };

    const hitTemplate = (hit) => {
      const title = hit.title || hit.name || 'No title';
      const rating = hit.vote_average || hit.rating || 'N/A';
      const poster = hit.poster_url || hit.poster || hit.image || '';
      const year = getYear(hit);
      const genres = Array.isArray(hit.genres) ? hit.genres.join(', ') : hit.genres || 'Unknown';
      const overview = hit.overview || hit.description || hit.plot || 'No description available.';

      return `
        <article class="hit-card cursor-pointer group overflow-hidden rounded-[28px] border border-slate-800/70 bg-slate-900/90 shadow-2xl shadow-slate-950/20 transition-transform duration-200 hover:-translate-y-1" data-hit='${escapeHtml(JSON.stringify(hit))}'>
          ${poster ? `<div class="hit-image w-full aspect-[2/3]" style="background-image:url('${escapeHtml(poster)}')"></div>` : '<div class="hit-image placeholder w-full aspect-[2/3]">No poster available</div>'}
          <div class="p-5">
            <h2 class="text-lg font-semibold text-white">${escapeHtml(title)}</h2>
            <p class="mt-2 text-sm text-slate-400">${escapeHtml(year)} · Rating ${escapeHtml(rating)}</p>
            <p class="mt-3 text-sm text-slate-300">Genre: ${escapeHtml(genres)}</p>
            <p class="mt-4 text-sm leading-6 text-slate-300 overview">${escapeHtml(overview)}</p>
          </div>
        </article>
      `;
    };

    search.addWidgets([
      instantsearch.widgets.searchBox({
        container: '#searchbox',
        placeholder: 'Search for movies...',
        showReset: false,
        showSubmit: true,
        searchAsYouType: true,
      }),

      instantsearch.widgets.clearRefinements({
        container: '#clear-refinements',
        templates: {
          resetLabel: 'Reset filters',
        },
      }),

      instantsearch.widgets.stats({
        container: '#stats',
      }),

      instantsearch.widgets.refinementList({
        container: '#genre-list',
        attribute: 'genres',
        operator: 'and',
        searchable: true,
        showMore: true,
        limit: 8,
        showMoreLimit: 30,
        sortBy: ['isRefined', 'count:desc', 'name:asc'],
        searchablePlaceholder: 'Search genres',
      }),

      instantsearch.widgets.menuSelect({
        container: '#year-list',
        attribute: 'release_year',
        limit: 20,
        sortBy: ['count:desc', 'name:desc'],
        templates: {
          defaultOption: 'All years',
        },
      }),

      instantsearch.widgets.hits({
        container: '#hits',
        templates: {
          item: hitTemplate,
          empty: '<div class="empty-state">No movies found. Try another keyword or clear the filters.</div>',
        },
      }),

      instantsearch.widgets.pagination({
        container: '#pagination',
      }),
    ]);

    const showMovieDetail = (event, hitData) => {
      event.stopPropagation();
      let hit;
      try {
        hit = typeof hitData === 'string' ? JSON.parse(hitData) : hitData;
      } catch (error) {
        return;
      }

      const title = hit.title || hit.name || 'No title';
      const year = getYear(hit);
      const rating = hit.vote_average || hit.rating || 'N/A';
      const poster = hit.poster_url || hit.poster || hit.image || '';
      const genres = Array.isArray(hit.genres) ? hit.genres.join(', ') : hit.genres || 'Unknown';
      const overview = hit.overview || hit.description || hit.plot || 'No description available.';

      document.getElementById('detail-title').textContent = title;
      document.getElementById('detail-meta').textContent = `${year} · Rating ${rating}`;
      document.getElementById('detail-genres').textContent = `Genre: ${genres}`;
      document.getElementById('detail-overview').textContent = overview;
      document.getElementById('detail-poster').style.backgroundImage = poster ? `url('${poster}')` : 'none';
      document.getElementById('detail-poster').textContent = poster ? '' : 'No poster available';
      const modal = document.getElementById('movie-detail-modal');
      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    };

    const closeMovieDetail = () => {
      const modal = document.getElementById('movie-detail-modal');
      modal.classList.remove('active');
      document.body.style.overflow = '';
    };

    document.getElementById('movie-detail-close').addEventListener('click', closeMovieDetail);
    document.getElementById('movie-detail-modal').addEventListener('click', (event) => {
      if (event.target.id === 'movie-detail-modal') {
        closeMovieDetail();
      }
    });

    document.getElementById('hits').addEventListener('click', (event) => {
      const hitCard = event.target.closest('.hit-card');
      if (hitCard && hitCard.dataset.hit) {
        showMovieDetail(event, hitCard.dataset.hit);
      }
    });

    search.start();
  </script>
</body>
</html>
