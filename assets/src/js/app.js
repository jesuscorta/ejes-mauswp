document.documentElement.classList.add('js');

const initMobileMenu = () => {
  const toggleButton = document.querySelector('[data-mobile-menu-toggle]');
  const panel = document.querySelector('[data-mobile-menu-panel]');
  const header = document.querySelector('[data-site-header]');

  if (!toggleButton || !panel || !header) {
    return;
  }

  const syncPanelOffset = () => {
    const headerHeight = Math.round(header.getBoundingClientRect().height);

    header.style.setProperty('--mobile-header-height', `${headerHeight}px`);
  };

  const closeMenu = () => {
    toggleButton.setAttribute('aria-expanded', 'false');
    panel.classList.remove('is-open');
  };

  const openMenu = () => {
    syncPanelOffset();
    toggleButton.setAttribute('aria-expanded', 'true');
    panel.classList.add('is-open');
  };

  toggleButton.addEventListener('click', () => {
    const isOpen = toggleButton.getAttribute('aria-expanded') === 'true';

    if (isOpen) {
      closeMenu();
      return;
    }

    openMenu();
  });

  // Close menu when clicking nav links, but NOT when clicking the catalog accordion trigger
  panel.querySelectorAll('nav a, .mobile-catalog-menu__panel a').forEach((link) => {
    link.addEventListener('click', closeMenu);
  });

  window.addEventListener('resize', () => {
    syncPanelOffset();

    if (window.innerWidth >= 1024) {
      closeMenu();
    }
  });

  syncPanelOffset();
};

const initMobileCatalogAccordion = () => {
  const trigger = document.querySelector('[data-mobile-catalog-trigger]');
  const panel = document.querySelector('[data-mobile-catalog-panel]');

  if (!trigger || !panel) {
    return;
  }

  const toggleAccordion = () => {
    const isExpanded = trigger.getAttribute('aria-expanded') === 'true';

    if (isExpanded) {
      trigger.setAttribute('aria-expanded', 'false');
      panel.setAttribute('hidden', 'hidden');
    } else {
      trigger.setAttribute('aria-expanded', 'true');
      panel.removeAttribute('hidden');
    }
  };

  trigger.addEventListener('click', (event) => {
    event.preventDefault();
    event.stopPropagation();
    toggleAccordion();
  });
};

const initCatalogMegaMenu = () => {
  const root = document.querySelector('[data-catalog-mega-root]');
  const panel = document.querySelector('[data-catalog-mega-panel]');

  if (!root || !panel) {
    return;
  }

  const triggerItem = root.querySelector('[data-catalog-mega-trigger]');

  if (!triggerItem) {
    return;
  }

  const openPanel = () => {
    triggerItem.setAttribute('aria-expanded', 'true');
    panel.classList.add('is-open');
  };

  const closePanel = () => {
    triggerItem.setAttribute('aria-expanded', 'false');
    panel.classList.remove('is-open');
  };

  triggerItem.addEventListener('click', (event) => {
    if (window.innerWidth < 640) {
      return;
    }

    event.preventDefault();

    if (panel.classList.contains('is-open')) {
      closePanel();
      return;
    }

    openPanel();
  });

  document.addEventListener('click', (event) => {
    const target = event.target;

    if (target instanceof Node && root.contains(target)) {
      return;
    }

    closePanel();
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closePanel();
    }
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth < 1024) {
      closePanel();
    }
  });
};

