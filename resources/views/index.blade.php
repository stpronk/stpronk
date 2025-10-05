<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Steve Pronk">
    <meta name="google-site-verification" content="xfuyR1cAs5xv7EvMWANbPC7PMb7G1xlk8G5pMl2tnlI"/>
    <meta name="description" content="My vision is to build Website’s & Web Applications for the future. Keeping in mind the rapid changing technology and being ahead of the rest!">
    <meta name="keywords" content="HTML,CSS,JavaScript,PHP,React,Laravel,Symphony,Scss,Sass,Illustrator,Git,Github,Gitlab,Bootstrap,StPronk,Steve,Pronk,Developer,Designer,Develop,Design,Web,Website,Zuid,Holland,Zuid-Holland,Zoetermeer,Nederland,Maatwerk">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <meta name="msapplication-TileColor" content="#000">
    <meta name="theme-color" content="#000">
    <title>StPronk | Developer</title>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#10b981',
                        secondary: '#06b6d4',
                        accent: '#059669'
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-text {
            background: linear-gradient(90deg, #10b981, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .skill-card {
            transition: all 0.3s ease;
        }
        .skill-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .dark .skill-card {
            background-color: #1f2937;
        }
        .dark .experience-card {
            background-color: #1f2937;
        }
        .sytatsu-section {
            background: linear-gradient(135deg, #06b6d4, #10b981);
        }
        .dark .sytatsu-section {
            background: linear-gradient(135deg, #0e7490, #059669);
        }
        .contact-form input,
        .contact-form textarea {
            transition: border-color 0.3s;
        }
        .contact-form input:focus,
        .contact-form textarea:focus {
            border-color: #10b981;
        }
    </style>
    @livewireStyles
</head>
<body class="bg-white text-slate-800 dark:bg-gray-900 dark:text-slate-100 min-h-screen">
    <!-- Header / Navigation -->
    <header class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-800">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="#" class="text-xl font-bold gradient-text">StPronk</a>
            <nav class="hidden md:flex space-x-8">
                <a href="#sytatsu" class="hover:text-primary transition">Sytatsu</a>
                <a href="#about" class="hover:text-primary transition">About</a>
                <a href="#skills" class="hover:text-primary transition">Skills</a>
                <a href="#experience" class="hover:text-primary transition">Experience</a>
                <a href="#contact" class="hover:text-primary transition">Contact</a>
            </nav>
            <div class="flex items-center space-x-4">
                <button id="theme-toggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <i class="fas fa-sun h-4 text-yellow-400 hidden dark:block"></i>
                    <i class="fas fa-moon h-4 text-gray-700 dark:hidden"></i>
                </button>
                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <div class="container mx-auto px-6 py-4 flex flex-col space-y-3">
                <a href="#sytatsu" class="hover:text-primary transition py-2">Sytatsu</a>
                <a href="#about" class="hover:text-primary transition py-2">About</a>
                <a href="#skills" class="hover:text-primary transition py-2">Skills</a>
                <a href="#experience" class="hover:text-primary transition py-2">Experience</a>
                <a href="#contact" class="hover:text-primary transition py-2">Contact</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="py-16 md:py-24">
        <div class="container mx-auto px-6 max-w-4xl flex flex-col-reverse md:flex-row items-center">
            <div class="mt-10 md:mt-0">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Hi, I’m Steve — Full-Stack Developer
                </h1>
                <p class="text-lg mb-8 max-w-lg">
                    Passionate about building responsive and efficient web applications using modern technologies.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#experience" class="px-6 py-3 bg-primary text-white rounded-md hover:bg-emerald-600 transition">My experience</a>
                    <a href="#contact" class="px-6 py-3 border border-primary text-primary dark:text-white rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition">Contact Me</a>
                </div>
            </div>
            <div class="flex justify-end">
                <div class="w-64 h-64 rounded-full border-4 border-white dark:border-gray-700 overflow-hidden shadow-xl">
                    <img src="images/stpronk_hero_self.jpg" alt="Steve Pronk" class="w-full h-full object-cover" />
                </div>
            </div>
        </div>
    </section>

    <!-- Sytatsu Section -->
    <section id="sytatsu" class="py-16 sytatsu-section text-white">
        <div class="container mx-auto px-6 max-w-4xl text-center">
            <h2 class="text-3xl font-bold mb-6">
                <span class="hidden">Sytatsu</span>
                <img src="images/sytatsu_white_no_background_text_only.webp" alt="Sytatsu" class="inline-block h-24" />
            </h2>
            <p class="mb-8 max-w-2xl mx-auto">
                My personal 3D-print studio & webshop, creating innovative and functional 3D models. Explore my work and collaborate with me.
            </p>
            <a href="https://www.sytatsu.nl/?source=stpronk" target="_blank" class="inline-block px-6 py-3 bg-white text-primary rounded-md font-bold hover:bg-gray-100 transition">Visit Sytatsu.nl</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-6 max-w-4xl">
            <h2 class="text-3xl font-bold mb-8 gradient-text">About Me</h2>
            <div class="bg-white dark:bg-gray-700 rounded-xl shadow-lg p-8">
                <p class="mb-4">
                    I'm a dedicated full-stack developer with experience in creating scalable web solutions. My approach combines technical excellence with clean, user-focused design.
                </p>
                <p>
                    With a strong foundation in both front-end and back-end development, I specialize in building modern applications using Laravel, Vue.js and various other technologies.
                </p>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="py-16">
        <div class="container mx-auto px-6 max-w-4xl">
            <h2 class="text-3xl font-bold mb-8 gradient-text">Skills</h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                <div class="skill-card p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-200 dark:border-slate-700 shadow-sm">
                    <p class="font-semibold text-slate-800 dark:text-white">HTML5</p>
                </div>
                <div class="skill-card p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-200 dark:border-slate-700 shadow-sm">
                    <p class="font-semibold text-slate-800 dark:text-white">CSS</p>
                </div>
                <div class="skill-card p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-200 dark:border-slate-700 shadow-sm">
                    <p class="font-semibold text-slate-800 dark:text-white">JavaScript</p>
                </div>
                <div class="skill-card p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-200 dark:border-slate-700 shadow-sm">
                    <p class="font-semibold text-slate-800 dark:text-white">TailwindCSS</p>
                </div>
                <div class="skill-card p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-200 dark:border-slate-700 shadow-sm">
                    <p class="font-semibold text-slate-800 dark:text-white">Vue.js</p>
                </div>
                <div class="skill-card p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-200 dark:border-slate-700 shadow-sm">
                    <p class="font-semibold text-slate-800 dark:text-white">React</p>
                </div>
                <div class="skill-card p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-200 dark:border-slate-700 shadow-sm">
                    <p class="font-semibold text-slate-800 dark:text-white">Symfony</p>
                </div>
                <div class="skill-card p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-200 dark:border-slate-700 shadow-sm">
                    <p class="font-semibold text-slate-800 dark:text-white">Laravel</p>
                </div>
                <div class="skill-card p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-200 dark:border-slate-700 shadow-sm">
                    <p class="font-semibold text-slate-800 dark:text-white">Livewire</p>
                </div>
                <div class="skill-card p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-200 dark:border-slate-700 shadow-sm">
                    <p class="font-semibold text-slate-800 dark:text-white">FilamentPHP</p>
                </div>
                <div class="skill-card p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-200 dark:border-slate-700 shadow-sm">
                    <p class="font-semibold text-slate-800 dark:text-white">MySQL</p>
                </div>
                <div class="skill-card p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-200 dark:border-slate-700 shadow-sm">
                    <p class="font-semibold text-slate-800 dark:text-white">Postgres</p>
                </div>
                <div class="skill-card p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-200 dark:border-slate-700 shadow-sm">
                    <p class="font-semibold text-slate-800 dark:text-white">RabbitMQ</p>
                </div>
                <div class="skill-card p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-200 dark:border-slate-700 shadow-sm">
                    <p class="font-semibold text-slate-800 dark:text-white">Docker</p>
                </div>
                <div class="skill-card p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-200 dark:border-slate-700 shadow-sm">
                    <p class="font-semibold text-slate-800 dark:text-white">Python</p>
                </div>
                <div class="skill-card p-4 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-200 dark:border-slate-700 shadow-sm">
                    <p class="font-semibold text-slate-800 dark:text-white">AI</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Work Experience Section -->
    <section id="experience" class="py-16 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-6 max-w-4xl">
            <h2 class="text-3xl font-bold mb-8 gradient-text">Work Experience</h2>
            <div class="space-y-6">
                <div class="experience-card bg-white dark:bg-gray-700 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-600">
                    <h3 class="text-xl font-bold">Frontend Developer</h3>
                    <p class="text-primary">Tech Solutions Inc.</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Jan 2021 – Present</p>
                    <p>Developed responsive UI components using React and Tailwind CSS, improving performance by 30%.</p>
                </div>
                <div class="experience-card bg-white dark:bg-gray-700 rounded-lg shadow p-6 border border-gray-200 dark:border-gray-600">
                    <h3 class="text-xl font-bold">Full Stack Developer</h3>
                    <p class="text-primary">Web Innovations LLC</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Jun 2019 – Dec 2020</p>
                    <p>Built RESTful APIs with Node.js and Express, integrated MongoDB for data management.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16">
        <div class="container mx-auto px-6 max-w-2xl">
            <h2 class="text-3xl font-bold mb-8 gradient-text">Let’s Build Something Great</h2>
            <livewire:contact-form />
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-8 border-t border-gray-200 dark:border-gray-800">
        <div class="container mx-auto px-6 text-center max-w-4xl flex justify-between items-center">
            <p class="text-gray-500 dark:text-gray-400">Made with ❤️ by StPronk · © <span id="year"></span></p>
            <p id="back-to-top" class="text-sm text-gray-500 dark:text-gray-400 underline cursor-pointer">Back to top</p>
        </div>
    </footer>

    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('theme-toggle');
        const htmlElement = document.documentElement;

        function setTheme(theme) {
            if (theme === 'dark') {
                htmlElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                htmlElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        }

        // Check for saved theme or prefer dark scheme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            setTheme(savedTheme);
        } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            setTheme('dark');
        }

        // Toggle theme on click
        themeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.classList.contains('dark') ? 'dark' : 'light';
            setTheme(currentTheme === 'dark' ? 'light' : 'dark');
        });

        // Mobile Menu Toggle
        const mobileMenuButton = document.querySelector('[aria-label="Toggle menu"]');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Back to Top Button
        document.getElementById('back-to-top').addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        })

        // Smooth Scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Update Footer Year
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
    @livewireScripts
</body>
</html>
