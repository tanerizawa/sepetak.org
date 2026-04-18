import './bootstrap';

function initHomeArticles() {
    const root = document.getElementById('home-articles');
    if (!root) return;

    const endpoint = root.getAttribute('data-endpoint');
    if (!endpoint) return;

    const grid = root.querySelector('.home-articles-grid');
    const sentinel = root.querySelector('.home-articles-sentinel');
    const skeletonTpl = root.querySelector('#home-article-skeleton');
    const moreBtn = root.querySelector('.home-articles-more');
    const tabs = Array.from(root.querySelectorAll('.home-article-tab'));

    if (!grid || !sentinel || !skeletonTpl) return;

    let activeCategory = '';
    let nextPageUrl = root.getAttribute('data-next-page-url') || '';
    let isLoading = false;

    function homeUrlForState({ category = '', page = '' } = {}) {
        const u = new URL(window.location.href);
        u.hash = 'home-articles';
        if (category) {
            u.searchParams.set('category', category);
        } else {
            u.searchParams.delete('category');
        }
        if (page && page !== '1') {
            u.searchParams.set('page', page);
        } else {
            u.searchParams.delete('page');
        }
        return u.toString();
    }

    function syncMoreButton() {
        if (!moreBtn) return;
        const hasMore = Boolean(nextPageUrl);
        moreBtn.classList.toggle('hidden', !hasMore);
        moreBtn.disabled = !hasMore || isLoading;
    }

    function setActiveTab(el) {
        for (const t of tabs) {
            const selected = t === el;
            t.setAttribute('aria-selected', selected ? 'true' : 'false');
            t.classList.toggle('bg-ink-900', selected);
            t.classList.toggle('text-paper-50', selected);
            t.classList.toggle('border-ink-900', selected);

            t.classList.toggle('bg-paper-50', !selected);
            t.classList.toggle('text-ink-900', !selected);
        }
    }

    function clearGrid() {
        grid.innerHTML = '';
    }

    function appendSkeleton(count) {
        const frag = document.createDocumentFragment();
        for (let i = 0; i < count; i++) {
            const node = skeletonTpl.content.cloneNode(true);
            frag.appendChild(node);
        }
        grid.appendChild(frag);
    }

    function removeSkeleton() {
        const items = grid.querySelectorAll('.animate-pulse');
        for (const el of items) {
            const card = el.closest('div.overflow-hidden');
            if (card) card.remove();
        }
    }

    async function load(url, { replace = false } = {}) {
        if (isLoading) return;
        if (!url) return;

        isLoading = true;
        syncMoreButton();

        if (replace) {
            clearGrid();
            appendSkeleton(6);
        }

        try {
            const res = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!res.ok) {
                throw new Error('Request failed');
            }

            const data = await res.json();
            const html = data.html || '';
            const next = data.next_page_url || '';

            if (replace) {
                clearGrid();
            }

            if (html) {
                const tmp = document.createElement('div');
                tmp.innerHTML = html;
                for (const child of Array.from(tmp.children)) {
                    grid.appendChild(child);
                }
            }

            nextPageUrl = next || '';
        } catch (_) {
            if (replace) {
                clearGrid();
            }
            nextPageUrl = '';
        } finally {
            removeSkeleton();
            isLoading = false;
            syncMoreButton();
        }
    }

    function buildUrl(category, page = '') {
        const u = new URL(endpoint, window.location.origin);
        if (category) {
            u.searchParams.set('category', category);
        }
        if (page) {
            u.searchParams.set('page', page);
        }
        return u.toString();
    }

    if (moreBtn) {
        moreBtn.addEventListener('click', () => {
            if (!nextPageUrl || isLoading) return;
            load(nextPageUrl);
        });
    }

    root.addEventListener('click', (e) => {
        const target = e.target;
        if (!(target instanceof Element)) return;

        const tab = target.closest('.home-article-tab');
        if (tab) {
            e.preventDefault();
            const category = tab.getAttribute('data-category') || '';
            activeCategory = category;
            setActiveTab(tab);
            nextPageUrl = '';
            window.history.pushState({}, '', homeUrlForState({ category, page: '1' }));
            load(buildUrl(activeCategory), { replace: true });
            return;
        }

        const pageLink = target.closest('.home-articles-pagination a');
        if (pageLink) {
            const href = pageLink.getAttribute('href') || '';
            if (!href) return;

            e.preventDefault();
            const u = new URL(href, window.location.origin);
            const page = u.searchParams.get('page') || '1';
            window.history.pushState({}, '', homeUrlForState({ category: activeCategory, page }));
            nextPageUrl = '';
            load(buildUrl(activeCategory, page), { replace: true });
        }
    });

    const obs = new IntersectionObserver((entries) => {
        const entry = entries[0];
        if (!entry || !entry.isIntersecting) return;
        if (!nextPageUrl || isLoading) return;
        load(nextPageUrl);
    }, { root: null, rootMargin: '200px 0px', threshold: 0 });

    obs.observe(sentinel);
    syncMoreButton();
}

document.addEventListener('DOMContentLoaded', () => {
    initHomeArticles();
});