const initHeroCarousel = () => {
  const carousels = document.querySelectorAll('[data-hero-carousel]');

  carousels.forEach((carousel) => {
    const slides = Array.from(carousel.querySelectorAll('[data-hero-slide]'));
    const dots = Array.from(carousel.querySelectorAll('[data-hero-dot]'));
    const prevButton = carousel.querySelector('[data-hero-prev]');
    const nextButton = carousel.querySelector('[data-hero-next]');

    if (slides.length <= 1) {
      return;
    }

    let currentIndex = slides.findIndex((slide) => slide.classList.contains('is-active'));

    if (currentIndex < 0) {
      currentIndex = 0;
    }

    const setActiveSlide = (nextIndex) => {
      slides.forEach((slide, index) => {
        const isActive = index === nextIndex;

        slide.classList.toggle('is-active', isActive);
        slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
      });

      dots.forEach((dot, index) => {
        const isActive = index === nextIndex;

        dot.classList.toggle('is-active', isActive);
        dot.setAttribute('aria-pressed', isActive ? 'true' : 'false');
      });

      currentIndex = nextIndex;
    };

    prevButton?.addEventListener('click', () => {
      const nextIndex = currentIndex === 0 ? slides.length - 1 : currentIndex - 1;
      setActiveSlide(nextIndex);
    });

    nextButton?.addEventListener('click', () => {
      const nextIndex = currentIndex === slides.length - 1 ? 0 : currentIndex + 1;
      setActiveSlide(nextIndex);
    });

    dots.forEach((dot) => {
      dot.addEventListener('click', () => {
        const nextIndex = Number.parseInt(dot.dataset.slideIndex || '0', 10);
        setActiveSlide(nextIndex);
      });
    });
  });
};

const initProductSwiper = () => {
  if (typeof window.Swiper !== 'function') {
    return;
  }

  const carousels = document.querySelectorAll('[data-product-swiper]');

  carousels.forEach((carousel) => {
    const swiperElement = carousel.querySelector('.product-showcase-block__swiper');
    const prevButton = carousel.querySelector('[data-product-prev]');
    const nextButton = carousel.querySelector('[data-product-next]');

    if (!swiperElement) {
      return;
    }

    // eslint-disable-next-line no-new
    new window.Swiper(swiperElement, {
      slidesPerView: 1.15,
      spaceBetween: 24,
      speed: 550,
      navigation: {
        prevEl: prevButton,
        nextEl: nextButton
      },
      breakpoints: {
        640: {
          slidesPerView: 2
        },
        1024: {
          slidesPerView: 4
        }
      }
    });
  });
};

const initFeaturesStripSwiper = () => {
  if (typeof window.Swiper !== 'function') {
    return;
  }

  const carousels = document.querySelectorAll('[data-features-strip-swiper]');

  carousels.forEach((carousel) => {
    if (window.innerWidth >= 1024) {
      return;
    }

    // eslint-disable-next-line no-new
    new window.Swiper(carousel, {
      slidesPerView: 1.12,
      spaceBetween: 18,
      speed: 500,
      breakpoints: {
        640: {
          slidesPerView: 2.15,
          spaceBetween: 20
        }
      }
    });
  });
};

const initProductConfigInfoModal = () => {
  const modals = document.querySelectorAll('[data-product-config-info-modal]');

  modals.forEach((modal) => {
    const openButton = document.querySelector(`[aria-controls="${modal.id}"]`);
    const closeButtons = modal.querySelectorAll('[data-product-config-info-close]');

    if (!openButton) {
      return;
    }

    const closeModal = () => {
      modal.setAttribute('hidden', 'hidden');
      openButton.setAttribute('aria-expanded', 'false');
    };

    const openModal = () => {
      modal.removeAttribute('hidden');
      openButton.setAttribute('aria-expanded', 'true');
    };

    openButton.addEventListener('click', openModal);

    closeButtons.forEach((closeButton) => {
      closeButton.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && !modal.hasAttribute('hidden')) {
        closeModal();
      }
    });
  });
};

