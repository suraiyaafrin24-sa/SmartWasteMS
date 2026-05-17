<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SmartBin | Smart Waste Management System</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>

</head>

<body class="bg-gray-50 text-gray-800">

    <!-- NAVBAR -->

    <header class="fixed top-0 w-full bg-green-200 shadow-sm z-50">
        <div class="max-w-[1400px] mx-auto px-6 py-4 flex justify-between items-center">

            <div class="flex items-center space-x-2">

                <div
                    class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>

                <h1 class="text-2xl font-bold">
                    <span class="text-black">Smart</span><span class="text-green-900">Bin</span>
                </h1>

            </div>

            <nav class="hidden md:flex space-x-8 font-medium">

                <a href="#home" class="text-gray-700 text-base hover:font-bold hover:text-black">Home</a>
                <a href="#features" class="text-gray-700 text-base hover:font-bold hover:text-black">Features</a>
                <a href="#technology" class="text-gray-700 text-base hover:font-bold hover:text-black">Technology</a>
                <a href="#contact" class="text-gray-700 text-base hover:font-bold hover:text-black">Contact</a>

            </nav>

            <div class="flex items-center space-x-6">

                <a href="{{ route('login') }}" class="text-gray-600 hover:text-black font-medium">Login</a>
                
                <a href="{{ route('register') }}"
                    class="bg-emerald-600 text-white px-5 py-2 rounded-lg hover:bg-emerald-700 font-bold transition-all shadow-lg shadow-emerald-600/20">
                    Register
                </a>

            </div>

        </div>
    </header>

    <!-- HERO -->

    <section id="home" class="pt-32 pb-24 bg-green-50">

        <div class="max-w-[1400px] mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">

            <div>
                <div
                    class="inline-flex items-center px-4 py-1.5 bg-emerald-100 border border-emerald-200 rounded-full mb-6">
                    <span class="w-2 h-2 bg-emerald-600 rounded-full animate-pulse mr-3"></span>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-700">Official CT
                        Corporation Partner</span>
                </div>

                <h1 class="text-5xl font-bold leading-tight">
                    Smart Waste <br>
                    <span class="text-emerald-600">Management System</span>
                </h1>

                <p class="mt-6 text-gray-600 text-lg">

                    A modern platform that helps cities monitor waste levels,
                    track garbage collection, and improve environmental
                    sustainability using smart technologies.

                </p>

                <div class="mt-8 flex space-x-4">

                    <a href="{{ route('login') }}"
                        class="bg-red-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-red-700 transition-all shadow-lg shadow-red-600/30">
                        Emergency Request
                    </a>

                    <a href="{{ route('register') }}"
                        class="bg-emerald-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/30">
                        Join Network
                    </a>

                </div>

            </div>

            <div class="relative group max-w-xl mx-auto">
                <img src="/images/bins.png"
                    class="rounded-3xl shadow-2xl relative z-0 transform group-hover:scale-[1.02] transition-transform duration-500">
            </div>

        </div>

    </section>

    <!-- FEATURES -->

    <section id="features" class="py-20 bg-gray-100">

        <div class="max-w-[1400px] mx-auto px-6">

            <div class="text-center mb-16">

                <h2 class="text-4xl font-bold">System Features</h2>

                <p class="text-gray-600 mt-3">
                    Key functionalities of the Smart Waste Management System
                </p>

            </div>

            <div class="grid md:grid-cols-3 gap-10">

                <div class="bg-white p-8 rounded-xl shadow hover:shadow-lg">

                    <h3 class="text-xl font-semibold mb-3">
                        Emergency Waste Request
                    </h3>

                    <p class="text-gray-600">
                        Citizens can request emergency waste pickup if their
                        bins are full before scheduled collection.
                    </p>

                </div>

                <div class="bg-white p-8 rounded-xl shadow hover:shadow-lg">

                    <h3 class="text-xl font-semibold mb-3">
                        Real-Time Monitoring
                    </h3>

                    <p class="text-gray-600">
                        Admin can track waste requests, collectors,
                        and bin status in real time.
                    </p>

                </div>

                <div class="bg-white p-8 rounded-xl shadow hover:shadow-lg">

                    <h3 class="text-xl font-semibold mb-3">
                        Systematic Scheduling
                    </h3>

                    <p class="text-gray-600">
                        Collectors receive organized schedules and sector assignments to reduce
                        time and improve operational efficiency.
                    </p>

                </div>

            </div>

        </div>

    </section>

    <!-- TECHNOLOGY -->

    <section id="technology" class="py-20 bg-white">

        <div class="max-w-[1400px] mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">

            <div>

                <img src="images/tech.png" class="rounded-xl shadow-lg">

            </div>

            <div>

                <h2 class="text-4xl font-bold mb-6">
                    Technology Behind SmartBin
                </h2>

                <p class="text-gray-600 mb-4">

                    The system integrates modern management features such as organized scheduling,
                    sector-based assignments, and real-time data monitoring to improve
                    waste collection efficiency.

                </p>

                <ul class="space-y-3 text-gray-600">

                    <li>✔ Systematic sector-based collection</li>
                    <li>✔ Admin monitoring dashboard</li>
                    <li>✔ Waste collector duty rosters</li>

                </ul>

            </div>

        </div>

        <!-- CONTACT & MAP -->

        <section id="contact" class="py-24 bg-green-50">
            <div class="max-w-[1400px] mx-auto px-6">

                <div class="grid lg:grid-cols-2 gap-16 items-start">

                    <!-- Service Coverage Info -->
                    <div class="bg-white p-12 rounded-[40px] border border-emerald-100 shadow-2xl shadow-emerald-900/10 relative overflow-hidden h-full">
                        <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-50 rounded-full blur-3xl opacity-50"></div>
                        
                        <div class="relative z-10">
                            <div class="inline-flex items-center px-4 py-1.5 bg-emerald-50 border border-emerald-100 rounded-full mb-6">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span>
                                <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600">Active Coverage</span>
                            </div>
                            
                            <h2 class="text-4xl font-black text-gray-900 leading-tight mb-6">
                                Operating across <br>
                                <span class="text-emerald-600">Uttara Model Town</span>
                            </h2>
                            
                            <p class="text-gray-500 font-medium leading-relaxed mb-10">
                                Our smart waste collection network covers all major sectors in Uttara. We prioritize efficient scheduling and systematic coordination to ensure every corner of the city stays clean and sustainable.
                            </p>
                            
                            <div class="space-y-8">
                                <div class="flex items-start gap-5">
                                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center shrink-0 border border-emerald-100">
                                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-gray-900 uppercase text-[11px] tracking-widest mb-1">Central Hub</h4>
                                        <p class="text-sm font-bold text-gray-600">Sector 15, Road 2, Block D <br> Uttara, Dhaka 1230</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start gap-5">
                                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center shrink-0 border border-emerald-100">
                                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-gray-900 uppercase text-[11px] tracking-widest mb-1">Emergency Support</h4>
                                        <p class="text-sm font-bold text-gray-600">+880 1234-567890 <br> Support for your waste collection needs</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-12">
                                <a href="{{ route('register') }}" class="inline-flex items-center text-emerald-600 font-black text-xs uppercase tracking-widest hover:gap-3 transition-all duration-300">
                                    Join our network now <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div
                        class="h-full min-h-[500px] w-full bg-gray-200 rounded-3xl overflow-hidden shadow-xl border border-gray-100 relative group">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.1435090089785!2d90.42196781536341!3d23.8130893922714!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c62f225d3091%3A0x6a117d91d17d057a!2sUnited%20International%20University!5e0!3m2!1sen!2sbd!4v1646485573420!5m2!1sen!2sbd"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            class="grayscale hover:grayscale-0 transition-all duration-700"></iframe>
                        <div
                            class="absolute bottom-6 left-6 right-6 bg-white p-6 rounded-2xl shadow-2xl border border-gray-100 group-hover:translate-y-2 transition-transform duration-500">
                            <h4 class="font-bold text-lg text-gray-900">Service Coverage</h4>
                            <p class="text-gray-500 text-sm mt-1">Real-time status of active collectors in your
                                zone.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <!-- FOOTER -->

        <footer class="bg-gray-900 text-gray-400 py-16">

            <div class="max-w-[1400px] mx-auto px-6 grid md:grid-cols-4 gap-10">

                <div>

                    <h3 class="text-xl font-bold text-white">
                        SmartBin
                    </h3>

                    <p class="mt-4">

                        A smart solution for improving urban waste management
                        and building cleaner, sustainable cities.

                    </p>

                </div>

                <div>

                    <h4 class="text-white font-semibold mb-4">
                        Resources
                    </h4>

                    <ul class="space-y-2">

                        <li><a href="#" class="hover:text-white">Documentation</a></li>
                        <li><a href="#" class="hover:text-white">API</a></li>
                        <li><a href="#" class="hover:text-white">Support</a></li>

                    </ul>

                </div>

                <div>

                    <h4 class="text-white font-semibold mb-4">
                        Company
                    </h4>

                    <ul class="space-y-2">

                        <li><a href="#" class="hover:text-white">About</a></li>
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                        <li><a href="#" class="hover:text-white">Privacy Policy</a></li>

                    </ul>

                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">
                        Contact Info
                    </h4>

                    <ul class="space-y-2">

                        <li><a href="#" class="hover:text-white">+123 456 7890</a></li>
                        <li><a href="#" class="hover:text-white">[EMAIL_ADDRESS]</a></li>
                        <li><a href="#" class="hover:text-white">Uttara, Dhaka, Bangladesh</a></li>

                    </ul>

                </div>

            </div>

            <div class="text-center mt-10 text-sm text-gray-500">

                © 2026 SmartBin | Smart Waste Management System

            </div>

        </footer>

</body>

</html>