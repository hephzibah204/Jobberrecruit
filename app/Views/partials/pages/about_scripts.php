
<script>

    document.addEventListener('DOMContentLoaded', function() {

        // Counter Animation

        const counters = document.querySelectorAll('.counter');

        const speed = 200;



        const animateCounter = (counter) => {

            const target = +counter.getAttribute('data-count');

            const count = +counter.innerText.replace(/,/g, '');

            const increment = target / speed;



            if (count < target) {

                counter.innerText = Math.ceil(count + increment).toLocaleString();

                setTimeout(() => animateCounter(counter), 1);

            } else {

                counter.innerText = target.toLocaleString();

            }

        };



        // Intersection Observer for counters and fade-in

        const observer = new IntersectionObserver((entries) => {

            entries.forEach(entry => {

                if (entry.isIntersecting) {

                    if (entry.target.classList.contains('counter')) {

                        entry.target.innerText = '0';

                        animateCounter(entry.target);

                    }

                    if (entry.target.classList.contains('fade-in')) {

                        entry.target.classList.add('visible');

                    }

                    observer.unobserve(entry.target);

                }

            });

        }, {

            threshold: 0.2

        });



        // Observe all counters and fade-in elements

        counters.forEach(counter => observer.observe(counter));

        document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));



        // Add platform icon hover effects

        document.querySelectorAll('.platform-icon').forEach(icon => {

            icon.addEventListener('mouseenter', function() {

                this.style.transform = 'scale(1.1)';

            });

            icon.addEventListener('mouseleave', function() {

                this.style.transform = 'scale(1)';

            });

        });

    });

</script>