const initHeaderSearch = () => {
  const searchRoot = document.querySelector('[data-header-search]');

  if (!searchRoot) {
    return;
  }

  const trigger = searchRoot.querySelector('[data-header-search-trigger]');
  const panel = searchRoot.querySelector('[data-header-search-panel]');
  const closeButton = searchRoot.querySelector('[data-header-search-close]');
  const input = searchRoot.querySelector('[data-header-search-input]');
  const resultsContainer = searchRoot.querySelector('[data-header-search-results]');
  const suggestionsSection = searchRoot.querySelector('[data-header-search-suggestions]');
  const lists = searchRoot.querySelectorAll('[data-header-search-list]');
  const emptyState = searchRoot.querySelector('[data-header-search-empty]');

  if (!trigger || !panel || !input) {
    return;
  }

  const nonceField = document.querySelector('#mauswp_search_nonce');
  const nonce = nonceField ? nonceField.value : '';
  const ajaxUrl = (window.mauswpData && window.mauswpData.ajaxUrl) || (window.location.origin + '/wp-admin/admin-ajax.php');
  const noResultsText = (window.mauswpData && window.mauswpData.searchNoResultsText) || 'No hay productos para';

  let searchTimeout = null;
  let isLoading = false;
  let selectedIndex = -1;
  let currentResults = [];

  const debounce = (fn, delay) => {
    return (...args) => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => fn(...args), delay);
    };
  };

  const openPanel = () => {
    trigger.setAttribute('aria-expanded', 'true');
    panel.removeAttribute('hidden');
    input.focus();
    loadSuggestions();
  };

  const closePanel = () => {
    trigger.setAttribute('aria-expanded', 'false');
    panel.setAttribute('hidden', 'hidden');
    input.value = '';
    clearResults();
  };

  const clearResults = () => {
    selectedIndex = -1;
    currentResults = [];
    lists.forEach((list) => {
      list.innerHTML = '';
    });
    if (emptyState) {
      emptyState.setAttribute('hidden', 'hidden');
    }
    suggestionsSection.setAttribute('hidden', 'hidden');
    // Remove any dynamic suggestion heading
    const existingHeading = searchRoot.querySelector('.header-search__suggestion-heading');
    if (existingHeading) {
      existingHeading.remove();
    }
  };

  const renderResultItem = (item) => {
    const imageHtml = item.image_url
      ? `<img src="${item.image_url}" alt="" class="header-search__result-thumb" loading="lazy">`
      : '<div class="header-search__result-thumb header-search__result-thumb--placeholder"></div>';

    const priceHtml = item.price_html
      ? `<span class="header-search__result-price">${item.price_html}</span>`
      : '';

    const categoryHtml = item.category
      ? `<span class="header-search__result-category">${item.category}</span>`
      : '';

    return `
      <a href="${item.url}" class="header-search__result-item" role="option" data-header-search-item>
        ${imageHtml}
        <div class="header-search__result-body">
          <h3 class="header-search__result-title">${item.title}</h3>
          <div class="header-search__result-meta">
            ${categoryHtml}
            ${priceHtml}
          </div>
        </div>
      </a>
    `;
  };

  const renderResults = (items, isSuggestions = false, suggestionReason = '', searchTerm = '') => {
    clearResults();
    currentResults = items;

    if (items.length === 0) {
      if (emptyState) {
        emptyState.removeAttribute('hidden');
      }
      return;
    }

    if (isSuggestions && suggestionReason) {
      const headingText = searchTerm
        ? `${noResultsText} "${searchTerm}". ${suggestionReason}`
        : suggestionReason;

      const heading = document.createElement('p');
      heading.className = 'header-search__suggestion-heading';
      heading.textContent = headingText;
      resultsContainer.insertBefore(heading, resultsContainer.firstChild);
    }

    const list = searchRoot.querySelector('[data-header-search-results] > [data-header-search-list]:not([data-header-search-suggestions] *)');

    if (list) {
      list.innerHTML = items.map(renderResultItem).join('');
    }
  };

  const fetchResults = async (query) => {
    if (isLoading) {
      return;
    }

    isLoading = true;
    searchRoot.classList.add('is-loading');

    try {
      const params = new URLSearchParams();
      params.append('action', 'mauswp_search_products');
      params.append('nonce', nonce);

      if (query) {
        params.append('s', query);
      }

      const response = await window.fetch(`${ajaxUrl}?${params.toString()}`);

      if (!response.ok) {
        throw new Error(`Request failed with status ${response.status}`);
      }

      const data = await response.json();

      if (data.success && data.data && typeof data.data === 'object') {
        renderResults(
          data.data.items || [],
          data.data.is_suggestions || false,
          data.data.suggestion_reason || '',
          data.data.search_term || ''
        );
      } else {
        renderResults([]);
      }
    } catch (error) {
      renderResults([]);
    } finally {
      isLoading = false;
      searchRoot.classList.remove('is-loading');
    }
  };

  const loadSuggestions = async () => {
    if (isLoading) {
      return;
    }

    isLoading = true;

    try {
      const params = new URLSearchParams();
      params.append('action', 'mauswp_search_suggestions');
      params.append('nonce', nonce);

      const response = await window.fetch(`${ajaxUrl}?${params.toString()}`);

      if (!response.ok) {
        throw new Error(`Request failed with status ${response.status}`);
      }

      const data = await response.json();

      if (data.success && Array.isArray(data.data) && data.data.length > 0) {
        renderResults(data.data, true, (window.mauswpData && window.mauswpData.searchPopularHeading) || 'Productos populares', '');
      }
    } catch (error) {
      // Silently fail suggestions
    } finally {
      isLoading = false;
    }
  };

  const debouncedSearch = debounce((query) => {
    if (query.length >= 2) {
      fetchResults(query);
    } else {
      clearResults();
      loadSuggestions();
    }
  }, 300);

  const updateSelection = () => {
    const items = searchRoot.querySelectorAll('[data-header-search-item]');

    items.forEach((item, index) => {
      const isSelected = index === selectedIndex;
      item.classList.toggle('is-selected', isSelected);
      item.setAttribute('aria-selected', isSelected ? 'true' : 'false');
    });

    if (selectedIndex >= 0 && items[selectedIndex]) {
      items[selectedIndex].scrollIntoView({ block: 'nearest' });
    }
  };

  const navigateItems = (direction) => {
    const items = searchRoot.querySelectorAll('[data-header-search-item]');

    if (items.length === 0) {
      return;
    }

    if (direction === 'next') {
      selectedIndex = selectedIndex < items.length - 1 ? selectedIndex + 1 : 0;
    } else {
      selectedIndex = selectedIndex > 0 ? selectedIndex - 1 : items.length - 1;
    }

    updateSelection();
  };

  trigger.addEventListener('click', openPanel);
  closeButton?.addEventListener('click', closePanel);

  input.addEventListener('input', () => {
    const query = input.value.trim();
    debouncedSearch(query);
  });

  input.addEventListener('keydown', (event) => {
    const items = searchRoot.querySelectorAll('[data-header-search-item]');

    if (event.key === 'ArrowDown') {
      event.preventDefault();
      navigateItems('next');
      return;
    }

    if (event.key === 'ArrowUp') {
      event.preventDefault();
      navigateItems('prev');
      return;
    }

    if (event.key === 'Enter') {
      event.preventDefault();

      if (selectedIndex >= 0 && items[selectedIndex]) {
        items[selectedIndex].click();
      }

      return;
    }

    if (event.key === 'Escape') {
      closePanel();
      trigger.focus();
    }
  });

  document.addEventListener('click', (event) => {
    const target = event.target;

    if (target instanceof Node && searchRoot.contains(target)) {
      return;
    }

    if (!panel.hasAttribute('hidden')) {
      closePanel();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !panel.hasAttribute('hidden')) {
      closePanel();
      trigger.focus();
    }
  });
};

