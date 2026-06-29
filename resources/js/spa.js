/**
 * AgriNex Lightweight Client-Side SPA Router
 * Enables instant transitions without full-page reloads.
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Inject the premium top progress loading bar
    const bar = document.createElement('div');
    bar.id = 'spa-loading-bar';
    bar.style.position = 'fixed';
    bar.style.top = '0';
    bar.style.left = '0';
    bar.style.height = '3px';
    bar.style.backgroundColor = '#10b981'; // brand emerald green
    bar.style.zIndex = '9999999';
    bar.style.transition = 'width 0.3s ease, opacity 0.3s ease';
    bar.style.width = '0%';
    bar.style.opacity = '0';
    document.body.appendChild(bar);

    // 2. Intercept same-origin internal link clicks
    document.body.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (!link) return;

        // Skip non-HTTP links (mailto, tel, javascript)
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;

        const url = new URL(link.href, window.location.href);

        // Bypass checks
        if (url.origin !== window.location.origin) return; // external links
        if (link.hasAttribute('download') || link.getAttribute('target') === '_blank') return;
        
        // Skip authentication routes
        if (url.pathname.includes('logout') || url.pathname.includes('login') || url.pathname === '/login') return;

        // Prevent browser navigation and load via SPA
        e.preventDefault();
        navigateTo(url.href);
    });

    // 3. Intercept same-origin internal form submissions (GET filters only)
    document.body.addEventListener('submit', (e) => {
        const form = e.target;
        if (!form) return;

        // Only intercept GET forms (like search filters)
        if (form.method.toLowerCase() !== 'get') return;

        const action = form.getAttribute('action') || window.location.pathname;
        const url = new URL(action, window.location.href);
        
        if (url.origin !== window.location.origin) return;

        e.preventDefault();

        // Serialize form data into URL params
        const formData = new FormData(form);
        const params = new URLSearchParams();
        for (const [key, value] of formData.entries()) {
            if (value !== '') {
                params.append(key, value);
            }
        }

        url.search = params.toString();
        navigateTo(url.href);
    });

    // 4. Handle browser back and forward button clicks
    window.addEventListener('popstate', () => {
        loadPage(window.location.href, false);
    });
});

let isNavigating = false;

/**
 * Navigate to a new SPA url
 */
async function navigateTo(url) {
    window.history.pushState(null, '', url);
    await loadPage(url, true);
}

/**
 * Load the content of the target url and swap into the container
 */
async function loadPage(url, isNewPage = true) {
    if (isNavigating) return;
    isNavigating = true;

    const bar = document.getElementById('spa-loading-bar');
    if (bar) {
        bar.style.opacity = '1';
        bar.style.width = '30%';
    }

    try {
        if (bar) bar.style.width = '60%';

        const response = await fetch(url, {
            headers: {
                'X-SPA-Request': 'true'
            }
        });

        if (bar) bar.style.width = '85%';

        if (!response.ok) {
            window.location.href = url;
            return;
        }

        const html = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // Swap the document title
        document.title = doc.title;

        // Find spa content elements
        const newContent = doc.querySelector('#spa-content');
        const oldContent = document.querySelector('#spa-content');

        if (newContent && oldContent) {
            // Swap inner HTML
            oldContent.innerHTML = newContent.innerHTML;

            // Re-evaluate and execute scripts nested inside the swapped content
            const scripts = oldContent.querySelectorAll('script');
            scripts.forEach(script => {
                const newScript = document.createElement('script');
                
                // Copy all attributes (src, type, etc.)
                Array.from(script.attributes).forEach(attr => {
                    newScript.setAttribute(attr.name, attr.value);
                });

                // Copy inner JS code
                newScript.appendChild(document.createTextNode(script.innerHTML));

                // Replace the script element to force browser execution
                script.parentNode.replaceChild(newScript, script);
            });

            // Dispatch custom navigated event for reactive components (like the sidebar)
            window.dispatchEvent(new CustomEvent('spa:navigated'));

            // Scroll to the top of the viewport
            if (isNewPage) {
                window.scrollTo({ top: 0, behavior: 'instant' });
            }
        } else {
            // If the retrieved page doesn't contain a spa container, fallback to traditional load
            window.location.href = url;
            return;
        }
    } catch (e) {
        console.error('SPA Navigation Error:', e);
        window.location.href = url;
        return;
    } finally {
        isNavigating = false;
        if (bar) {
            bar.style.width = '100%';
            setTimeout(() => {
                bar.style.opacity = '0';
                setTimeout(() => {
                    bar.style.width = '0%';
                }, 300);
            }, 150);
        }
    }
}
