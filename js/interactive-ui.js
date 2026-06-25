document.addEventListener('DOMContentLoaded', function() {

    // 1. Interactive Bookmarks
    document.body.addEventListener('click', function(e) {
        if (e.target.closest('.bookmark-btn, .save-job-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.bookmark-btn, .save-job-btn');
            const icon = btn.querySelector('i');
            if (icon) {
                if (icon.classList.contains('bi-bookmark')) {
                    icon.classList.remove('bi-bookmark');
                    icon.classList.add('bi-bookmark-fill', 'text-primary');
                    toastr.success('Saved successfully!');
                } else {
                    icon.classList.remove('bi-bookmark-fill', 'text-primary');
                    icon.classList.add('bi-bookmark');
                    toastr.info('Removed from saved items.');
                }
            }
        }
    });

    // 2. Trust Tooltips for Verified Badges
    const verifiedBadges = document.querySelectorAll('.verified-badge, [title="Verified"]');
    verifiedBadges.forEach(badge => {
        badge.setAttribute('title', 'Verified by JobberRecruit via comprehensive KYC process.');
        badge.setAttribute('data-bs-toggle', 'tooltip');
        badge.setAttribute('data-bs-placement', 'top');
    });
    
    // Initialize tooltips
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // 3. Transform Pagination to Load More
    const paginationContainer = document.querySelector('.jobs-pagination, .pagination');
    if (paginationContainer) {
        const nextBtn = paginationContainer.querySelector('.page-item:not(.disabled) a[data-page], .page-item:last-child:not(.disabled) a');
        
        if (nextBtn) {
            // Hide traditional pagination
            paginationContainer.style.display = 'none';

            // Create Load More button
            const loadMoreWrapper = document.createElement('div');
            loadMoreWrapper.className = 'text-center mt-5 mb-4 load-more-wrapper';
            
            const loadMoreBtn = document.createElement('button');
            loadMoreBtn.className = 'btn btn-outline-primary btn-lg px-5';
            loadMoreBtn.innerHTML = 'Load More <i class="bi bi-arrow-clockwise ms-2"></i>';
            
            loadMoreWrapper.appendChild(loadMoreBtn);
            paginationContainer.parentNode.insertBefore(loadMoreWrapper, paginationContainer.nextSibling);

            loadMoreBtn.addEventListener('click', async function() {
                // Simulate loading state
                const originalHtml = this.innerHTML;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Loading...';
                this.disabled = true;

                try {
                    const url = nextBtn.getAttribute('href') || window.location.href + '?page=' + nextBtn.getAttribute('data-page');
                    const response = await fetch(url);
                    if (!response.ok) throw new Error('Network response was not ok');
                    const htmlText = await response.text();
                    
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(htmlText, 'text/html');
                    
                    // Identify the container holding the items (assuming .job-card wrapper or similar list)
                    // We'll look for the main grid container. Usually it has .row or is parent of .job-card
                    const newItems = doc.querySelectorAll('.job-card, .candidate-card, .company-card');
                    if (newItems.length > 0) {
                        const targetContainer = document.querySelector('.job-card, .candidate-card, .company-card').parentNode;
                        newItems.forEach(item => targetContainer.appendChild(item));
                    }
                    
                    // Update next button
                    const newNextBtn = doc.querySelector('.jobs-pagination .page-item:not(.disabled) a[data-page], .pagination .page-item:last-child:not(.disabled) a');
                    if (newNextBtn) {
                        nextBtn.setAttribute('href', newNextBtn.getAttribute('href'));
                        nextBtn.setAttribute('data-page', newNextBtn.getAttribute('data-page'));
                        this.innerHTML = originalHtml;
                        this.disabled = false;
                    } else {
                        this.innerHTML = 'No More Items';
                    }
                } catch (error) {
                    console.error('Error fetching more items:', error);
                    toastr.error('Failed to load more items.');
                    this.innerHTML = originalHtml;
                    this.disabled = false;
                }
            });
        }
    }

});
