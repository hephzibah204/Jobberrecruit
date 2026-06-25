/**
 * Native Mobile App Feel Script
 * Implements pull-to-refresh, haptic feedback, bottom sheet behavior, and swipeable tabs.
 */

document.addEventListener('DOMContentLoaded', () => {
    // Check if we are on a mobile device
    const isMobile = window.matchMedia("(max-width: 767.98px)").matches;
    if (!isMobile) return;

    /* ==========================================================================
       1. Haptic Feedback
       ========================================================================== */
    const triggerHaptic = (duration = 50) => {
        if (navigator.vibrate) {
            navigator.vibrate(duration);
        }
    };

    // Add haptics to all buttons, links, and touchables
    document.body.addEventListener('click', (e) => {
        const target = e.target.closest('button, .btn, .nav-item, a, .touchable');
        if (target) {
            triggerHaptic(30); // Subtle 30ms vibration
        }
    });

    /* ==========================================================================
       2. Pull-to-Refresh
       ========================================================================== */
    let pStart = { x: 0, y: 0 };
    let pCurrent = { x: 0, y: 0 };
    const pRefreshThreshold = 100; // How far to pull down before refresh triggers
    let ptrIndicator = null;
    let isAtTop = true;

    // Create pull-to-refresh indicator
    const createPTRIndicator = () => {
        ptrIndicator = document.createElement('div');
        ptrIndicator.id = 'ptr-indicator';
        ptrIndicator.innerHTML = '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>';
        Object.assign(ptrIndicator.style, {
            position: 'fixed',
            top: '-50px',
            left: '50%',
            transform: 'translateX(-50%)',
            background: '#fff',
            borderRadius: '50%',
            padding: '8px',
            boxShadow: '0 4px 10px rgba(0,0,0,0.1)',
            zIndex: '1050',
            transition: 'top 0.2s ease',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            width: '40px',
            height: '40px'
        });
        document.body.appendChild(ptrIndicator);
    };

    document.addEventListener('touchstart', (e) => {
        if (typeof e.touches[0] === 'undefined') return;
        pStart.x = e.touches[0].screenX;
        pStart.y = e.touches[0].screenY;
        isAtTop = window.scrollY === 0;
    }, { passive: true });

    document.addEventListener('touchmove', (e) => {
        if (!isAtTop) return;
        pCurrent.x = e.touches[0].screenX;
        pCurrent.y = e.touches[0].screenY;
        
        const yDiff = pCurrent.y - pStart.y;
        const xDiff = Math.abs(pCurrent.x - pStart.x);
        
        // Only trigger if pulling down mostly vertically
        if (yDiff > 10 && yDiff > xDiff) {
            if (!ptrIndicator) createPTRIndicator();
            
            // Move indicator down based on pull distance (with friction)
            const moveY = Math.min(yDiff * 0.4, 80);
            ptrIndicator.style.top = `${moveY - 40}px`;
        }
    }, { passive: true });

    document.addEventListener('touchend', () => {
        if (!isAtTop || !ptrIndicator) return;
        
        const yDiff = pCurrent.y - pStart.y;
        
        if (yDiff > pRefreshThreshold) {
            // Trigger refresh
            triggerHaptic([30, 50, 30]);
            ptrIndicator.style.top = '20px';
            setTimeout(() => {
                window.location.reload();
            }, 400);
        } else {
            // Cancel refresh
            ptrIndicator.style.top = '-50px';
        }
        
        pStart = { x: 0, y: 0 };
        pCurrent = { x: 0, y: 0 };
    }, { passive: true });


    /* ==========================================================================
       3. Bottom Sheet Modals (Overrides Bootstrap Modals on Mobile)
       ========================================================================== */
    // Add CSS for Bottom Sheet
    const sheetStyles = document.createElement('style');
    sheetStyles.innerHTML = `
        @media (max-width: 767.98px) {
            .bottom-sheet .modal-dialog {
                position: fixed;
                margin: 0;
                width: 100%;
                height: 100%;
                padding: 0;
                display: flex;
                align-items: flex-end;
                justify-content: center;
                pointer-events: none;
            }
            .bottom-sheet .modal-content {
                width: 100%;
                max-height: 85vh;
                border: none;
                border-radius: 20px 20px 0 0;
                pointer-events: auto;
                animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .bottom-sheet .modal-header {
                border-bottom: 1px solid var(--border-light, #e2e8f0);
                padding: 1rem 1.5rem;
                position: relative;
            }
            /* Add drag handle */
            .bottom-sheet .modal-header::before {
                content: '';
                position: absolute;
                top: 8px;
                left: 50%;
                transform: translateX(-50%);
                width: 40px;
                height: 4px;
                background-color: #cbd5e1;
                border-radius: 4px;
            }
            @keyframes slideUp {
                from { transform: translateY(100%); }
                to { transform: translateY(0); }
            }
        }
    `;
    document.head.appendChild(sheetStyles);

    // Apply .bottom-sheet class to standard modals (like filters or menus)
    document.querySelectorAll('.modal').forEach(modal => {
        // Exclude large full-screen modals or those specifically opted out
        if (!modal.classList.contains('modal-fullscreen') && !modal.classList.contains('no-bottom-sheet')) {
            modal.classList.add('bottom-sheet');
        }
    });

    /* ==========================================================================
       4. Swipeable Tabs (For AI Resume Builder or Job Dashboards)
       ========================================================================== */
    let swipeStartX = 0;
    let swipeEndX = 0;
    
    // Resume builder swipe navigation
    const builderContainer = document.querySelector('#resume-form');
    if (builderContainer) {
        builderContainer.addEventListener('touchstart', e => {
            swipeStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        builderContainer.addEventListener('touchend', e => {
            swipeEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });
        
        const handleSwipe = () => {
            const diffX = swipeStartX - swipeEndX;
            const threshold = 100;
            
            // Only swipe horizontally, check if it's mostly a horizontal swipe
            if (Math.abs(diffX) > threshold) {
                // Determine if we swipe left (next) or right (prev)
                if (diffX > 0) {
                    // Swiped Left -> Go Next
                    const nextBtn = document.getElementById('mobile-next-btn');
                    if (nextBtn && nextBtn.style.visibility !== 'hidden' && !nextBtn.disabled) {
                        triggerHaptic(50);
                        nextBtn.click();
                    }
                } else {
                    // Swiped Right -> Go Prev
                    const prevBtn = document.getElementById('mobile-prev-btn');
                    if (prevBtn && prevBtn.style.visibility !== 'hidden' && !prevBtn.disabled) {
                        triggerHaptic(50);
                        prevBtn.click();
                    }
                }
            }
        };
    }
});