const initShopArchiveFilters = () => {
  const archives = document.querySelectorAll('[data-shop-archive]');

  archives.forEach((archive) => {
    const drawer = archive.querySelector('[data-shop-filters-drawer]');
    const openButton = archive.querySelector('[data-shop-filters-open]');

    if (!drawer || !openButton) {
      return;
    }

    let isLoading = false;

    const closeDrawer = () => {
      drawer.setAttribute('hidden', 'hidden');
      openButton.setAttribute('aria-expanded', 'false');
      document.documentElement.classList.remove('shop-filters-open');
    };

    const openDrawer = () => {
      drawer.removeAttribute('hidden');
      openButton.setAttribute('aria-expanded', 'true');
      document.documentElement.classList.add('shop-filters-open');
    };

    const replaceArchiveContent = (htmlText, url) => {
      const parser = new DOMParser();
      const nextDocument = parser.parseFromString(htmlText, 'text/html');
      const nextArchive = nextDocument.querySelector('[data-shop-archive]');

      if (!nextArchive) {
        window.location.href = url;
        return;
      }

      const currentToolbar = archive.querySelector('[data-shop-archive-toolbar]');
      const nextToolbar = nextArchive.querySelector('[data-shop-archive-toolbar]');
      const currentResults = archive.querySelector('[data-shop-archive-results]');
      const nextResults = nextArchive.querySelector('[data-shop-archive-results]');
      const currentDrawer = archive.querySelector('[data-shop-filters-drawer]');
      const nextDrawer = nextArchive.querySelector('[data-shop-filters-drawer]');

      if (currentToolbar && nextToolbar) {
        currentToolbar.replaceWith(nextToolbar);
      }

      if (currentResults && nextResults) {
        currentResults.replaceWith(nextResults);
      }

      if (currentDrawer && nextDrawer) {
        currentDrawer.replaceWith(nextDrawer);
      }

      window.history.pushState({}, '', url);
      closeDrawer();
    };

    const fetchArchive = async (url) => {
      if (isLoading) {
        return;
      }

      isLoading = true;
      archive.classList.add('is-loading');

      try {
        const response = await window.fetch(url, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        if (!response.ok) {
          throw new Error(`Request failed with status ${response.status}`);
        }

        const htmlText = await response.text();
        replaceArchiveContent(htmlText, url);
      } catch (error) {
        window.location.href = url;
      } finally {
        isLoading = false;
        archive.classList.remove('is-loading');
      }
    };

    archive.addEventListener('click', (event) => {
      const target = event.target;

      if (!(target instanceof Element)) {
        return;
      }

      const openTrigger = target.closest('[data-shop-filters-open]');
      const closeTrigger = target.closest('[data-shop-filters-close]');
      const resetTrigger = target.closest('[data-shop-filters-reset]');
      const paginationLink = target.closest('.shop-category__pagination a');

      if (openTrigger) {
        event.preventDefault();
        openDrawer();
        return;
      }

      if (closeTrigger) {
        event.preventDefault();
        closeDrawer();
        return;
      }

      if (resetTrigger && resetTrigger instanceof HTMLAnchorElement) {
        event.preventDefault();
        fetchArchive(resetTrigger.href);
        return;
      }

      if (paginationLink && paginationLink instanceof HTMLAnchorElement) {
        event.preventDefault();
        fetchArchive(paginationLink.href);
      }
    });

    archive.addEventListener('submit', (event) => {
      const target = event.target;

      if (!(target instanceof HTMLFormElement) || !target.matches('[data-shop-filters-form]')) {
        return;
      }

      event.preventDefault();

      const formData = new window.FormData(target);
      const params = new URLSearchParams();

      formData.forEach((value, key) => {
        if (typeof value === 'string' && value !== '') {
          params.append(key, value);
        }
      });

      const url = `${target.action}${params.toString() ? `?${params.toString()}` : ''}`;
      fetchArchive(url);
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && !drawer.hasAttribute('hidden')) {
        closeDrawer();
      }
    });

  });
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    initMobileMenu();
    initMobileCatalogAccordion();
    initCatalogMegaMenu();
    initHeroCarousel();
    initProductSwiper();
    initFeaturesStripSwiper();
    initProductConfigInfoModal();
    initHeaderSearch();
    initShopArchiveFilters();
  });
} else {
  initMobileMenu();
  initMobileCatalogAccordion();
  initCatalogMegaMenu();
  initHeroCarousel();
  initProductSwiper();
  initFeaturesStripSwiper();
  initProductConfigInfoModal();
  initHeaderSearch();
  initShopArchiveFilters();
}
