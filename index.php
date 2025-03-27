<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VolunTree | Make a Difference in Your Community</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --teal: #008080;
            --mustard: #FFDB58;
            --teal-light: #E6F2F2;
            --mustard-light: #FFF4CC;
        }
        .hero-pattern {
            background-image: linear-gradient(to bottom, rgba(0, 128, 128, 0.9), rgba(0, 100, 100, 0.9)), 
                              url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
        }
        .feature-card {
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .btn-primary {
            background-color: var(--mustard);
            color: #333;
        }
        .btn-primary:hover {
            background-color: #E6C44D;
        }
        .btn-secondary {
            background-color: var(--teal);
            color: white;
        }
        .btn-secondary:hover {
            background-color: #006666;
        }
        .stat-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="font-sans bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="index.php" class="flex items-center text-teal-600">
                <i class="fas fa-tree text-2xl mr-2" style="color: var(--teal);"></i>
                <span class="text-xl font-bold" style="color: var(--teal);">VolunTree</span>
            </a>
            <div class="flex items-center space-x-4">
                <a href="login.php" class="text-gray-600 hover:text-teal-700 font-medium">Log In</a>
                <a href="register.php" class="px-4 py-2 rounded-lg font-medium" style="background-color: var(--mustard); color: #333;">Sign Up</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-pattern text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Join the Global Volunteer Movement</h1>
            <p class="text-xl mb-8 max-w-3xl mx-auto">Be part of the 1 billion+ volunteers worldwide who are creating positive change in their communities.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="register.php" class="px-8 py-3 rounded-lg font-bold text-lg" style="background-color: var(--mustard); color: #333;">Get Started</a>
                <a href="#volunteer-stats" class="px-8 py-3 rounded-lg font-bold text-lg border-2 border-white">See the Impact</a>
            </div>
        </div>
    </section>

    <!-- Volunteer Statistics Section -->
    <section id="volunteer-stats" class="py-16" style="background-color: var(--teal);">
        <div class="max-w-7xl mx-auto px-4 text-white">
            <h2 class="text-3xl font-bold text-center mb-12">The Power of Volunteering Worldwide</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Stat 1 -->
                <div class="stat-card p-6 text-center">
                    <div class="text-4xl font-bold mb-2">1B+</div>
                    <p class="mb-3">Volunteers worldwide (UN Volunteers)</p>
                    <div class="text-xs text-gray-300">Source: United Nations Volunteers Report 2022</div>
                </div>
                
                <!-- Stat 2 -->
                <div class="stat-card p-6 text-center">
                    <div class="text-4xl font-bold mb-2">8.8B</div>
                    <p class="mb-3">Hours volunteered annually in the US alone</p>
                    <div class="text-xs text-gray-300">Source: AmeriCorps 2021 data</div>
                </div>
                
                <!-- Stat 3 -->
                <div class="stat-card p-6 text-center">
                    <div class="text-4xl font-bold mb-2">$200B</div>
                    <p class="mb-3">Economic value of volunteering in US annually</p>
                    <div class="text-xs text-gray-300">Source: Independent Sector</div>
                </div>
                
                <!-- Stat 4 -->
                <div class="stat-card p-6 text-center">
                    <div class="text-4xl font-bold mb-2">10M+</div>
                    <p class="mb-3">Nonprofit organizations worldwide</p>
                    <div class="text-xs text-gray-300">Source: Johns Hopkins University</div>
                </div>
            </div>

            <div class="mt-12 grid md:grid-cols-2 gap-8">
                <div class="bg-white bg-opacity-10 p-6 rounded-lg">
                    <h3 class="text-xl font-semibold mb-4">Volunteering by the Numbers</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2 text-mustard-300"></i>
                            <span>30% of Americans volunteer annually (Corporation for National and Community Service)</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2 text-mustard-300"></i>
                            <span>Volunteers are 27% more likely to find employment after being out of work (CNCS)</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle mt-1 mr-2 text-mustard-300"></i>
                            <span>Corporate volunteering has grown by 259% since 2007 (Chief Executives for Corporate Purpose)</span>
                        </li>
                    </ul>
                </div>
                <div class="bg-white bg-opacity-10 p-6 rounded-lg">
                    <h3 class="text-xl font-semibold mb-4">Global Volunteering Trends</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-globe-americas mt-1 mr-2 text-mustard-300"></i>
                            <span>Europe has the highest volunteer participation rate at 34% (European Commission)</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-globe-americas mt-1 mr-2 text-mustard-300"></i>
                            <span>Developing countries contribute 70% of UN Online Volunteers</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-globe-americas mt-1 mr-2 text-mustard-300"></i>
                            <span>Youth volunteering (ages 15-24) has increased by 28% since 2015 (UN)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12" style="color: var(--teal);">How VolunTree Works</h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Find Opportunities -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-md border-t-4" style="border-top-color: var(--teal);">
                    <div class="flex flex-col items-center text-center">
                        <div class="p-4 rounded-full mb-4" style="background-color: var(--teal-light);">
                            <i class="fas fa-search text-2xl" style="color: var(--teal);"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3" style="color: var(--teal);">Find Opportunities</h3>
                        <p class="text-gray-600">Browse hundreds of volunteer opportunities from local organizations in your area.</p>
                    </div>
                </div>
                
                <!-- Connect -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-md border-t-4" style="border-top-color: var(--mustard);">
                    <div class="flex flex-col items-center text-center">
                        <div class="p-4 rounded-full mb-4" style="background-color: var(--mustard-light);">
                            <i class="fas fa-handshake text-2xl" style="color: var(--mustard);"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3" style="color: var(--teal);">Connect</h3>
                        <p class="text-gray-600">Apply to opportunities that match your skills and schedule, and connect with organizations.</p>
                    </div>
                </div>
                
                <!-- Make an Impact -->
                <div class="feature-card bg-white p-6 rounded-xl shadow-md border-t-4" style="border-top-color: var(--teal);">
                    <div class="flex flex-col items-center text-center">
                        <div class="p-4 rounded-full mb-4" style="background-color: var(--teal-light);">
                            <i class="fas fa-heart text-2xl" style="color: var(--teal);"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3" style="color: var(--teal);">Make an Impact</h3>
                        <p class="text-gray-600">Track your volunteer hours, earn badges, and see the difference you're making in your community.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12" style="color: var(--teal);">What Our Volunteers Say</h2>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="p-8 rounded-lg shadow-md" style="background-color: var(--teal-light);">
                    <div class="flex items-center mb-4">
                        <img class="w-12 h-12 rounded-full mr-4" src="https://randomuser.me/api/portraits/women/32.jpg" alt="Sarah J.">
                        <div>
                            <h4 class="font-bold">Sarah J.</h4>
                            <p class="text-sm text-gray-600">Volunteer since 2020</p>
                        </div>
                    </div>
                    <p class="text-gray-700">"VolunTree made it so easy to find opportunities that fit my schedule. I've met amazing people and made a real impact in my community."</p>
                </div>
                
                <div class="p-8 rounded-lg shadow-md" style="background-color: var(--mustard-light);">
                    <div class="flex items-center mb-4">
                        <img class="w-12 h-12 rounded-full mr-4" src="https://randomuser.me/api/portraits/men/45.jpg" alt="Michael T.">
                        <div>
                            <h4 class="font-bold">Michael T.</h4>
                            <p class="text-sm text-gray-600">Volunteer since 2019</p>
                        </div>
                    </div>
                    <p class="text-gray-700">"Tracking my hours and seeing the collective impact of all volunteers is incredibly motivating. This platform is a game-changer!"</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16" style="background-color: var(--teal);">
        <div class="max-w-7xl mx-auto px-4 text-center text-white">
            <h2 class="text-3xl font-bold mb-6">Ready to Join Millions of Volunteers Worldwide?</h2>
            <p class="text-xl mb-8 max-w-3xl mx-auto">Be part of the movement that contributes over $200 billion in economic value annually through volunteering.</p>
            <a href="register.php" class="inline-block px-8 py-3 rounded-lg font-bold text-lg" style="background-color: var(--mustard); color: #333;">Sign Up Now</a>
        </div>
    </section>

    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <a href="index.php" class="flex items-center mb-4">
                        <i class="fas fa-tree text-xl mr-2" style="color: var(--mustard);"></i>
                        <span class="font-bold text-xl">VolunTree</span>
                    </a>
                    <p class="text-gray-400">Connecting volunteers with organizations to build stronger communities.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">For Volunteers</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Find Opportunities</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Track Hours</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Earn Badges</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">For Organizations</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Post Opportunities</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Manage Volunteers</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Track Impact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Company</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo date("Y"); ?> VolunTree. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>